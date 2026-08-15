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

        .primary {
            background: #0d6efd;
            color: white;
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
            grid-template-columns: repeat(6, 1fr);
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

        <a href="{{ route('prefeitura.home') }}" class="primary" style="padding: 10px 15px; color: white; text-decoration: none; border-radius: 7px; height: 40px; display: flex; align-items: center;">
            ← Voltar à Prefeitura
        </a>
    </div>

    {{-- FILTRO --}}

    <div class="filter">
        @include(
            'admin.dashboard.partials._period-filter'
        )
    </div>

    {{-- KPIs --}}

    <section class="kpis">

    @include('admin.dashboard.partials._kpi-card', [
        'title' => 'Acessos totais',
        'value' => number_format($data['kpis']['accesses']['value'], 0, ',', '.'),
        'variation' => $data['kpis']['accesses']['variation'],
        'icon' => '👁️'
    ])

    @include('admin.dashboard.partials._kpi-card', [
        'title' => 'Empreendedores aprovados',
        'value' => $data['empreendedores']['aprovados']['value'],
        'variation' => $data['empreendedores']['aprovados']['variation'],
        'icon' => '✅'
    ])

    @include('admin.dashboard.partials._kpi-card', [
        'title' => 'Empreendedores pendentes',
        'value' => $data['empreendedores']['pendentes']['value'],
        'variation' => $data['empreendedores']['pendentes']['variation'],
        'icon' => '⏳'
    ])

    @include('admin.dashboard.partials._kpi-card', [
        'title' => 'Empreendedores rejeitados',
        'value' => $data['empreendedores']['rejeitados']['value'],
        'variation' => $data['empreendedores']['rejeitados']['variation'],
        'icon' => '❌'
    ])

    @include('admin.dashboard.partials._kpi-card', [
        'title' => 'Turistas registrados',
        'value' => number_format($data['usuarios']['turistas']['value'], 0, ',', '.'),
        'variation' => $data['usuarios']['turistas']['variation'],
        'icon' => '🧭'
    ])

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

    {{-- ACESSIBILIDADE --}}

    <div class="card">

        <h2>
            Empreendimentos que possuem acessibilidade
        </h2>

        <div class="chart-container" style="max-width: 360px; margin: 0 auto;">
            <canvas id="accessibilityChart"></canvas>
        </div>

    </div>

    {{-- PERFIL --}}

    {{-- CONTEÚDOS --}}

    <div class="content-grid">

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

    </div>

</div>

<script>

const timeline = @json($data['timeline']);

new Chart(document.getElementById('accessChart'), {
    type: 'line',
    data: {
        labels: timeline.labels,
        datasets: [
            { label: 'Acessos', data: timeline.accesses, tension: 0.3 },
            { label: 'Visitantes únicos', data: timeline.unique_visitors, tension: 0.3 }
        ]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

function createDoughnut(element, data) {
    new Chart(document.getElementById(element), {
        type: 'doughnut',
        data: { labels: Object.keys(data), datasets: [{ data: Object.values(data) }] },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

const accessibilityData = @json($data['accessibility']);

new Chart(document.getElementById('accessibilityChart'), {
    type: 'pie',
    data: {
        labels: Object.keys(accessibilityData),
        datasets: [{
            data: Object.values(accessibilityData),
            backgroundColor: ['#0d6efd', '#dc2626'],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

new Chart(
    document.getElementById('accessChart'),
    {
        type: 'line',
        data: {
            labels: timeline.labels,
            datasets: [
                { label: 'Acessos', data: timeline.accesses, tension: 0.3 },
                { label: 'Visitantes únicos', data: timeline.unique_visitors, tension: 0.3 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    }
);

// --- Mapa de calor por bairro ---
const heatmapData = @json($data['location_heatmap']);
const heatmapContainer = document.getElementById('heatmapList');
const emptyMessage = document.getElementById('heatmapEmpty');

const entries = Object.entries(heatmapData);

if (entries.length === 0) {
    emptyMessage.style.display = 'block';
} else {
    const maxValue = Math.max(...entries.map(([, total]) => total));

    entries.forEach(([bairro, total]) => {
        const intensity = total / maxValue;
        const color = `rgba(220, 38, 38, ${0.15 + intensity * 0.75})`;

        const row = document.createElement('div');
        row.style.display = 'flex';
        row.style.alignItems = 'center';
        row.style.gap = '12px';
        row.style.padding = '8px 0';

        row.innerHTML = `
            <div style="flex:1; font-size:14px; color:#374151;">${bairro}</div>
            <div style="flex:3; background:#f3f4f6; border-radius:6px; overflow:hidden; height:20px;">
                <div style="width:${intensity * 100}%; height:100%; background:${color};"></div>
            </div>
            <strong style="width:32px; text-align:right; font-size:14px;">${total}</strong>
        `;

        heatmapContainer.appendChild(row);
    });
}

</script>

</body>
</html>