<?php

declare(strict_types=1);

class ComboController extends Controller
{
    private Combo $comboModel;
    public function __construct() { $this->comboModel = new Combo(); }
    public function index(): void { $this->requireAuth(); $this->render('combos/index', ['pageTitle' => 'Quản lý combo', 'combos' => $this->comboModel->all()]); }
    public function toggle(): void { $this->requireAuth(); $id = (int) ($_POST['id'] ?? 0); if ($id > 0) $this->comboModel->toggle($id); $this->setFlash('success', 'Đã cập nhật trạng thái combo.'); $this->redirect('/combos'); }
}
