<?php

declare(strict_types=1);

class CustomerAccount extends Model
{
    public function all(): array
    {
        $statement = $this->db->query(
            "SELECT ca.*, COALESCE(SUM(CASE WHEN ac.status IN ('available','using') THEN 1 ELSE 0 END), 0) AS active_combos
             FROM customer_accounts ca
             LEFT JOIN account_combos ac ON ac.account_id = ca.id
             GROUP BY ca.id
             ORDER BY ca.id DESC"
        );
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM customer_accounts WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $account = $statement->fetch();
        return $account !== false ? $account : null;
    }

    public function existsUsername(string $username, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM customer_accounts WHERE username = :username';
        $params = ['username' => $username];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return (int) $statement->fetchColumn() > 0;
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO customer_accounts (username, password_hash, display_name, balance, status)
             VALUES (:username, :password_hash, :display_name, 0, :status)'
        );
        return $statement->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE customer_accounts SET display_name = :display_name, status = :status';
        $params = ['display_name' => $data['display_name'], 'status' => $data['status'], 'id' => $id];
        if ($data['password_hash'] !== '') {
            $sql .= ', password_hash = :password_hash';
            $params['password_hash'] = $data['password_hash'];
        }
        $sql .= ' WHERE id = :id';
        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }
}
