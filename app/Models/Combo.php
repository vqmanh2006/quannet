<?php

declare(strict_types=1);

class Combo extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM combos ORDER BY starts_at, id')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM combos WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $combo = $statement->fetch();
        return $combo !== false ? $combo : null;
    }

    public function active(): array
    {
        return $this->db->query("SELECT * FROM combos WHERE status = 'active' ORDER BY starts_at, id")->fetchAll();
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO combos (name, machine_group, starts_at, ends_at, price, status)
             VALUES (:name, :machine_group, :starts_at, :ends_at, :price, :status)'
        );
        return $statement->execute($data);
    }

    public function toggle(int $id): bool
    {
        $statement = $this->db->prepare(
            "UPDATE combos SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = :id"
        );
        return $statement->execute(['id' => $id]);
    }
}
