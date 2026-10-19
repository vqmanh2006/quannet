<?php

declare(strict_types=1);

class SessionController extends Controller
{
    private GamingSession $sessionModel;
    private Computer $computerModel;
    private Customer $customerModel;

    public function __construct()
    {
        $this->sessionModel = new GamingSession();
        $this->computerModel = new Computer();
        $this->customerModel = new Customer();
    }

    public function index(): void
    {
        $this->requireAuth();

        $this->render('sessions/index', [
            'pageTitle' => 'Quản lý phiên chơi',
            'sessions' => $this->sessionModel->all(),
        ]);
    }

    public function open(): void
    {
        $this->requireAuth();

        $this->render('sessions/open', [
            'pageTitle' => 'Mở phiên chơi',
            'errors' => [],
            'old' => ['computer_id' => '', 'customer_id' => ''],
            'computers' => $this->computerModel->available(),
            'customers' => $this->customerModel->all(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        $computerId = (int) ($_POST['computer_id'] ?? 0);
        $customerIdRaw = $_POST['customer_id'] ?? '';
        $customerId = $customerIdRaw === '' ? null : (int) $customerIdRaw;
        $errors = [];

        if ($computerId <= 0) {
            $errors['computer_id'] = 'Vui lòng chọn máy trạm.';
        }

        if ($customerId !== null && $customerId <= 0) {
            $errors['customer_id'] = 'Khách hàng không hợp lệ.';
        }

        if ($errors !== []) {
            $this->render('sessions/open', [
                'pageTitle' => 'Mở phiên chơi',
                'errors' => $errors,
                'old' => [
                    'computer_id' => (string) $computerId,
                    'customer_id' => $customerIdRaw,
                ],
                'computers' => $this->computerModel->available(),
                'customers' => $this->customerModel->all(),
            ]);
            return;
        }

        $user = $this->currentUser();
        if ($user === null) {
            $this->setFlash('danger', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('/login');
        }

        $opened = $this->sessionModel->openSession($computerId, $customerId, (int) $user['id']);

        if (!$opened) {
            $this->setFlash('danger', 'Không thể mở phiên. Máy có thể đang được sử dụng.');
            $this->redirect('/sessions/open');
        }

        $this->setFlash('success', 'Đã mở phiên chơi.');
        $this->redirect('/sessions');
    }

    public function close(): void
    {
        $this->requireAuth();

        $id = (int) ($_GET['id'] ?? 0);
        $session = $this->sessionModel->findPlaying($id);

        if ($session === null) {
            $this->setFlash('danger', 'Không tìm thấy phiên đang chơi.');
            $this->redirect('/sessions');
        }

        $this->render('sessions/close', [
            'pageTitle' => 'Đóng phiên chơi',
            'session' => $session,
            'estimatedFee' => $this->sessionModel->calculateFee(
                (string) $session['start_time'],
                date('Y-m-d H:i:s'),
                (float) $session['hourly_rate']
            ),
        ]);
    }

    public function completeClose(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Phiên chơi không hợp lệ.');
            $this->redirect('/sessions');
        }

        $closed = $this->sessionModel->closeSession($id);

        if (!$closed) {
            $this->setFlash('danger', 'Không thể đóng phiên. Phiên có thể đã được xử lý trước đó.');
            $this->redirect('/sessions');
        }

        $this->setFlash('success', 'Đã đóng phiên chơi và cập nhật trạng thái máy.');
        $this->redirect('/sessions');
    }
}
