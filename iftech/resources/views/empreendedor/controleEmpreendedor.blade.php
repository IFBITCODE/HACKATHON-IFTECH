<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Empreendedor - Turismo PB</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #ff6f00; } /* Laranja vibrante para o Empreendedor */
        .card-metric { border-left: 4px solid #ff6f00; }
        .moedas-text { color: #f39c12; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Navegação Superior -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shop"></i> Portal do Empreendedor - Turismo PB</a>
            <div class="d-flex text-white align-items-center">
                <i class="bi bi-person-circle fs-4 me-2"></i>
                <span>{{ $empreendedor->nome_fantasia }}</span>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <!-- PAINEL DE MÉTRICAS -->
        <div class="row mb-4">
            <!-- (Mantenha os cards de métricas aqui para eles verem o painel bonito) -->
            ... 
        </div>

        <!-- AQUI COMEÇA O BLOQUEIO VISUAL -->
        @if($empreendedor->status == 'aprovado')
            
            <!-- SÓ MOSTRA SE ESTIVER APROVADO -->
            <div class="row">
                <!-- COLUNA ESQUERDA: PERFIL DO ESTABELECIMENTO -->
                ... 
                <!-- COLUNA DIREITA: GESTÃO DE CUPONS E MOEDAS -->
                ... 
            </div>

        @else
            
            <!-- TELA DE BLOQUEIO PARA QUEM ESTÁ PENDENTE/REJEITADO -->
            <div class="row mt-5">
                <div class="col-12 text-center p-5 bg-white rounded shadow-sm">
                    <i class="bi bi-lock-fill text-warning" style="font-size: 5rem;"></i>
                    <h3 class="mt-4 text-dark">Funcionalidades Bloqueadas</h3>
                    <p class="text-muted fs-5">
                        Assim que a Prefeitura validar e aprovar seus dados, você poderá editar seu perfil, preencher informações de acessibilidade e criar cupons de ofertas para os turistas.
                    </p>
                </div>
            </div>

        @endif

        <!-- PAINEL DE MÉTRICAS -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm card-metric mb-3">
                    <div class="card-body">
                        <h6 class="text-muted">Turistas Recebidos pelo App</h6>
                        <h2 class="mb-0">124 <small class="text-success fs-6"><i class="bi bi-arrow-up"></i></small></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm card-metric mb-3" style="border-left-color: #f39c12;">
                    <div class="card-body">
                        <h6 class="text-muted">Moedas de Troca (Circulando)</h6>
                        <h2 class="mb-0 moedas-text">1.500 <i class="bi bi-coin"></i></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- COLUNA ESQUERDA: PERFIL DO ESTABELECIMENTO -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Dados do Estabelecimento</h5>
                        <button class="btn btn-outline-primary btn-sm"><i class="bi bi-save"></i> Salvar</button>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" value="{{ $empreendedor->nome_fantasia }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Horário de Funcionamento</label>
                                    <input type="text" class="form-control" placeholder="Ex: Seg a Sex, 08h às 18h">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">WhatsApp para Reservas</label>
                                    <input type="text" class="form-control" placeholder="(83) 90000-0000">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descrição (Como a IA vai te vender para o turista)</label>
                                <textarea class="form-control" rows="3" placeholder="Descreva seus diferenciais..."></textarea>
                            </div>
                            <hr>
                            <h6 class="mb-3">Recursos de Acessibilidade</h6>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="cadeirante" checked>
                                <label class="form-check-label" for="cadeirante">Rampa / Elevador</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="braille">
                                <label class="form-check-label" for="braille">Cardápio em Braille</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- COLUNA DIREITA: GESTÃO DE CUPONS E MOEDAS (A SUA IDEIA) -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm h-100 border-warning">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="mb-0"><i class="bi bi-ticket-perforated"></i> Ofertas e Cupons</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Crie recompensas para os turistas resgatarem usando as <strong>Moedas de Troca</strong> que ganham no Chatbot.</p>
                        
                        <!-- Formulário de Criação de Cupom -->
                        <form class="mb-4 p-3 bg-light rounded border">
                            <h6 class="mb-3">Criar Nova Oferta</h6>
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Nome do Cupom</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Ex: Sobremesa Grátis, 10% de Desconto...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Valor em Moedas (Custo para o turista)</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-warning text-dark"><i class="bi bi-coin"></i></span>
                                    <input type="number" class="form-control" placeholder="Ex: 50">
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning btn-sm w-100 fw-bold">Criar Cupom</button>
                        </form>

                        <!-- Lista de Cupons Ativos -->
                        <h6 class="mb-3">Seus Cupons Ativos</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>10% OFF na Diária</strong><br>
                                    <small class="text-muted">Resgatado 12 vezes</small>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill fs-6">100 <i class="bi bi-coin"></i></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Café da Manhã Extra</strong><br>
                                    <small class="text-muted">Resgatado 5 vezes</small>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill fs-6">50 <i class="bi bi-coin"></i></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>