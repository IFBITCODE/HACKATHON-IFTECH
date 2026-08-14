<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rotaguiada | Turismo Inteligente</title>

    <!-- Importação do Vite (CSS e JS) -->
    @vite([
        'resources/css/usuario/homeUsuario.css',
        'resources/js/usuario/homeUsuario.js'
    ])
</head>

<body>

<main class="landing-page">
    <section class="hero">

        <div class="hero-content">

            <!-- Logo -->
            <div class="brand">
                <h1>ROTAGU<span>IA</span>DA</h1>
            </div>

            <!-- Estrada SVG + Marcador de Localização Amarelo -->
            <div class="route-container">
                <svg class="road-path" viewBox="0 0 500 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 20 170 C 130 170 180 110 100 90 C 20 70 80 25 380 25" 
                          stroke="#FAFAFA" stroke-width="26" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <div class="location-marker">
                    <div class="marker-circle">
                        <svg class="pin-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M12 21s7-6.2 7-11a7 7 0 0 0-14 0c0 4.8 7 11 7 11z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Container da Busca (Sem lupa + Botão Amarelo) -->
<!-- Container da Busca / Chat -->
<div class="search-container">

    <form action="{{ url('/chat') }}" method="POST" class="search-box">

        @csrf

        <input
            id="searchInput"
            type="text"
            name="mensagem"
            placeholder="Digite sua pesquisa..."
            autocomplete="off"
            required
        >

        <button
            type="submit"
            id="searchButton"
            class="search-button"
        >
            <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#202124"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle cx="11" cy="11" r="8"></circle>
                <line
                    x1="21"
                    y1="21"
                    x2="16.65"
                    y2="16.65"
                ></line>
            </svg>
        </button>

    </form>


    <!-- MENSAGENS DO CHAT -->
    @if(isset($resposta))

        <div class="chat-messages">

            <!-- Mensagem do usuário -->
            <div class="message user-message">

                <div class="message-label">
                    Você
                </div>

                <div class="message-text">
                    {{ $mensagemUser }}
                </div>

            </div>


            <!-- Resposta do chatbot -->
            <div class="message bot-message">

                <div class="message-label">
                    RotaGuiada
                </div>

                <div class="message-text">
                    {{ $resposta }}
                </div>

            </div>

        </div>

    @endif

</div>

        </div>

    </section>
</main>

</body>

</html>