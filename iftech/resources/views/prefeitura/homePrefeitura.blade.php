<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Prefeitura - Turismo PB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { 
            --brand: #28819e; 
            --brand-dark: #1d6178;
            --bg-main: #f8fafc;
            --card-border: #f1f5f9;
        }

        body { 
            background-color: var(--bg-main); 
            color: #334155; 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        /* Navbar moderna com a cor personalizada */
        .navbar { 
            background: linear-gradient(135deg, var(--brand), var(--brand-dark)); 
            box-shadow: 0 4px 20px rgba(40, 129, 158, 0.2);
        }

        /* Dashboard Cards */
        .summary-card {
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            background: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }
        
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }

        .summary-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
        }
        .summary-card .label {
            font-size: .85rem;
            color: #64748b;
            font-weight: 500;
        }
        .mini-chart { height: 160px; }

        .occ-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: .875rem;
        }
        .occ-item:last-child { border-bottom: none; }

        /* Tabela Aprimorada */
        .custom-card {
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            background: #ffffff;
            overflow: hidden;
        }

        .table > :not(caption) > * > * {
            padding: 1rem 1.2rem;
        }

        .table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Botões Estilizados */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            font-size: 0.8125rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-action-info {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .btn-action-info:hover {
            background-color: #dcfce7;
            color: #14532d;
        }

        .btn-action-success {
            background-color: #059669;
            color: #ffffff;
        }
        .btn-action-success:hover {
            background-color: #047857;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);
        }

        .btn-action-danger {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .btn-action-danger:hover {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Modal Customizada */
        .info-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
        }
        .info-box label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }
        .info-box p {
            margin: 0;
            color: #1e293b;
            font-weight: 500;
            font-size: 0.9375rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 py-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('prefeitura.home') }}">
                 Administração
            </a>
            <div class="d-flex align-items-center gap-3">
                <form method="POST" action="{{ route('logout-prefeitura') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm rounded-3 px-3">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">

        <!-- SEÇÃO 1: Dashboard Executivo -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold text-slate-800">Visão Geral</h5>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: var(--brand);">
                    Ver dashboard executivo completo →
                </a>
            </div>

            <!-- Card 1: KPIs resumidos -->
            <div class="col-md-4 mb-3">
                <div class="card summary-card p-4">
                    <h6 class="text-muted fw-bold mb-3 small text-uppercase">Indicadores do mês</h6>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="label">Empreendedores aprovados</span>
                        <span class="value text-success">{{ number_format($data['empreendedores']['aprovados']['value'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="label">Turistas registrados</span>
                        <span class="value" style="color: var(--brand);">{{ number_format($data['usuarios']['turistas']['value'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="label">Empreendedores pendentes</span>
                        <span class="value text-warning">{{ number_format($data['empreendedores']['pendentes']['value'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Evolução de acessos -->
            <div class="col-md-4 mb-3">
                <div class="card summary-card p-4">
                    <h6 class="text-muted fw-bold mb-3 small text-uppercase">Evolução de acessos</h6>
                    <div class="mini-chart">
                        <canvas id="miniAccessChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Card 3: Ocorrências / alertas turísticos -->
            <div class="col-md-4 mb-3">
                <div class="card summary-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted fw-bold mb-0 small text-uppercase">Ocorrências em aberto</h6>
                        <a href="{{ route('admin.occurrences.index') }}" class="small text-decoration-none fw-semibold" style="color: var(--brand);">Ver todas</a>
                    </div>

                    @forelse($ocorrenciasAbertas as $ocorrencia)
                        <div class="occ-item">
                            <span class="fw-medium">{{ $ocorrencia->title }}</span>
                            <span class="badge rounded-pill bg-{{ $ocorrencia->severity === 'critica' ? 'danger' : ($ocorrencia->severity === 'alta' ? 'warning text-dark' : 'secondary') }}">
                                {{ ucfirst($ocorrencia->severity) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted small mb-0 py-2">Nenhuma ocorrência em aberto no momento. 🎉</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- SEÇÃO 2: Tabela de Solicitações -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="custom-card">
                    <div class="d-flex justify-content-between align-items-center p-3 px-4 bg-white border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0 fw-bold">Aguardando Autorização</h6>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $pendentesCount }}</span>
                        </div>
                        <button class="btn btn-sm btn-light border rounded-2 d-flex align-items-center gap-2 text-secondary" onclick="carregarPendentes()">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Atualizar
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead>
                                <tr>
                                    <th class="text-start">ID</th>
                                    <th class="text-start">Nome Fantasia</th>
                                    <th>CNPJ/CPF</th>
                                    <th>Informações</th>
                                    <th>Status</th>
                                    <th class="text-end px-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tabela-pendentes">
                                <tr>
                                    <td colspan="6" class="text-muted py-4">Carregando dados...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal de informações do empreendedor -->
    <div class="modal fade" id="modalInformacoes" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-slate-800 ms-2 mt-2">
                        📋 Informações do Empreendimento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body p-4" id="conteudoInformacoes">
                    <!-- Conteúdo inserido dinamicamente via JS -->
                </div>

                <div class="modal-footer border-top-0 pt-0 pe-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 px-4 fw-medium" data-bs-dismiss="modal">
                        Fechar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('token') || localStorage.getItem('auth_token');

        if (!token) {
            window.location.href = '/login-prefeitura';
        }

        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('auth_token');
            window.location.href = '/login-prefeitura';
        }

        // Mini gráfico com Chart.js usando a nova cor
        const timeline = @json($data['timeline']);

        new Chart(document.getElementById('miniAccessChart'), {
            type: 'line',
            data: {
                labels: timeline.labels,
                datasets: [
                    {
                        label: 'Acessos',
                        data: timeline.accesses,
                        borderColor: '#28819e',
                        borderWidth: 2.5,
                        tension: 0.35,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    x: { display: false },
                    y: { display: false } 
                }
            }
        });

        document.addEventListener('DOMContentLoaded', carregarPendentes);

        async function carregarPendentes() {
            const tbody = document.getElementById('tabela-pendentes');
            tbody.innerHTML = '<tr><td colspan="6" class="text-muted py-4">Carregando solicitações...</td></tr>';

            try {
                const token = localStorage.getItem('token') || localStorage.getItem('auth_token');

                const resposta = await fetch('/api/prefeitura/empreendedores/pendentes', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const empreendedores = await resposta.json();

                if (!resposta.ok) {
                    if (resposta.status === 401 || resposta.status === 403) {
                        logout();
                        return;
                    }
                    throw new Error(empreendedores.message || 'Não foi possível carregar as solicitações.');
                }

                tbody.innerHTML = '';

                if (empreendedores.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-muted py-4">Nenhum pedido pendente no momento. 🎉</td></tr>';
                    return;
                }

                empreendedores.forEach(emp => {
                    tbody.innerHTML += `
                        <tr>
                            <td class="text-start fw-bold text-secondary">#${emp.id}</td>
                            <td class="text-start">
                                <div class="fw-bold text-dark">${emp.nome_fantasia}</div>
                            </td>
                            <td class="text-muted small">${emp.cpf_cnpj}</td>
                            <td>
                                <button type="button" class="btn-action btn-action-info" onclick='mostrarInformacoes(${JSON.stringify(emp)})'>
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Ver detalhes
                                </button>
                            </td>
                            <td><span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">Pendente</span></td>
                            <td class="text-end px-4">
                                <button onclick="aprovar(${emp.id})" class="btn-action btn-action-success me-1">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Aprovar
                                </button>
                                <button onclick="rejeitar(${emp.id})" class="btn-action btn-action-danger">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Rejeitar
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } catch (erro) {
                console.error('Erro ao buscar dados:', erro);
                tbody.innerHTML = '<tr><td colspan="6" class="text-danger py-4">Erro ao conectar com o servidor.</td></tr>';
            }
        }

        async function aprovar(id) {
            if (!confirm('Tem certeza que deseja APROVAR este cadastro?')) return;

            try {
                const token = localStorage.getItem('token') || localStorage.getItem('auth_token');

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
                const token = localStorage.getItem('token') || localStorage.getItem('auth_token');

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

        function mostrarInformacoes(emp) {
            const conteudo = document.getElementById('conteudoInformacoes');

            conteudo.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Nome Fantasia</label>
                            <p>${emp.nome_fantasia || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Razão Social</label>
                            <p>${emp.razao_social || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>CPF / CNPJ</label>
                            <p>${emp.cpf_cnpj || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>E-mail</label>
                            <p>${emp.email || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Telefone</label>
                            <p>${emp.telefone || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>WhatsApp</label>
                            <p>${emp.whatsapp || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-box">
                            <label>Endereço Completo</label>
                            <p>
                                ${emp.endereco || 'Não informado'}
                                ${emp.bairro ? ' - ' + emp.bairro : ''}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <label>Cidade</label>
                            <p>${emp.cidade || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <label>Estado</label>
                            <p>${emp.estado || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <label>CEP</label>
                            <p>${emp.cep || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-box">
                            <label>Descrição do Negócio</label>
                            <p>${emp.descricao || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Horário de funcionamento</label>
                            <p>${emp.horario_funcionamento || 'Não informado'}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <label>Acessibilidade</label>
                            <p class="${emp.acessivel ? 'text-success' : 'text-muted'}">
                                ${emp.acessivel ? '✅ Local possui acessibilidade' : '❌ Não informado'}
                            </p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-box">
                            <label>Recursos de Acessibilidade</label>
                            <p>${emp.recursos_acessibilidade || 'Nenhum informado'}</p>
                        </div>
                    </div>
                </div>
            `;

            const modal = new bootstrap.Modal(document.getElementById('modalInformacoes'));
            modal.show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>