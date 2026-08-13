<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Executivo</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #1f2937;
        }

        .dashboard {
            max-width: 1400px;
            margin: auto;
            padding: 32px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header p {
            margin-top: 8px;
            color: #6b7280;
        }

        .filter {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .period-filter {
            display: flex;
            align-items: end;
            gap: 16px;
        }

        .period-filter div {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
        }

        select,
        input,
        button {
            height: 40px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            padding: 0 12px;
        }

        button {
            background: #111827;
            color: white;
            cursor: pointer;
        }

        .kpis {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .kpi-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
        }

        .kpi-header {
            display: flex;
            justify-content: space-between;
            color: #6b7280;
        }

        .kpi-title {
            font-size: 14px;
        }

        .kpi-value {
            font-size: 30px;
            font-weight: bold;
            margin-top: 14px;
        }

        .kpi-variation {
            font-size: 13px;
            margin-top: 10px;
        }

        .positive {
            color: #15803d;
        }

        .negative {
            color: #dc2626;
        }

        .kpi-variation span {
            color: #6b7280;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .card h2 {
            margin-top: 0;
            font-size: 18px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .ranking-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .ranking-item:last-child {
            border-bottom: none;
        }

        @media(max-width: 1000px) {
            .kpis {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-grid,
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 600px) {
            .dashboard {
                padding: 16px;
            }

            .kpis {
                grid-template-columns: 1fr;
            }

            .period-filter {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>

<div class="dashboard">

    <div class="header">
        <div>
            <h1>Dashboard Executivo</h1>

            <p>
                Indicadores agregados e anonimizados
                para apoio à gestão turística.
            </p>
        </div>
    </div>

    {{-- FILTRO --}}

    <div class="filter">
        @include(
            'admin.dashboard.partials._period-filter'
        )
    </div>

    {{-- KPIs --}}

    <section class="kpis">

        @include(
            'admin.dashboard.partials._kpi-card',
            [
                'title' => 'Acessos totais',
                'value' => number_format(
                    $data['kpis']['accesses']['value'],
                    0,
                    ',',
                    '.'
                ),
                'variation' =>
                    $data['kpis']['accesses']['variation'],
                'icon' => '👁️'
            ]
        )

        @include(
            'admin.dashboard.partials._kpi-card',
            [
                'title' => 'Visitantes únicos',
                'value' => number_format(
                    $data['kpis']['unique_visitors']['value'],
                    0,
                    ',',
                    '.'
                ),
                'variation' =>
                    $data['kpis']['unique_visitors']['variation'],
                'icon' => '👥'
            ]
        )

        @include(
            'admin.dashboard.partials._kpi-card',
            [
                'title' => 'Visitantes recorrentes',
                'value' => number_format(
                    $data['kpis']['recurring_visitors']['value'],
                    0,
                    ',',
                    '.'
                ),
                'variation' =>
                    $data['kpis']['recurring_visitors']['variation'],
                'icon' => '🔄'
            ]
        )

        @include(
            'admin.dashboard.partials._kpi-card',
            [
                'title' => 'Tempo médio',
                'value' =>
                    gmdate(
                        'i\m\ s\s',
                        $data['kpis']['avg_navigation']['value']
                    ),
                'variation' =>
                    $data['kpis']['avg_navigation']['variation'],
                'icon' => '⏱️'
            ]
        )

        @include(
            'admin.dashboard.partials._kpi-card',
            [
                'title' => 'Taxa de retorno',
                'value' =>
                    $data['kpis']['return_rate']['value'] . '%',
                'variation' =>
                    $data['kpis']['return_rate']['variation'],
                'icon' => '↩️'
            ]
        )

    </section>

    {{-- EVOLUÇÃO --}}

    <div class="card">

        <h2>
            Evolução de acessos
        </h2>

        <div class="chart-container">
            <canvas id="accessChart"></canvas>
        </div>

    </div>

    {{-- PERFIL --}}

    <div class="charts-grid">

        <div class="card">

            <h2>
                Origem geográfica
            </h2>

            <div class="chart-container">
                <canvas id="geoChart"></canvas>
            </div>

        </div>

        <div class="card">

            <h2>
                Dispositivos
            </h2>

            <div class="chart-container">
                <canvas id="deviceChart"></canvas>
            </div>

        </div>

        <div class="card">

            <h2>
                Idiomas
            </h2>

            <div class="chart-container">
                <canvas id="languageChart"></canvas>
            </div>

        </div>

        <div class="card">

            <h2>
                Canais de origem
            </h2>

            <div class="chart-container">
                <canvas id="channelChart"></canvas>
            </div>

        </div>

    </div>

    {{-- CONTEÚDOS --}}

    <div class="content-grid">

        <div class="card">

            <h2>
                Atrativos mais acessados
            </h2>

            @foreach(
                $data['top_content']['attractions']
                as $name => $value
            )

                <div class="ranking-item">
                    <span>{{ $name }}</span>

                    <strong>
                        {{ number_format(
                            $value,
                            0,
                            ',',
                            '.'
                        ) }}
                    </strong>
                </div>

            @endforeach

        </div>

        <div class="card">

            <h2>
                Roteiros mais acessados
            </h2>

            @foreach(
                $data['top_content']['routes']
                as $name => $value
            )

                <div class="ranking-item">
                    <span>{{ $name }}</span>

                    <strong>
                        {{ number_format(
                            $value,
                            0,
                            ',',
                            '.'
                        ) }}
                    </strong>
                </div>

            @endforeach

        </div>

        <div class="card">

            <h2>
                Eventos mais acessados
            </h2>

            @foreach(
                $data['top_content']['events']
                as $name => $value
            )

                <div class="ranking-item">
                    <span>{{ $name }}</span>

                    <strong>
                        {{ number_format(
                            $value,
                            0,
                            ',',
                            '.'
                        ) }}
                    </strong>
                </div>

            @endforeach

        </div>

        <div class="card">

            <h2>
                Páginas mais acessadas
            </h2>

            @foreach(
                $data['top_content']['pages']
                as $name => $value
            )

                <div class="ranking-item">
                    <span>{{ $name }}</span>

                    <strong>
                        {{ number_format(
                            $value,
                            0,
                            ',',
                            '.'
                        ) }}
                    </strong>
                </div>

            @endforeach

        </div>

    </div>

</div>

<script>

const timeline = @json($data['timeline']);

new Chart(
    document.getElementById('accessChart'),
    {
        type: 'line',

        data: {
            labels: timeline.labels,

            datasets: [
                {
                    label: 'Acessos',
                    data: timeline.accesses,
                    tension: 0.3
                },
                {
                    label: 'Visitantes únicos',
                    data: timeline.unique_visitors,
                    tension: 0.3
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    }
);

function createDoughnut(
    element,
    data
) {
    new Chart(
        document.getElementById(element),
        {
            type: 'doughnut',

            data: {
                labels: Object.keys(data),

                datasets: [
                    {
                        data: Object.values(data)
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    );
}

createDoughnut(
    'geoChart',
    @json($data['profile']['geo'])
);

createDoughnut(
    'deviceChart',
    @json($data['profile']['devices'])
);

createDoughnut(
    'languageChart',
    @json($data['profile']['languages'])
);

createDoughnut(
    'channelChart',
    @json($data['profile']['channels'])
);

</script>

</body>
</html>