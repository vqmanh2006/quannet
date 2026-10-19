<?php

declare(strict_types=1);

class Dashboard extends Model
{
    public function getComputerStats(): array
    {
        $statement = $this->db->query(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) AS available,
                SUM(CASE WHEN status = 'playing' THEN 1 ELSE 0 END) AS playing,
                SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) AS maintenance
             FROM computers"
        );

        $stats = $statement->fetch();

        return [
            'total' => (int) ($stats['total'] ?? 0),
            'available' => (int) ($stats['available'] ?? 0),
            'playing' => (int) ($stats['playing'] ?? 0),
            'maintenance' => (int) ($stats['maintenance'] ?? 0),
        ];
    }

    public function getTodayRevenue(): float
    {
        $statement = $this->db->query(
            "SELECT COALESCE(SUM(total_fee), 0) AS revenue
             FROM gaming_sessions
             WHERE status = 'completed' AND DATE(end_time) = CURDATE()"
        );

        return (float) $statement->fetchColumn();
    }

    public function getActiveSessions(): array
    {
        $statement = $this->db->query(
            "SELECT gs.id, gs.start_time, c.computer_code, c.computer_name, cu.full_name AS customer_name
             FROM gaming_sessions gs
             INNER JOIN computers c ON c.id = gs.computer_id
             LEFT JOIN customers cu ON cu.id = gs.customer_id
             WHERE gs.status = 'playing'
             ORDER BY gs.start_time ASC"
        );

        return $statement->fetchAll();
    }
}
