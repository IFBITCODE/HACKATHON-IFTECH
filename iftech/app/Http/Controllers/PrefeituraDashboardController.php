<?php

namespace App\Http\Controllers;

use App\Models\Empreendedor;
use App\Models\Occurrence;
use App\Services\Dashboard\DashboardMetricsService;

class PrefeituraDashboardController extends Controller
{
    public function __construct(
        private DashboardMetricsService $dashboardService
    ) {
    }

    public function index()
    {
        // Reaproveita o mesmo service que alimenta /admin/dashboard,
        // pedindo o período "mês" como resumo padrão da home.
        $data = $this->dashboardService->getDashboardData('month');

        $pendentesCount = Empreendedor::where('status', 'pendente')->count();

        $ocorrenciasAbertas = Occurrence::whereIn('status', ['aberta', 'em_atendimento'])
            ->orderByDesc('occurred_at')
            ->limit(4)
            ->get();

        return view('prefeitura.homePrefeitura', [
            'data' => $data,
            'pendentesCount' => $pendentesCount,
            'ocorrenciasAbertas' => $ocorrenciasAbertas,
        ]);
    }
}