<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Prefeitura - Turismo PB</title>
    <!-- Bootstrap CDN para estilização rápida e profissional -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .navbar { background-color: #0d6efd; }
        .card-header { background-color: #fff; font-weight: bold; }
        /* Estilo para a área reservada aos dashboards da equipe */
        .dashboard-placeholder {
            border: 2px dashed #adb5bd;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            color: #6c757d;
            background-color: #e9ecef;
            height: 100%;
        }
    </style>
</head>

<body>
    <!-- Barra de Navegação Superior -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">🏛️ Gestão de Turismo - Prefeitura</a>
            <span class="navbar-text text-light">
                Bem-vindo, Administrador
            </span>
        </div>
    </nav>

    <div class="container-fluid px-4">
        
        <!-- SEÇÃO 1: Espaço para os Dashboards (Feitos por outras pessoas) -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3">Visão Geral (Dashboards)</h4>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="dashboard-placeholder">
                    <h5>Gráfico de Turistas</h5>
                    <p>Espaço reservado para o componente da equipe.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="dashboard-placeholder">
                    <h5>Mapa de Calor</h5>
                    <p>Espaço reservado para o componente da equipe.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="dashboard-placeholder">
                    <h5>Receita / Impacto</h5>
                    <p>Espaço reservado para o componente da equipe.</p>
                </div>
            </div>
        </div>

        <hr class="mb-4">

        <!-- SEÇÃO 2: Tabela de Solicitações (Seu escopo) -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">Empreendedores Aguardando Autorização</h5>
                        <button class="btn btn-sm btn-outline-secondary">🔄 Atualizar Lista</button>
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
                                    <!-- Exemplo visual de como o dado vai aparecer -->
                                    <tr>
                                        <td>#001</td>
                                        <td><strong>Pousada Paraíba Tur</strong></td>
                                        <td>11.222.333/0001-99</td>
                                        <td>Campina Grande - PB</td>
                                        <td><span class="badge bg-warning text-dark">Pendente</span></td>
                                        <td>
                                            <button class="btn btn-success btn-sm me-1">✔️ Aprovar</button>
                                            <button class="btn btn-danger btn-sm">❌ Rejeitar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Lógica de Conexão com a API (Back-end) -->
    <script>
        // Assim que a tela abrir, ele chama a função para buscar os dados
        const token = localStorage.getItem('token');

        if (!token) {
            window.location.href = '/login-prefeitura';
        }
        
        document.addEventListener('DOMContentLoaded', carregarPendentes);

        // 1. Função para buscar no Supabase/Laravel e montar a tabela
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
                
                tbody.innerHTML = ''; // Limpa o "Carregando..."

                if (empreendedores.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-muted">Nenhum pedido pendente no momento. 🎉</td></tr>';
                    return;
                }

                // Percorre cada empreendedor e desenha a linha da tabela
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

        // 2. Função disparada ao clicar no botão Aprovar
        async function aprovar(id) {
            if(!confirm('Tem certeza que deseja APROVAR este cadastro?')) return;

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
                alert(resultado.message); // Mostra a mensagem de sucesso
                
                carregarPendentes(); // Recarrega a tabela automaticamente para sumir com o aprovado
            } catch (erro) {
                alert('Ocorreu um erro ao tentar aprovar.');
            }
        }

        // 3. Função disparada ao clicar no botão Rejeitar
        async function rejeitar(id) {
            // Pede para a prefeitura digitar o motivo da rejeição
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
                
                carregarPendentes(); // Atualiza a tabela
            } catch (erro) {
                alert('Ocorreu um erro ao tentar rejeitar.');
            }
        }
    </script>
</body>
</html>