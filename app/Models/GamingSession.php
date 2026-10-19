<?php

declare(strict_types=1);

class GamingSession extends Model
{
    public function all(): array
    {
        $statement = $this->db->query(
            "SELECT gs.*, c.computer_code, c.computer_name, cu.full_name AS customer_name
             FROM gaming_sessions gs
             INNER JOIN computers c ON c.id = gs.computer_id
             LEFT JOIN customers cu ON cu.id = gs.customer_id
             ORDER BY gs.id DESC"
        );

        return $statement->fetchAll();
    }

    public function activeCount(): int
    {
        $statement = $this->db->query("SELECT COUNT(*) FROM gaming_sessions WHERE status = 'playing'");

        return (int) $statement->fetchColumn();
    }

    public function findPlaying(int $id): ?array
    {
        $statement = $this->db->prepare(
            "SELECT gs.*, c.computer_name, c.computer_code
             FROM gaming_sessions gs
             INNER JOIN computers c ON c.id = gs.computer_id
             WHERE gs.id = :id AND gs.status = 'playing' LIMIT 1"
        );
        $statement->execute(['id' => $id]);

        $session = $statement->fetch();

        return $session !== false ? $session : null;
    }

    public function openSession(int $computerId, ?int $customerId, int $userId): bool
    {
        $this->db->beginTransaction();

        try {
            $computerStatement = $this->db->prepare(
                "SELECT id, hourly_rate FROM computers WHERE id = :id AND status = 'available' FOR UPDATE"
            );
            $computerStatement->execute(['id' => $computerId]);
            $computer = $computerStatement->fetch();

            if ($computer === false) {
                $this->db->rollBack();
                return false;
            }

            $sessionStatement = $this->db->prepare(
                "INSERT INTO gaming_sessions (computer_id, customer_id, start_time, hourly_rate, status, created_by)
                 VALUES (:computer_id, :customer_id, NOW(), :hourly_rate, 'playing', :created_by)"
            );
            $sessionStatement->bindValue('computer_id', $computerId, PDO::PARAM_INT);
            if ($customerId !== null) {
                $sessionStatement->bindValue('customer_id', $customerId, PDO::PARAM_INT);
            } else {
                $sessionStatement->bindValue('customer_id', null, PDO::PARAM_NULL);
            }
            $sessionStatement->bindValue('hourly_rate', (float) $computer['hourly_rate']);
            $sessionStatement->bindValue('created_by', $userId, PDO::PARAM_INT);
            $sessionStatement->execute();

            $updateComputer = $this->db->prepare("UPDATE computers SET status = 'playing' WHERE id = :id");
            $updateComputer->execute(['id' => $computerId]);

            $this->db->commit();

            return true;
        } catch (Throwable $throwable) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $throwable;
        }
    }

    public function closeSession(int $sessionId): bool
    {
        $this->db->beginTransaction();

        try {
            $statement = $this->db->prepare(
                "SELECT gs.id, gs.start_time, gs.hourly_rate, gs.computer_id
                 FROM gaming_sessions gs
                 WHERE gs.id = :id AND gs.status = 'playing' FOR UPDATE"
            );
            $statement->execute(['id' => $sessionId]);
            $session = $statement->fetch();

            if ($session === false) {
                $this->db->rollBack();
                return false;
            }

            $endTime = date('Y-m-d H:i:s');
            $totalFee = $this->calculateFee((string) $session['start_time'], $endTime, (float) $session['hourly_rate']);

            $updateSession = $this->db->prepare(
                "UPDATE gaming_sessions
                 SET end_time = :end_time,
                     total_fee = :total_fee,
                     status = 'completed'
                 WHERE id = :id"
            );
            $updateSession->execute([
                'end_time' => $endTime,
                'total_fee' => $totalFee,
                'id' => $sessionId,
            ]);

            $updateComputer = $this->db->prepare("UPDATE computers SET status = 'available' WHERE id = :computer_id");
            $updateComputer->execute(['computer_id' => (int) $session['computer_id']]);

            $this->db->commit();

            return true;
        } catch (Throwable $throwable) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $throwable;
        }
    }

    public function calculateFee(string $startTime, string $endTime, float $hourlyRate): float
    {
        $startTimestamp = strtotime($startTime);
        $endTimestamp = strtotime($endTime);

        if ($startTimestamp === false || $endTimestamp === false || $endTimestamp <= $startTimestamp) {
            return 0;
        }

        $minutes = ($endTimestamp - $startTimestamp) / 60;

        return round($minutes * ($hourlyRate / 60), 2);
    }
}
