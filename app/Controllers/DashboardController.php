<?php

declare(strict_types=1);

class DashboardController extends Controller
{
    private Dashboard $dashboardModel;
    private GamingSession $sessionModel;

    public function __construct()
    {
        $this->dashboardModel = new Dashboard();
        $this->sessionModel = new GamingSession();
    }

    public function index(): void
    {
        $this->requireAuth();

        $this->render('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'stats' => $this->dashboardModel->getComputerStats(),
            'activeSessionsCount' => $this->sessionModel->activeCount(),
            'todayRevenue' => $this->dashboardModel->getTodayRevenue(),
            'activeSessions' => $this->dashboardModel->getActiveSessions(),
        ]);
    }
}
