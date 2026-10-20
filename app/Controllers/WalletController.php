<?php

declare(strict_types=1);

class WalletController extends Controller
{
    private CustomerAccount $accountModel; private Combo $comboModel; private AccountTransaction $transactionModel;
    public function __construct() { $this->accountModel = new CustomerAccount(); $this->comboModel = new Combo(); $this->transactionModel = new AccountTransaction(); }
    public function index(): void { $this->requireAuth(); $this->render('wallet/index', ['pageTitle' => 'Nạp tiền / Bán combo', 'accounts' => $this->accountModel->all(), 'combos' => $this->comboModel->active()]); }
    public function topup(): void { $this->requireAuth(); $amount = (float) ($_POST['amount'] ?? 0); $accountId = (int) ($_POST['account_id'] ?? 0); if ($amount <= 0 || $accountId <= 0) { $this->setFlash('danger', 'Tài khoản hoặc số tiền không hợp lệ.'); $this->redirect('/wallet'); } $this->transactionModel->topUp($accountId, $amount, 'Nạp tiền tại quầy', (int) $this->currentUser()['id']); $this->setFlash('success', 'Nạp tiền thành công.'); $this->redirect('/wallet'); }
    public function buyCombo(): void { $this->requireAuth(); $ok = $this->transactionModel->buyCombo((int) ($_POST['account_id'] ?? 0), (int) ($_POST['combo_id'] ?? 0), (int) $this->currentUser()['id']); $this->setFlash($ok ? 'success' : 'danger', $ok ? 'Mua combo thành công.' : 'Không đủ số dư hoặc dữ liệu không hợp lệ.'); $this->redirect('/wallet'); }
    public function transactions(): void { $this->requireAuth(); $this->render('transactions/index', ['pageTitle' => 'Lịch sử giao dịch', 'transactions' => $this->transactionModel->all()]); }
}
