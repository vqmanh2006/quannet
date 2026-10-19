<?php

declare(strict_types=1);

class CustomerController extends Controller
{
    private Customer $customerModel;

    public function __construct()
    {
        $this->customerModel = new Customer();
    }

    public function index(): void
    {
        $this->requireAuth();

        $this->render('customers/index', [
            'pageTitle' => 'Quản lý khách hàng',
            'customers' => $this->customerModel->all(),
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();

        $this->render('customers/create', [
            'pageTitle' => 'Thêm khách hàng',
            'errors' => [],
            'old' => [
                'full_name' => '',
                'phone' => '',
                'email' => '',
                'note' => '',
            ],
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        $data = [
            'full_name' => trim((string) ($_POST['full_name'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'note' => trim((string) ($_POST['note'] ?? '')),
        ];

        $errors = $this->validate($data);

        if ($errors !== []) {
            $this->render('customers/create', [
                'pageTitle' => 'Thêm khách hàng',
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $this->customerModel->create($data);
        $this->setFlash('success', 'Đã thêm khách hàng.');
        $this->redirect('/customers');
    }

    public function edit(): void
    {
        $this->requireAuth();

        $id = (int) ($_GET['id'] ?? 0);
        $customer = $this->customerModel->find($id);

        if ($customer === null) {
            $this->setFlash('danger', 'Không tìm thấy khách hàng.');
            $this->redirect('/customers');
        }

        $this->render('customers/edit', [
            'pageTitle' => 'Cập nhật khách hàng',
            'errors' => [],
            'customer' => $customer,
        ]);
    }

    public function update(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);
        $customer = $this->customerModel->find($id);

        if ($customer === null) {
            $this->setFlash('danger', 'Không tìm thấy khách hàng.');
            $this->redirect('/customers');
        }

        $data = [
            'full_name' => trim((string) ($_POST['full_name'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'note' => trim((string) ($_POST['note'] ?? '')),
        ];

        $errors = $this->validate($data);

        if ($errors !== []) {
            $this->render('customers/edit', [
                'pageTitle' => 'Cập nhật khách hàng',
                'errors' => $errors,
                'customer' => array_merge($customer, $data),
            ]);
            return;
        }

        $this->customerModel->update($id, $data);
        $this->setFlash('success', 'Đã cập nhật khách hàng.');
        $this->redirect('/customers');
    }

    public function delete(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Dữ liệu không hợp lệ.');
            $this->redirect('/customers');
        }

        $this->customerModel->delete($id);
        $this->setFlash('success', 'Đã xóa khách hàng.');
        $this->redirect('/customers');
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['full_name'] === '') {
            $errors['full_name'] = 'Họ tên không được để trống.';
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        }

        return $errors;
    }
}
