<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Prefeitura - Turismo PB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --brand: #0d6efd; }
        body { background-color: #f4f6f9; }
        .navbar { background-color: var(--brand); }
        .card-header { background-color: #fff; font-weight: bold; }

        .summary-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            height: 100%;
        }
        .summary-card .value {
            font-size: 1.6rem;
            font-weight: 700;
        }
        .summary-card .label {
            font-size: .8rem;
            color: #6c757d;
        }
        .mini-chart { height: 180px; }
        .occ-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: .9rem;
        }
        .occ-item:last-child { border-bottom: none; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('prefeitura.home') }}">🏛️ Gestão de Turismo - Prefeitura</a>
            <span class="navbar-text text-light">
                Bem-vindo, Administrador
            </span>
        </div>
    </nav>

    <div class="container-fluid px-4">

        <!-- SEÇÃO 1: Dashboard Executivo (agora com dados reais) -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Visão Geral (Dashboards)</h4>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">
                    Ver dashboard executivo completo →
                </a>
            </div>

            <!-- Card 1: KPIs resumidos -->
            <div class="col-md-4 mb-3">
                <div class="card summary-card p-3">
                    <h6 class="text-muted mb-3">Indicadores do mês</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="label">Acessos totais</span>
                        <span class="value">{{ number_format($data['kpis']['accesses']['value'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="label">Visitantes únicos</span>
                        <span class="value">{{ number_format($data['kpis']['unique_visitors']['value'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="label">Taxa de retorno</span>
                        <span class="value">{{ $data['kpis']['return_rate']['value'] }}%</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Evolução de acessos (gráfico real) -->
            <div class="col-md-4 mb-3">
                <div class="card summary-card p-3">
                    <h6 class="text-muted mb-3">Evolução de acessos</h6>
                    <div class="mini-chart">
                        <canvas id="miniAccessChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Card 3: Ocorrências / alertas turísticos -->
            <div class="col-md-4 mb-3">
                <div class="card summary-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0">Ocorrências em aberto</h6>
                        <a href="{{ route('admin.occurrences.index') }}" class="small">Ver todas</a>
                    </div>

                    @forelse($ocorrenciasAbertas as $ocorrencia)
                        <div class="occ-item">
                            <span>{{ $ocorrencia->title }}</span>
                            <span class="badge bg-{{ $ocorrencia->severity === 'critica' ? 'danger' : ($ocorrencia->severity === 'alta' ? 'warning text-dark' : 'secondary') }}">
                                {{ ucfirst($ocorrencia->severity) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Nenhuma ocorrência em aberto no momento. 🎉</p>
                    @endforelse
                </div>
            </div>
        </div>

        <hr class="mb-4">

        <!-- SEÇÃO 2: Tabela de Solicitações (escopo original do teu colega) -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">
                            Empreendedores Aguardando Autorização
                            <span class="badge bg-warning text-dark">{{ $pendentesCount }}</span>
                        </h5>
                        <button class="btn btn-sm btn-outline-secondary" onclick="carregarPendentes()">🔄 Atualizar Lista</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome Fantasia</th>
                                        <th>CNPJ/CPF</th>
                                        <th>Cidade/Localidade</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="tabela-pendentes">
                                    <tr>
                                        <td colspan="6">Carregando dados...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>

        // Assim que a tela abrir, ele chama a função para buscar os dados
        const token = localStorage.getItem('token');

        if (!token) {
            window.location.href = '/login-prefeitura';
        }
        
        // Gráfico mini com os mesmos dados usados no dashboard completo
        const timeline = @json($data['timeline']);

        new Chart(document.getElementById('miniAccessChart'), {
            type: 'line',
            data: {
                labels: timeline.labels,
                datasets: [
                    {
                        label: 'Acessos',
                        data: timeline.accesses,
                        borderColor: '#0d6efd',
                        tension: 0.3,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { display: false } }
            }
        });

        document.addEventListener('DOMContentLoaded', carregarPendentes);

        async function carregarPendentes() {
            const tbody = document.getElementById('tabela-pendentes');
            tbody.innerHTML = '<tr><td colspan="6">Carregando dados...</td></tr>';

            try {
                // Chama a sua rota GET do PrefeituraController
                const token = localStorage.getItem('token');

                const resposta = await fetch('/api/prefeitura/empreendedores/pendentes', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const empreendedores = await resposta.json();

                tbody.innerHTML = '';

                if (empreendedores.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-muted">Nenhum pedido pendente no momento. 🎉</td></tr>';
                    return;
                }

                empreendedores.forEach(emp => {
                    tbody.innerHTML += `
                        <tr>
                            <td>#${emp.id}</td>
                            <td><strong>${emp.nome_fantasia}</strong></td>
                            <td>${emp.cpf_cnpj}</td>
                            <td>${emp.cidade || 'Não informada'}</td>
                            <td><span class="badge bg-warning text-dark">Pendente</span></td>
                            <td>
                                <button onclick="aprovar(${emp.id})" class="btn btn-success btn-sm me-1">✔️ Aprovar</button>
                                <button onclick="rejeitar(${emp.id})" class="btn btn-danger btn-sm">❌ Rejeitar</button>
                            </td>
                        </tr>
                    `;
                });
            } catch (erro) {
                console.error('Erro ao buscar dados:', erro);
                tbody.innerHTML = '<tr><td colspan="6" class="text-danger">Erro ao conectar com o servidor.</td></tr>';
            }
        }

        async function aprovar(id) {
            if (!confirm('Tem certeza que deseja APROVAR este cadastro?')) return;

            try {
                // Dispara o PATCH para a sua rota de aprovação
                const token = localStorage.getItem('token');

                const resposta = await fetch(`/api/prefeitura/empreendedores/${id}/aprovar`, {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const resultado = await resposta.json();
                alert(resultado.message);

                carregarPendentes();
            } catch (erro) {
                alert('Ocorreu um erro ao tentar aprovar.');
            }
        }

        async function rejeitar(id) {
            const motivo = prompt("Por favor, digite o motivo da rejeição:");

            if (motivo === null || motivo.trim() === "") {
                alert('A rejeição foi cancelada. É obrigatório informar um motivo.');
                return;
            }

            try {
                // Dispara o PATCH enviando o motivo no 'body'
                const token = localStorage.getItem('token');

                const resposta = await fetch(`/api/prefeitura/empreendedores/${id}/rejeitar`, {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        motivo_rejeicao: motivo
                    })
                });

                const resultado = await resposta.json();
                alert(resultado.message);

                carregarPendentes();
            } catch (erro) {
                alert('Ocorreu um erro ao tentar rejeitar.');
            }
        }
    </script>
</body>
</html>