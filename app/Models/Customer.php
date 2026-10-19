<?php

declare(strict_types=1);

class Customer extends Model
{
    public function all(): array
    {
        $statement = $this->db->query('SELECT * FROM customers ORDER BY id DESC');

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM customers WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);

        $customer = $statement->fetch();

        return $customer !== false ? $customer : null;
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO customers (full_name, phone, email, note)
             VALUES (:full_name, :phone, :email, :note)'
        );

        return $statement->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $statement = $this->db->prepare(
            'UPDATE customers
             SET full_name = :full_name,
                 phone = :phone,
                 email = :email,
                 note = :note
             WHERE id = :id'
        );

        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM customers WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }
}
