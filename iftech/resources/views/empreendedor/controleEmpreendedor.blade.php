<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Painel do Empreendedor - Turismo PB
    </title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    >


    <style>

        /* =====================================================
           PALETA
           ===================================================== */

        :root {

            --azul-900: #0f2742;
            --azul-800: #163a5f;
            --azul-700: #1e4f7a;
            --azul-600: #25669a;
            --azul-500: #347fb5;

            --cinza-900: #1f2937;
            --cinza-700: #4b5563;
            --cinza-600: #6b7280;
            --cinza-500: #9ca3af;
            --cinza-300: #d1d5db;
            --cinza-200: #e5e7eb;
            --cinza-100: #f3f4f6;
            --cinza-50: #f8fafc;

            --branco: #ffffff;

            --verde: #198754;
            --vermelho: #dc3545;

            --sombra:
                0 4px 18px rgba(15, 39, 66, 0.08);

            --sombra-hover:
                0 8px 28px rgba(15, 39, 66, 0.13);

        }


        /* =====================================================
           GERAL
           ===================================================== */

        body {

            background: #f4f6f8 !important;

            color: var(--cinza-900);

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;

        }


        /* =====================================================
           NAVBAR
           ===================================================== */

        .navbar {

            background:
                linear-gradient(
                    135deg,
                    var(--azul-900),
                    var(--azul-800)
                ) !important;

            min-height: 68px;

            border-bottom:
                1px solid rgba(255,255,255,0.08);

            box-shadow:
                0 3px 15px
                rgba(15,39,66,0.15);

        }


        .navbar-brand {

            font-weight: 700;

            letter-spacing: .2px;

        }


        .navbar-brand i {

            margin-right: 8px;

        }


        .navbar .text-white {

            font-size: .92rem;

        }


        /* =====================================================
           CARDS
           ===================================================== */

        .card {

            border:
                1px solid var(--cinza-200) !important;

            border-radius:
                12px !important;

            box-shadow:
                var(--sombra) !important;

            overflow: hidden;

            transition:
                transform .2s ease,
                box-shadow .2s ease;

        }


        .card:hover {

            box-shadow:
                var(--sombra-hover) !important;

        }


        .card-header {

            background:
                #ffffff !important;

            border-bottom:
                1px solid var(--cinza-200) !important;

        }


        .card-header h5 {

            color:
                var(--azul-900);

            font-weight:
                700;

        }


        /* =====================================================
           CARDS DE INDICADORES
           ===================================================== */

        .card-metric {

            border-left:
                4px solid var(--azul-600) !important;

        }


        .card-metric h6 {

            color:
                var(--cinza-600) !important;

            font-size:
                .82rem;

            font-weight:
                600;

            text-transform:
                uppercase;

            letter-spacing:
                .4px;

        }


        .card-metric h2 {

            color:
                var(--azul-900);

            font-size:
                2rem;

            font-weight:
                700;

        }


        /* =====================================================
           BOTÕES
           ===================================================== */

        .btn-warning {

            background:
                var(--azul-700) !important;

            border-color:
                var(--azul-700) !important;

            color:
                #fff !important;

        }


        .btn-warning:hover {

            background:
                var(--azul-800) !important;

            border-color:
                var(--azul-800) !important;

            color:
                #fff !important;

        }


        .btn-primary {

            background:
                var(--azul-700);

            border-color:
                var(--azul-700);

        }


        .btn-primary:hover {

            background:
                var(--azul-800);

            border-color:
                var(--azul-800);

        }


        .btn-outline-dark {

            color:
                var(--azul-800) !important;

            border-color:
                var(--cinza-300) !important;

        }


        .btn-outline-dark:hover {

            background:
                var(--azul-800) !important;

            border-color:
                var(--azul-800) !important;

            color:
                #fff !important;

        }


        /* =====================================================
           FORMULÁRIOS
           ===================================================== */

        .form-label {

            color:
                var(--cinza-900);

            font-weight:
                600;

            font-size:
                .9rem;

        }


        .form-control {

            border:
                1px solid var(--cinza-300) !important;

            border-radius:
                8px !important;

            padding:
                .65rem .8rem;

            color:
                var(--cinza-900);

            background:
                #fff;

            transition:
                border-color .2s ease,
                box-shadow .2s ease;

        }


        .form-control:focus {

            border-color:
                var(--azul-500) !important;

            box-shadow:
                0 0 0 3px
                rgba(52,127,181,.12) !important;

        }


        .form-control[readonly] {

            background:
                var(--cinza-50);

            color:
                var(--cinza-700);

        }


        /* =====================================================
           ÁREA DO CÓDIGO
           ===================================================== */

        .codigo-box {

            background:
                linear-gradient(
                    135deg,
                    #f8fafc,
                    #eef4f9
                ) !important;

            border:
                1px dashed var(--azul-500) !important;

            border-radius:
                12px !important;

            padding:
                24px !important;

            text-align:
                center;

        }


        .codigo-principal {

            color:
                var(--azul-900) !important;

            font-size:
                2rem;

            font-weight:
                800;

            letter-spacing:
                3px;

            word-break:
                break-all;

        }


        #moedasGeradas {

            color:
                var(--azul-600) !important;

            font-size:
                .95rem;

        }


        /* =====================================================
           LISTA DE CÓDIGOS
           ===================================================== */

        .codigo-item {

            border:
                1px solid var(--cinza-200) !important;

            border-radius:
                10px !important;

            padding:
                14px 16px !important;

            margin-bottom:
                10px;

            background:
                #fff;

            transition:
                background .2s ease,
                border-color .2s ease,
                transform .2s ease;

        }


        .codigo-item:hover {

            background:
                var(--cinza-50);

            border-color:
                #c5d4e1 !important;

            transform:
                translateY(-1px);

        }


        .codigo-item strong {

            color:
                var(--azul-900);

            font-size:
                .95rem;

            letter-spacing:
                1px;

        }


        /* =====================================================
           BADGES
           ===================================================== */

        .badge.bg-warning {

            background:
                #e8eef4 !important;

            color:
                var(--azul-800) !important;

            border:
                1px solid #d5e0ea;

            font-weight:
                600;

        }


        .badge.bg-success {

            background:
                #e8f5ee !important;

            color:
                #187443 !important;

        }


        .badge.bg-secondary {

            background:
                var(--cinza-200) !important;

            color:
                var(--cinza-700) !important;

        }


        /* =====================================================
           ALERTA
           ===================================================== */

        .alert-success {

            background:
                #eef8f3 !important;

            border:
                1px solid #cde9d9 !important;

            color:
                #176b3c !important;

            border-radius:
                10px;

        }


        /* =====================================================
           BLOQUEADO
           ===================================================== */

        .bi-lock-fill {

            color:
                var(--azul-700) !important;

        }


        /* =====================================================
           TEXTOS
           ===================================================== */

        .text-muted {

            color:
                var(--cinza-600) !important;

        }


        /* =====================================================
           RESPONSIVO
           ===================================================== */

        @media (max-width: 768px) {

            .navbar-brand {

                font-size:
                    .95rem;

            }


            .navbar .d-flex.text-white {

                margin-top:
                    10px;

            }


            .codigo-principal {

                font-size:
                    1.45rem;

                letter-spacing:
                    2px;

            }


            .card-body {

                padding:
                    18px;

            }

        }

    </style>

