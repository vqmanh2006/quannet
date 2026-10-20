<?php

declare(strict_types=1);

class AccountTransaction extends Model
{
    public function all(): array
    {
        return $this->db->query(
            'SELECT at.*, ca.username, ca.display_name FROM account_transactions at INNER JOIN customer_accounts ca ON ca.id = at.account_id ORDER BY at.id DESC LIMIT 200'
        )->fetchAll();
    }

    public function topUp(int $accountId, float $amount, string $description, int $userId): bool
    {
        $this->db->beginTransaction();
        try {
            $lock = $this->db->prepare("SELECT id FROM customer_accounts WHERE id = :id AND status = 'active' FOR UPDATE");
            $lock->execute(['id' => $accountId]);
            if ($lock->fetch() === false || $amount <= 0) { $this->db->rollBack(); return false; }
            $update = $this->db->prepare('UPDATE customer_accounts SET balance = balance + :amount WHERE id = :id');
            $update->execute(['amount' => $amount, 'id' => $accountId]);
            $insert = $this->db->prepare("INSERT INTO account_transactions (account_id, type, amount, description, created_by) VALUES (:account_id, 'deposit', :amount, :description, :created_by)");
            $insert->execute(['account_id' => $accountId, 'amount' => $amount, 'description' => $description, 'created_by' => $userId]);
            $this->db->commit(); return true;
        } catch (Throwable $e) { if ($this->db->inTransaction()) $this->db->rollBack(); throw $e; }
    }

    public function buyCombo(int $accountId, int $comboId, int $userId): bool
    {
        $this->db->beginTransaction();
        try {
            $account = $this->db->prepare("SELECT id, status, balance FROM customer_accounts WHERE id = :id FOR UPDATE"); $account->execute(['id' => $accountId]); $accountRow = $account->fetch();
            $combo = $this->db->prepare("SELECT * FROM combos WHERE id = :id AND status = 'active' FOR UPDATE"); $combo->execute(['id' => $comboId]); $comboRow = $combo->fetch();
            if ($accountRow === false || $accountRow['status'] !== 'active' || $comboRow === false || (float) $accountRow['balance'] < (float) $comboRow['price']) { $this->db->rollBack(); return false; }
            $update = $this->db->prepare('UPDATE customer_accounts SET balance = balance - :price WHERE id = :id'); $update->execute(['price' => $comboRow['price'], 'id' => $accountId]);
            $package = $this->db->prepare("INSERT INTO account_combos (account_id, combo_id, remaining_minutes, expires_at, status) VALUES (:account_id, :combo_id, :minutes, DATE_ADD(NOW(), INTERVAL 30 DAY), 'available')"); $package->execute(['account_id' => $accountId, 'combo_id' => $comboId, 'minutes' => $comboRow['duration_minutes']]);
            $insert = $this->db->prepare("INSERT INTO account_transactions (account_id, type, amount, description, reference_id, created_by) VALUES (:account_id, 'buy_combo', :amount, :description, :reference_id, :created_by)"); $insert->execute(['account_id' => $accountId, 'amount' => -$comboRow['price'], 'description' => 'Mua combo ' . $comboRow['name'], 'reference_id' => $comboId, 'created_by' => $userId]);
            $this->db->commit(); return true;
        } catch (Throwable $e) { if ($this->db->inTransaction()) $this->db->rollBack(); throw $e; }
    }
}
