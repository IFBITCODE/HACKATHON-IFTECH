<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rotaguiada | Turismo Inteligente</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite([
        'resources/css/usuario/homeUsuario.css',
        'resources/js/usuario/homeUsuario.js'
    ])
</head>

<body>

    <div id="loginModal" class="login-modal">
        <div class="login-box">
            <button class="close-login" id="closeLogin">&times;</button>


            <h2>Entrar</h2>

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

            <button type="button" class="google-button" id="googleLogin">
                <i class="fa-brands fa-google"></i>
                Entrar com Google
            </button>

            <p class="register-link">
                Não tem conta?
                <a href="#" id="registerFake">Cadastrar</a>
            </p>

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

                <button id="closeSidebar">&times;</button>
            </div>

            <div class="coins-content">
                <div class="coins-balance">
                    <i class="fa-solid fa-coins"></i>
                    <div>
                        <span>Saldo atual</span>
                        <strong id="coinsValue">25</strong>
                        <small>moedas</small>
                    </div>
                </div>

                <div class="code-section">
                    <h4>Resgatar moedas</h4>
                    <p>
                        Chegou no local? solicite seu código e digite abaixo para adicionar suas moedas.
                    </p>

                    <form id="codeForm" class="code-form">
                        <input
                            type="text"
                            id="codeInput"
                            placeholder="Ex: TURISMO100"
                            autocomplete="off"
                            required
                        >
                        <button type="submit">Resgatar</button>
                    </form>

                    <div id="codeMessage"></div>
                </div>

            </div>
        </aside>

        <div id="sidebarOverlay" class="sidebar-overlay"></div>
    </header>

    <main class="landing-page">

<section
    class="hero-section"
    style="background-image: url('{{ asset('imagens/fundo.png') }}');"
>
    <div class="hero-content">
        <div class="brand">
            <h1>ROTAGU<span>IA</span>DA</h1>
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
                    placeholder="Descubra o melhor da cidade..."
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
        </div>
    </div>

    <!-- Divisor com Ondas Suaves em Camadas -->
<div class="mountain-divider">

    <svg 
        class="mountain-back" 
        viewBox="0 0 1200 120" 
        preserveAspectRatio="none"
    >
        <path d="
            M0,68
            C80,48 150,48 220,64
            C290,80 350,88 420,70
            C500,48 550,42 620,60
            C700,82 760,92 830,68
            C900,44 960,42 1030,62
            C1100,82 1150,78 1200,68
            L1200,120
            L0,120
            Z
        "></path>
    </svg>


    <svg 
        class="mountain-mid" 
        viewBox="0 0 1200 120" 
        preserveAspectRatio="none"
    >
        <path d="
            M0,80
            C70,68 120,58 190,76
            C250,92 310,100 380,78
            C450,56 500,52 570,72
            C640,94 700,88 760,72
            C830,54 880,58 940,76
            C1010,96 1080,92 1140,76
            C1170,70 1190,72 1200,80
            L1200,120
            L0,120
            Z
        "></path>
    </svg>


    <svg 
        class="mountain-front" 
        viewBox="0 0 1200 120" 
        preserveAspectRatio="none"
    >
        <path d="
            M0,91

            C65,103 120,106 175,94
            C235,80 285,74 345,89
            C405,104 455,108 515,94
            C575,80 625,72 685,87
            C745,102 795,106 855,92
            C915,78 965,72 1025,86

            C1070,96 1110,101 1145,96
            C1170,93 1185,91 1200,91

            L1200,120
            L0,120
            Z
        "></path>
    </svg>

</div>
    
</section>

        <section class="content-section">
            <div class="results-wrapper">
                <div
                    class="search-result-container {{ isset($resposta) ? 'active' : '' }}"
                    id="searchResult"
                >
                    <div class="result-header">
                        <div class="header-tag">
                            <i class="fa-solid fa-compass"></i>
                            <span id="resultTag">Guia Rotaguiada</span>
                        </div>
                    </div>

                    <div class="result-body" id="resultBody">
                        @if(isset($resposta))
                            {!! nl2br(e($resposta)) !!}
                        @endif
                    </div>
                </div>
            </div>
        </section>

    </main>

</body>

</html>