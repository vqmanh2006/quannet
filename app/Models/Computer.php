<?php

declare(strict_types=1);

class Computer extends Model
{
    public function all(): array
    {
        $statement = $this->db->query('SELECT * FROM computers ORDER BY id DESC');

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM computers WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);

        $computer = $statement->fetch();

        return $computer !== false ? $computer : null;
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO computers (computer_code, computer_name, ip_address, hourly_rate, status)
             VALUES (:computer_code, :computer_name, :ip_address, :hourly_rate, :status)'
        );

        return $statement->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $statement = $this->db->prepare(
            'UPDATE computers
             SET computer_code = :computer_code,
                 computer_name = :computer_name,
                 ip_address = :ip_address,
                 hourly_rate = :hourly_rate,
                 status = :status
             WHERE id = :id'
        );

        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM computers WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    public function hasActiveSession(int $id): bool
    {
        $statement = $this->db->prepare(
            "SELECT COUNT(*) FROM gaming_sessions WHERE computer_id = :id AND status = 'playing'"
        );
        $statement->execute(['id' => $id]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function existsCode(string $code, ?int $excludeId = null): bool
    {
        $query = 'SELECT COUNT(*) FROM computers WHERE computer_code = :code';
        $params = ['code' => $code];

        if ($excludeId !== null) {
            $query .= ' AND id != :exclude_id';
            $params['exclude_id'] = $excludeId;
        }

        $statement = $this->db->prepare($query);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    public function available(): array
    {
        $statement = $this->db->query("SELECT * FROM computers WHERE status = 'available' ORDER BY computer_name ASC");

        return $statement->fetchAll();
    }
}
