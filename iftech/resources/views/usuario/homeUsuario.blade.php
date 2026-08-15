<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rotaguiada | Turismo Inteligente</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        window.authUser = @json(auth()->user());
    </script>

    @vite([
        'resources/css/usuario/homeUsuario.css',
        'resources/js/usuario/homeUsuario.js'
    ])
</head>

<body>

    <div id="loginModal" class="login-modal">

        <div class="login-box">

            <button class="close-login" id="closeLogin">&times;</button>

            <div class="login-icon">
                <i class="fa-solid fa-compass"></i>
            </div>

            <h2> Entrar </h2>

            <p class="login-subtitle">
                Entre para curtir o melhor da cidade e ganhar recompensas por cada experiência.
            </p>

            <form id="fakeLoginForm">

                <input
                    id="loginEmail"
                    type="email"
                    placeholder="Email"
                    required
                >

                <input
                    id="loginPassword"
                    type="password"
                    placeholder="Senha"
                    required
                >

                <button type="submit" class="login-submit">
                    Entrar
                </button>

            </form>

            <div class="divider">
                <span>ou</span>
            </div>

            <a
                href="{{ route('google.login') }}"
                class="google-button"
                id="googleLogin"
            >
                <i class="fa-brands fa-google"></i>
                Entrar com Google
            </a>

            <p class="register-link">
                Não tem conta?
                <a href="#" id="registerFake">Cadastrar</a>
            </p>

            <div id="loginMessage">
                @if(session('google_login_error'))
                    <div class="login-error">
                        {{ session('google_login_error') }}
                    </div>
                @endif
            </div>

            <div id="loginMessage"></div>

        </div>

    </div>

    <header class="topbar">

        <button id="coinsToggle" class="coins-fab">
            <i class="fa-solid fa-coins"></i>
        </button>

        <button id="openLogin" class="login-button">
            Entrar
        </button>

        <aside id="coinsSidebar" class="coins-sidebar">

            <div class="sidebar-header">

                <div>
                    <span>{{ auth()->user()->name ?? 'Usuário' }}</span>
                    <h3>Suas moedas</h3>
                </div>

                <button id="closeSidebar">
                    &times;
                </button>

            </div>

            <div class="coins-content">

                <div class="coins-balance">

                    <i class="fa-solid fa-coins"></i>

                    <div>

                        <span>Saldo atual</span>

                        <strong id="coinsValue">{{ auth()->check() ? (auth()->user()->moedas ?? 0) : 0 }}</strong>

                        <small>moedas</small>

                    </div>

                </div>

                <div class="code-section">

                    <h4>Resgatar moedas</h4>

                    <p>
                        Chegou no local? solicite seu codigo e
                        digite abaixo para adicionar sua moedas.
                    </p>

                    <form id="codeForm" class="code-form">

                        <input
                            type="text"
                            id="codeInput"
                            placeholder="Ex: TURISMO100"
                            autocomplete="off"
                            required
                        >

                        <button type="submit">
                            Resgatar
                        </button>

                    </form>

                    <div id="codeMessage"></div>

                </div>

                <div class="valid-codes">

                    <span>Códigos para teste</span>

                    <strong>TURISMO100</strong>
                    <strong>ROTAPB50</strong>
                    <strong>GUIA25</strong>

                </div>

            </div>

        </aside>

        <div id="sidebarOverlay" class="sidebar-overlay"></div>

    </header>

    <main class="landing-page">

        <section class="hero">

            <div class="hero-content">

                <div class="brand">

                    <h1>
                        ROTAGU<span>IA</span>DA
                    </h1>

                </div>

                <div class="search-container">

                    <form
                        id="searchForm"
                        action="{{ url('/chat') }}"
                        method="POST"
                        class="search-box"
                    >

                        @csrf

                        <input
                            id="searchInput"
                            type="text"
                            name="mensagem"
                            placeholder="Descubra o melhor da cidade"
                            autocomplete="off"
                            value="{{ $mensagemUser ?? '' }}"
                            required
                        >

                        <button
                            type="submit"
                            id="searchButton"
                            class="search-button"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                    </form>

                    <div
                        class="search-result-container {{ isset($resposta) ? 'active' : '' }}"
                        id="searchResult"
                    >

                        <div class="result-header">

                            <div class="header-tag">

                                <i class="fa-solid fa-compass"></i>

                                <span id="resultTag">
                                    Guia Rotaguiada
                                </span>

                            </div>

                        </div>

                        <div class="result-body" id="resultBody">

                            @if(isset($resposta))
                                {!! nl2br(e($resposta)) !!}
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>
    <!-- SCRIPT DE RESGATE DE CÓDIGOS ROTAGUIADA -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const codeForm = document.getElementById('codeForm');
            const codeInput = document.getElementById('codeInput');
            const coinsValue = document.getElementById('coinsValue');
            const codeMessage = document.getElementById('codeMessage');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            codeForm?.addEventListener('submit', async (e) => {
                e.preventDefault(); // Evita que a página recarregue ao apertar Enter/Resgatar
                
                const codigo = codeInput.value.trim().toUpperCase();
                const btnSubmit = codeForm.querySelector('button[type="submit"]');
                const textoOriginal = btnSubmit.innerHTML;

                if (codigo.length < 5) return;

                try {
                    // Estado de carregamento
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
                    codeMessage.innerHTML = ''; // Limpa mensagens de erro antigas

                    const response = await fetch('/usuario/codigos/usar', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ codigo: codigo })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Código inválido ou já utilizado.');
                    }

                    // Sucesso! Atualiza o número de moedas na tela
                    coinsValue.textContent = data.saldo_atual;
                    
                    // Mostra mensagem verde de sucesso
                    codeInput.value = '';
                    codeMessage.innerHTML = `<div style="color: #28a745; margin-top: 10px; font-weight: bold; text-align: center;">
                        <i class="fa-solid fa-check-circle"></i> +${data.moedas_ganhas} moedas!
                    </div>`;
                    
                    // Apaga a mensagem depois de 4 segundos
                    setTimeout(() => {
                        codeMessage.innerHTML = '';
                    }, 4000);

                } catch (error) {
                    // Mostra mensagem vermelha de erro
                    codeMessage.innerHTML = `<div style="color: #dc3545; margin-top: 10px; font-weight: bold; text-align: center;">
                        <i class="fa-solid fa-circle-exclamation"></i> ${error.message}
                    </div>`;
                } finally {
                    // Devolve o botão ao estado normal
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = textoOriginal;
                }
            });
        });
    </script>

</body>

</html>