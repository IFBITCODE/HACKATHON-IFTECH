<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardMetricsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardMetricsService $dashboardService
    ) {
    }

    public function index(Request $request)
    {
        $period = $request->get('period', 'month');

        if (!in_array($period, ['month', 'quarter', 'year'])) {
            $period = 'month';
        }

        $data = $this->dashboardService->getDashboardData(
            $period,
            $request->get('date')
        );

        return view(
            'admin.dashboard.index',
            compact('data')
        );
    }
}