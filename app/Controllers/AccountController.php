<?php

declare(strict_types=1);

class AccountController extends Controller
{
    private CustomerAccount $accountModel;
    public function __construct() { $this->accountModel = new CustomerAccount(); }
    public function index(): void { $this->requireAuth(); $this->render('accounts/index', ['pageTitle' => 'Tài khoản khách hàng', 'accounts' => $this->accountModel->all()]); }
    public function create(): void { $this->requireAuth(); $this->render('accounts/create', ['pageTitle' => 'Tạo tài khoản', 'errors' => [], 'old' => ['username' => '', 'display_name' => '', 'status' => 'active']]); }
    public function store(): void
    {
        $this->requireAuth(); $password = (string) ($_POST['password'] ?? '');
        $data = ['username' => trim((string) ($_POST['username'] ?? '')), 'display_name' => trim((string) ($_POST['display_name'] ?? '')), 'password_hash' => password_hash($password, PASSWORD_DEFAULT), 'status' => 'active']; $errors = [];
        if ($data['username'] === '') $errors['username'] = 'Tên tài khoản không được để trống.'; if ($password === '' || strlen($password) < 4) $errors['password'] = 'Mật khẩu/PIN tối thiểu 4 ký tự.'; if ($data['display_name'] === '') $errors['display_name'] = 'Tên hiển thị không được để trống.'; if ($this->accountModel->existsUsername($data['username'])) $errors['username'] = 'Tên tài khoản đã tồn tại.';
        if ($errors !== []) { $this->render('accounts/create', ['pageTitle' => 'Tạo tài khoản', 'errors' => $errors, 'old' => $data]); return; }
        $this->accountModel->create($data); $this->setFlash('success', 'Đã tạo tài khoản khách hàng.'); $this->redirect('/accounts');
    }
}
