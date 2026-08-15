<?php

namespace App\Services\Dashboard;

use App\Models\Empreendedor;
use App\Models\MetricDaily;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardMetricsService
{
    public function getDashboardData(
        string $period = 'month',
        ?string $referenceDate = null
    ): array {
        $reference = $referenceDate
            ? Carbon::parse($referenceDate)
            : Carbon::now();

        [$start, $end] = $this->resolvePeriod(
            $period,
            $reference
        );

        [$previousStart, $previousEnd] = $this->resolvePreviousPeriod(
            $period,
            $start,
            $end
        );

        $current = MetricDaily::whereBetween(
            'metric_date',
            [$start, $end]
        )->get();

        $previous = MetricDaily::whereBetween(
            'metric_date',
            [$previousStart, $previousEnd]
        )->get();

        return [
            'period' => [ /* ...igual... */ ],

            'kpis' => $this->buildKpis($current, $previous),

            'timeline' => $this->buildTimeline($current),

            'top_content' => [
                'attractions' => $this->aggregateJson($current, 'top_attractions'),
                'routes' => $this->aggregateJson($current, 'top_routes'),
                'events' => $this->aggregateJson($current, 'top_events'),
            ],

            'profile' => [
                'geo' => $this->aggregateJson($current, 'geo_origin', false),
            ],

            'empreendedores' => $this->buildEmpreendedoresStats(),
            'location_heatmap' => $this->buildLocationHeatmap(),
        ];
    }

    private function buildEmpreendedoresStats(): array
    {
        return [
            'pendentes' => Empreendedor::where('status', 'pendente')->count(),
            'aprovados' => Empreendedor::where('status', 'aprovado')->count(),
            'rejeitados' => Empreendedor::where('status', 'rejeitado')->count(),
            'suspensos' => Empreendedor::where('status', 'suspenso')->count(),
            'total' => Empreendedor::count(),
        ];
    }

    private function buildLocationHeatmap(): array
    {
        return Empreendedor::where('status', 'aprovado')
            ->whereNotNull('bairro')
            ->where('bairro', '!=', '')
            ->selectRaw('bairro, COUNT(*) as total')
            ->groupBy('bairro')
            ->orderByDesc('total')
            ->limit(15)
            ->pluck('total', 'bairro')
            ->toArray();
    }

    private function buildKpis(
        Collection $current,
        Collection $previous
    ): array {
        $accesses = $current->sum('accesses_total');
        $uniqueVisitors = $current->sum('unique_visitors');
        $recurringVisitors = $current->sum('recurring_visitors');

        $previousAccesses = $previous->sum('accesses_total');
        $previousUnique = $previous->sum('unique_visitors');
        $previousRecurring = $previous->sum('recurring_visitors');

        $avgNavigation = $current->count()
            ? (int) $current->avg('avg_navigation_seconds')
            : 0;

        $returnRate = $uniqueVisitors > 0
            ? round(
                ($recurringVisitors / $uniqueVisitors) * 100,
                2
            )
            : 0;

        $previousReturnRate = $previousUnique > 0
            ? round(
                ($previousRecurring / $previousUnique) * 100,
                2
            )
            : 0;

        return [
            'accesses' => [
                'value' => $accesses,
                'variation' => $this->variation(
                    $accesses,
                    $previousAccesses
                ),
            ],

            'unique_visitors' => [
                'value' => $uniqueVisitors,
                'variation' => $this->variation(
                    $uniqueVisitors,
                    $previousUnique
                ),
            ],

            'recurring_visitors' => [
                'value' => $recurringVisitors,
                'variation' => $this->variation(
                    $recurringVisitors,
                    $previousRecurring
                ),
            ],

            'avg_navigation' => [
                'value' => $avgNavigation,
                'variation' => $this->variation(
                    $avgNavigation,
                    (int) $previous->avg(
                        'avg_navigation_seconds'
                    )
                ),
            ],

            'return_rate' => [
                'value' => $returnRate,
                'variation' => round(
                    $returnRate - $previousReturnRate,
                    2
                ),
            ],
        ];
    }

    private function buildTimeline(Collection $metrics): array
    {
        return [
            'labels' => $metrics
                ->sortBy('metric_date')
                ->map(
                    fn ($metric) =>
                    $metric->metric_date->format('d/m')
                )
                ->values()
                ->all(),

            'accesses' => $metrics
                ->sortBy('metric_date')
                ->pluck('accesses_total')
                ->values()
                ->all(),

            'unique_visitors' => $metrics
                ->sortBy('metric_date')
                ->pluck('unique_visitors')
                ->values()
                ->all(),
        ];
    }

    private function aggregateJson(
        Collection $metrics,
        string $field,
        bool $sort = true
    ): array {
        $result = [];

        foreach ($metrics as $metric) {
            $data = $metric->{$field} ?? [];

            foreach ($data as $key => $value) {
                if (!isset($result[$key])) {
                    $result[$key] = 0;
                }

                $result[$key] += $value;
            }
        }

        if ($sort) {
            arsort($result);
        }

        return array_slice(
            $result,
            0,
            10,
            true
        );
    }

    private function variation(
        float|int $current,
        float|int $previous
    ): float {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round(
            (($current - $previous) / $previous) * 100,
            2
        );
    }

    private function resolvePeriod(
        string $period,
        Carbon $reference
    ): array {
        return match ($period) {
            'quarter' => [
                $reference->copy()->startOfQuarter(),
                $reference->copy()->endOfQuarter(),
            ],

            'year' => [
                $reference->copy()->startOfYear(),
                $reference->copy()->endOfYear(),
            ],

            default => [
                $reference->copy()->startOfMonth(),
                $reference->copy()->endOfMonth(),
            ],
        };
    }

    private function resolvePreviousPeriod(
        string $period,
        Carbon $start,
        Carbon $end
    ): array {
        return match ($period) {
            'quarter' => [
                $start->copy()->subQuarter()->startOfQuarter(),
                $start->copy()->subQuarter()->endOfQuarter(),
            ],

            'year' => [
                $start->copy()->subYear()->startOfYear(),
                $start->copy()->subYear()->endOfYear(),
            ],

            default => [
                $start->copy()->subMonth()->startOfMonth(),
                $start->copy()->subMonth()->endOfMonth(),
            ],
        };
    }
}