<?php

declare(strict_types=1);

class ComputerController extends Controller
{
    private Computer $computerModel;

    public function __construct()
    {
        $this->computerModel = new Computer();
    }

    public function index(): void
    {
        $this->requireAuth();

        $this->render('computers/index', [
            'pageTitle' => 'Quản lý máy trạm',
            'computers' => $this->computerModel->all(),
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();

        $this->render('computers/create', [
            'pageTitle' => 'Thêm máy trạm',
            'errors' => [],
            'old' => [
                'computer_code' => '',
                'computer_name' => '',
                'ip_address' => '',
                'hourly_rate' => '10000',
                'status' => 'available',
            ],
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        $data = [
            'computer_code' => trim((string) ($_POST['computer_code'] ?? '')),
            'computer_name' => trim((string) ($_POST['computer_name'] ?? '')),
            'ip_address' => trim((string) ($_POST['ip_address'] ?? '')),
            'hourly_rate' => (float) ($_POST['hourly_rate'] ?? 0),
            'status' => (string) ($_POST['status'] ?? 'available'),
        ];

        $errors = $this->validate($data);

        if ($this->computerModel->existsCode($data['computer_code'])) {
            $errors['computer_code'] = 'Mã máy đã tồn tại.';
        }

        if ($errors !== []) {
            $this->render('computers/create', [
                'pageTitle' => 'Thêm máy trạm',
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $this->computerModel->create($data);
        $this->setFlash('success', 'Đã thêm máy trạm.');
        $this->redirect('/computers');
    }

    public function edit(): void
    {
        $this->requireAuth();

        $id = (int) ($_GET['id'] ?? 0);
        $computer = $this->computerModel->find($id);

        if ($computer === null) {
            $this->setFlash('danger', 'Không tìm thấy máy trạm.');
            $this->redirect('/computers');
        }

        $this->render('computers/edit', [
            'pageTitle' => 'Cập nhật máy trạm',
            'errors' => [],
            'computer' => $computer,
        ]);
    }

    public function update(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);
        $computer = $this->computerModel->find($id);

        if ($computer === null) {
            $this->setFlash('danger', 'Không tìm thấy máy trạm.');
            $this->redirect('/computers');
        }

        $data = [
            'computer_code' => trim((string) ($_POST['computer_code'] ?? '')),
            'computer_name' => trim((string) ($_POST['computer_name'] ?? '')),
            'ip_address' => trim((string) ($_POST['ip_address'] ?? '')),
            'hourly_rate' => (float) ($_POST['hourly_rate'] ?? 0),
            'status' => (string) ($_POST['status'] ?? 'available'),
        ];

        $errors = $this->validate($data);

        if ($this->computerModel->existsCode($data['computer_code'], $id)) {
            $errors['computer_code'] = 'Mã máy đã tồn tại.';
        }

        if ($errors !== []) {
            $this->render('computers/edit', [
                'pageTitle' => 'Cập nhật máy trạm',
                'errors' => $errors,
                'computer' => array_merge($computer, $data),
            ]);
            return;
        }

        $this->computerModel->update($id, $data);

        $this->setFlash('success', 'Đã cập nhật máy trạm.');
        $this->redirect('/computers');
    }

    public function delete(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Dữ liệu không hợp lệ.');
            $this->redirect('/computers');
        }

        if ($this->computerModel->hasActiveSession($id)) {
            $this->setFlash('danger', 'Không thể xóa máy đang có phiên chơi.');
            $this->redirect('/computers');
        }

        $this->computerModel->delete($id);
        $this->setFlash('success', 'Đã xóa máy trạm.');
        $this->redirect('/computers');
    }

    private function validate(array $data): array
    {
        $errors = [];
        $validStatuses = ['available', 'playing', 'maintenance'];

        if ($data['computer_code'] === '') {
            $errors['computer_code'] = 'Mã máy không được để trống.';
        }

        if ($data['computer_name'] === '') {
            $errors['computer_name'] = 'Tên máy không được để trống.';
        }

        if ($data['hourly_rate'] <= 0) {
            $errors['hourly_rate'] = 'Đơn giá theo giờ phải lớn hơn 0.';
        }

        if (!in_array($data['status'], $validStatuses, true)) {
            $errors['status'] = 'Trạng thái máy không hợp lệ.';
        }

        return $errors;
    }
}
