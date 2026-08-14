<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Painel do Empreendedor - Turismo PB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #ff6f00; }
        .card-metric { border-left: 4px solid #ff6f00; }
        .codigo-box {
            background: #fff7ed;
            border: 2px dashed #ff6f00;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        .codigo-principal {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 3px;
            color: #e65100;
            word-break: break-all;
        }
        .codigo-item {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 10px;
            background: #fff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-shop"></i> Portal do Empreendedor - Turismo PB
        </a>
        <div class="d-flex text-white align-items-center">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            <span class="me-3">{{ $empreendedor->nome_fantasia }}</span>
            <button id="btnLogout" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Sair
            </button>
        </div>
    </div>
</nav>

<div class="container">

    @if($empreendedor->status === 'aprovado')

        <div class="alert alert-success d-flex align-items-center mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>
                <strong>Cadastro aprovado!</strong>
                Você já pode gerar códigos de troca para os usuários.
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm card-metric mb-3">
                    <div class="card-body">
                        <h6 class="text-muted">Turistas Recebidos pelo App</h6>
                        <h2 class="mb-0">124</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm card-metric mb-3">
                    <div class="card-body">
                        <h6 class="text-muted">Códigos Gerados</h6>
                        <h2 class="mb-0" id="totalCodigos">{{ $codigos->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-building"></i> Dados do Estabelecimento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nome Fantasia</label>
                            <input type="text" class="form-control" value="{{ $empreendedor->nome_fantasia }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Endereço</label>
                            <input type="text" class="form-control" value="{{ $empreendedor->endereco ?? 'Não informado' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" rows="4" readonly>{{ $empreendedor->descricao ?? 'Não informado' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

                <!-- COLUNA DIREITA: GESTÃO DO CUPOM ÚNICO -->
                <div class="col-lg-5 mb-4">
                    <div class="card shadow-sm h-100 border-warning">
                        <div class="card-header bg-warning text-dark py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-upc-scan"></i> Configuração do Cupom
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                Defina o valor da recompensa do seu estabelecimento e ative/desative o cupom quando desejar.
                            </p>

                            <!-- Pega o primeiro cupom se existir -->
                            @php
                                $cupomAtual = $codigos->first(); 
                            @endphp

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Código do Cupom Físico</label>
                                <div class="codigo-box p-3">
                                    <div id="codigoGerado" class="codigo-principal">
                                        {{ $cupomAtual ? $cupomAtual->codigo : 'NENHUM-CÓDIGO' }}
                                    </div>
                                    <small class="text-muted">Este é o código que os turistas irão digitar.</small>
                                </div>
                            </div>

                            <form id="formCupomUnico">
                                <div class="mb-3">
                                    <label for="quantidadeMoedas" class="form-label fw-bold">
                                        <i class="bi bi-coin"></i> Moedas de Recompensa
                                    </label>
                                    <input type="number" id="quantidadeMoedas" class="form-control" min="1" max="10000" value="{{ $cupomAtual ? $cupomAtual->moedas : 50 }}" required>
                                </div>

                                <div class="form-check form-switch mb-4 fs-5">
                                    <input class="form-check-input" type="checkbox" role="switch" id="statusCupom" {{ ($cupomAtual && $cupomAtual->status === 'disponivel') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="statusCupom" id="labelStatusCupom">
                                        {{ ($cupomAtual && $cupomAtual->status === 'disponivel') ? 'Cupom Ativado' : 'Cupom Desativado' }}
                                    </label>
                                </div>

                                <button type="button" id="btnSalvarCupom" class="btn btn-warning w-100 fw-bold fs-5">
                                    <i class="bi bi-save"></i> Salvar Alterações
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else

        <div class="row mt-5">
            <div class="col-12 text-center p-5 bg-white rounded shadow-sm">
                <i class="bi bi-lock-fill text-warning" style="font-size: 5rem;"></i>
                <h3 class="mt-4 text-dark">Funcionalidades Bloqueadas</h3>
                <p class="text-muted fs-5">
                    Assim que a Prefeitura validar e aprovar seus dados, você poderá gerar códigos de troca para os usuários.
                </p>
                <p class="mb-0">
                    Status atual:
                    <strong>{{ ucfirst($empreendedor->status) }}</strong>
                </p>
            </div>
        </div>

    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const btnLogout = document.getElementById('btnLogout');

btnLogout?.addEventListener('click', async () => {
    try {
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            throw new Error('Não foi possível sair da conta.');
        }
    } catch (e) {
        console.error(e);
    } finally {
        localStorage.removeItem('auth_token');
        window.location.href = '/login-empreendedor';
    }
});

// Script do botão de Logout continua igual...

const btnSalvarCupom = document.getElementById('btnSalvarCupom');
const quantidadeMoedas = document.getElementById('quantidadeMoedas');
const statusCupom = document.getElementById('statusCupom');
const labelStatusCupom = document.getElementById('labelStatusCupom');
const codigoGerado = document.getElementById('codigoGerado');

// Muda o texto do label na hora que clica no interruptor
statusCupom?.addEventListener('change', function() {
    labelStatusCupom.textContent = this.checked ? 'Cupom Ativado' : 'Cupom Desativado';
});

btnSalvarCupom?.addEventListener('click', async () => {
    const textoOriginal = btnSalvarCupom.innerHTML;

    try {
        btnSalvarCupom.disabled = true;
        btnSalvarCupom.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...';

        const moedas = Number(quantidadeMoedas.value);
        const ativo = statusCupom.checked;

        if (!Number.isInteger(moedas) || moedas < 1) {
            alert('Informe uma quantidade válida de moedas.');
            return;
        }

        // Nova rota para salvar/atualizar o cupom único
        const response = await fetch('/empreendedor/cupom/salvar', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ moedas: moedas, ativo: ativo })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Erro ao salvar o cupom.');
        }

        // Atualiza o código na tela caso tenha sido gerado pela primeira vez
        codigoGerado.textContent = data.codigo;
        
        // Dá um feedback visual legal para o usuário
        btnSalvarCupom.classList.replace('btn-warning', 'btn-success');
        btnSalvarCupom.innerHTML = '<i class="bi bi-check-lg"></i> Salvo com sucesso!';
        
        setTimeout(() => {
            btnSalvarCupom.classList.replace('btn-success', 'btn-warning');
            btnSalvarCupom.innerHTML = textoOriginal;
        }, 2000);

    } catch (error) {
        alert(error.message);
        btnSalvarCupom.innerHTML = textoOriginal;
    } finally {
        btnSalvarCupom.disabled = false;
    }
});

btnCopiarCodigo?.addEventListener('click', async () => {
    try {
        await navigator.clipboard.writeText(codigoGerado.textContent);
        btnCopiarCodigo.innerHTML = '<i class="bi bi-check"></i> Copiado!';

        setTimeout(() => {
            btnCopiarCodigo.innerHTML = '<i class="bi bi-copy"></i> Copiar código';
        }, 2000);
    } catch (error) {
        alert('Não foi possível copiar o código.');
    }
});

async function carregarCodigos() {
    try {
        const response = await fetch('/empreendedor/codigos', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) return;

        const codigos = await response.json();

        if (totalCodigos) {
            totalCodigos.textContent = codigos.length;
        }

        if (!listaCodigos) return;

        listaCodigos.innerHTML = '';

        if (codigos.length === 0) {
            listaCodigos.innerHTML = '<p class="text-muted text-center">Nenhum código gerado ainda.</p>';
            return;
        }

        codigos.forEach(item => {
            const data = new Date(item.created_at).toLocaleString('pt-BR');
            const status = item.status === 'utilizado'
                ? '<span class="badge bg-secondary">Utilizado</span>'
                : '<span class="badge bg-success">Disponível</span>';

            listaCodigos.innerHTML += `
                <div class="codigo-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.codigo}</strong>
                        <br>
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-coin"></i> ${item.moedas ?? 1} ${(item.moedas ?? 1) == 1 ? 'moeda' : 'moedas'}
                        </span>
                        <br>
                        <small class="text-muted">${data}</small>
                    </div>
                    ${status}
                </div>
            `;
        });

    } catch (error) {
        console.error('Erro ao carregar códigos:', error);
    }
}
</script>

</body>
</html>