</head>


<body>


    <!-- =====================================================
         NAVBAR
         ===================================================== -->

    <nav
        class="navbar navbar-expand-lg navbar-dark mb-4 shadow-sm"
    >

        <div class="container">


            <a
                class="navbar-brand"
                href="#"
            >

                <i class="bi bi-shop"></i>

                Portal do Empreendedor
                <span class="d-none d-md-inline">
                    - Turismo PB
                </span>

            </a>


            <div
                class="d-flex text-white align-items-center"
            >

                <i
                    class="bi bi-person-circle fs-4 me-2"
                ></i>


                <span class="me-3 d-none d-sm-inline">

                    {{ $empreendedor->nome_fantasia }}

                </span>


                <button
                    type="button"
                    id="btnLogout"
                    class="btn btn-outline-light btn-sm"
                >

                    <i
                        class="bi bi-box-arrow-right"
                    ></i>

                    Sair

                </button>

            </div>

        </div>

    </nav>



    <!-- =====================================================
         CONTEÚDO
         ===================================================== -->

    <div class="container">


        @if($empreendedor->status === 'aprovado')


            <!-- =================================================
                 ALERTA
                 ================================================= -->

            <div
                class="alert alert-success d-flex align-items-center mb-4"
            >

                <i
                    class="bi bi-check-circle-fill me-2"
                ></i>


                <div>

                    <strong>
                        Cadastro aprovado!
                    </strong>

                    <br class="d-md-none">

                    Você já pode gerar códigos de troca
                    para os usuários.

                </div>

            </div>



            <!-- =================================================
                 INDICADORES
                 ================================================= -->

            <div class="row mb-4">


                <div class="col-md-6">

                    <div
                        class="card shadow-sm card-metric mb-3"
                    >

                        <div class="card-body">

                            <div
                                class="d-flex justify-content-between align-items-center"
                            >

                                <div>

                                    <h6 class="mb-2">

                                        Turistas Recebidos pelo App

                                    </h6>


                                    <h2 class="mb-0">

                                        124

                                    </h2>

                                </div>


                                <div
                                    class="text-primary"
                                    style="font-size: 2rem;"
                                >

                                    <i
                                        class="bi bi-people"
                                        style="color: var(--azul-600);"
                                    ></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="col-md-6">

                    <div
                        class="card shadow-sm card-metric mb-3"
                    >

                        <div class="card-body">

                            <div
                                class="d-flex justify-content-between align-items-center"
                            >

                                <div>

                                    <h6 class="mb-2">

                                        Códigos Gerados

                                    </h6>


                                    <h2
                                        class="mb-0"
                                        id="totalCodigos"
                                    >

                                        {{ $codigos->count() }}

                                    </h2>

                                </div>


                                <div
                                    style="
                                        font-size: 2rem;
                                        color: var(--azul-600);
                                    "
                                >

                                    <i
                                        class="bi bi-upc-scan"
                                    ></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 ÁREA PRINCIPAL
                 ================================================= -->

            <div class="row">


                <!-- =============================================
                     DADOS DO ESTABELECIMENTO
                     ============================================= -->

                <div class="col-lg-7 mb-4">

                    <div
                        class="card shadow-sm h-100"
                    >


                        <div
                            class="card-header py-3"
                        >

                            <h5 class="mb-0">

                                <i
                                    class="bi bi-building me-2"
                                    style="color: var(--azul-600);"
                                ></i>

                                Dados do Estabelecimento

                            </h5>

                        </div>


                        <div class="card-body">


                            <div class="mb-3">

                                <label
                                    class="form-label"
                                >

                                    Nome Fantasia

                                </label>


                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $empreendedor->nome_fantasia }}"
                                    readonly
                                >

                            </div>



                            <div class="mb-3">

                                <label
                                    class="form-label"
                                >

                                    Endereço

                                </label>


                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $empreendedor->endereco ?? 'Não informado' }}"
                                    readonly
                                >

                            </div>



                            <div class="mb-3">

                                <label
                                    class="form-label"
                                >

                                    Descrição

                                </label>


                                <textarea
                                    class="form-control"
                                    rows="4"
                                    readonly
                                >{{ $empreendedor->descricao ?? 'Não informado' }}</textarea>

                            </div>


                        </div>

                    </div>

                </div>



                <!-- =============================================
                     CÓDIGOS
                     ============================================= -->

                <!-- COLUNA DIREITA: CÓDIGO ROTATIVO -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm h-100 border-warning">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-upc-scan"></i> Código Automático (Turista)
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted small mb-4">
                            Este é o seu código atual. Assim que um turista utilizá-lo, <strong>um novo código será gerado automaticamente</strong> no lugar dele.
                        </p>

                        @php
                            $codigoAtual = $codigos->first(); 
                        @endphp

                        <!-- CAIXA DO CÓDIGO DA FILA -->
                        <div class="codigo-box p-4 mb-4">
                            <div class="text-muted fw-bold mb-2">AGUARDANDO RESGATE</div>
                            <div id="codigoGerado" class="codigo-principal mb-2">
                                {{ $codigoAtual ? $codigoAtual->codigo : 'NENHUM-CÓDIGO' }}
                            </div>
                            <div class="badge bg-warning text-dark fs-6" id="badgeMoedas">
                                <i class="bi bi-coin"></i> {{ $codigoAtual ? $codigoAtual->moedas : 0 }} moedas
                            </div>
                        </div>

                        <hr>

                        <!-- FORMULÁRIO PARA ALTERAR O VALOR DA FILA -->
                        <form id="formFila" class="text-start">
                            <label class="form-label fw-bold">Alterar valor da recompensa</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light"><i class="bi bi-coin"></i></span>
                                <input type="number" id="quantidadeMoedas" class="form-control" value="{{ $codigoAtual ? $codigoAtual->moedas : 50 }}" min="1">
                                <button type="button" id="btnAtualizarFila" class="btn btn-warning fw-bold">Atualizar</button>
                            </div>
                            <small class="text-muted">Isso mudará o valor do código atual e de todos os próximos que forem gerados automaticamente.</small>
                        </form>

                    </div>
                </div>
            </div>


            </div>


        @else


            <!-- =================================================
                 BLOQUEADO
                 ================================================= -->

            <div class="row mt-5">

                <div class="col-12">


                    <div
                        class="card text-center p-5"
                    >

                        <div>

                            <i
                                class="bi bi-lock-fill"
                                style="
                                    font-size: 4.5rem;
                                "
                            ></i>

                        </div>


                        <h3
                            class="mt-4"
                            style="
                                color: var(--azul-900);
                            "
                        >

                            Funcionalidades Bloqueadas

                        </h3>


                        <p
                            class="text-muted fs-5 mx-auto"
                            style="max-width: 650px;"
                        >

                            Assim que a Prefeitura validar e
                            aprovar seus dados, você poderá
                            gerar códigos de troca para os
                            usuários.

                        </p>


                        <p class="mb-0">

                            Status atual:

                            <span
                                class="badge bg-light text-dark border ms-1"
                            >

                                {{ ucfirst($empreendedor->status) }}

                            </span>

                        </p>

                    </div>

                </div>

            </div>


        @endif


    </div>



    <!-- =====================================================
         BOOTSTRAP
         ===================================================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    ></script>



    <script>
    /* =====================================================
       CSRF
       ===================================================== */
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    /* =====================================================
       LOGOUT
       ===================================================== */
    const btnLogout = document.getElementById('btnLogout');

    btnLogout?.addEventListener('click', async () => {
        const textoOriginal = btnLogout.innerHTML;
        try {
            btnLogout.disabled = true;
            btnLogout.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saindo...';
            
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
        } catch (error) {
            console.error('Erro ao sair:', error);
        } finally {
            localStorage.removeItem('auth_token');
            window.location.href = '/login-empreendedor';
        }
    });

    /* =====================================================
       ELEMENTOS DA FILA ROTATIVA
       ===================================================== */
    const btnAtualizarFila = document.getElementById('btnAtualizarFila');
    const quantidadeMoedas = document.getElementById('quantidadeMoedas');
    const codigoGerado = document.getElementById('codigoGerado');
    const badgeMoedas = document.getElementById('badgeMoedas');
    const btnCopiarCodigo = document.getElementById('btnCopiarCodigo');

    /* =====================================================
       ATUALIZAR VALOR DA FILA
       ===================================================== */
    btnAtualizarFila?.addEventListener('click', async () => {
        const textoOriginal = btnAtualizarFila.innerHTML;

        try {
            const moedas = Number(quantidadeMoedas.value);

            if (!Number.isInteger(moedas) || moedas < 1 || moedas > 10000) {
                alert('Informe uma quantidade de moedas válida (entre 1 e 10.000).');
                quantidadeMoedas.focus();
                return;
            }

            btnAtualizarFila.disabled = true;
            btnAtualizarFila.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            const response = await fetch('/empreendedor/fila/atualizar', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ moedas: moedas })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Erro ao atualizar a fila.');
            }

            // Atualiza os dados na tela visualmente
            codigoGerado.textContent = data.codigo;
            badgeMoedas.innerHTML = `<i class="bi bi-coin"></i> ${data.moedas} moedas`;

            // Feedback de sucesso visual no botão
            btnAtualizarFila.classList.replace('btn-warning', 'btn-success');
            btnAtualizarFila.innerHTML = '<i class="bi bi-check-lg"></i> Salvo!';
            
            setTimeout(() => {
                btnAtualizarFila.classList.replace('btn-success', 'btn-warning');
                btnAtualizarFila.innerHTML = textoOriginal;
            }, 2000);

        } catch (error) {
            console.error('Erro ao atualizar fila:', error);
            alert(error.message);
            btnAtualizarFila.innerHTML = textoOriginal;
        } finally {
            btnAtualizarFila.disabled = false;
        }
    });

    /* =====================================================
       COPIAR CÓDIGO ATUAL
       ===================================================== */
    btnCopiarCodigo?.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(codigoGerado.textContent);
            
            const conteudoAntigo = btnCopiarCodigo.innerHTML;
            btnCopiarCodigo.innerHTML = '<i class="bi bi-check me-1"></i>Copiado!';

            setTimeout(() => {
                btnCopiarCodigo.innerHTML = conteudoAntigo;
            }, 2000);
        } catch (error) {
            alert('Não foi possível copiar o código.');
        }
    });

</script>


</body>

</html>