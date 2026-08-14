<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rotaguiada | Turismo Inteligente</title>

    <!-- Ícones do FontAwesome para deixar o formato visual atraente -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

            <!-- Container da Busca -->
            <div class="search-container">

                <form id="searchForm" action="{{ url('/chat') }}" method="POST" class="search-box">
                    @csrf

                    <input
                        id="searchInput"
                        type="text"
                        name="mensagem"
                        placeholder="Ex: Onde comer cartola em Campina Grande?"
                        autocomplete="off"
                        value="{{ $mensagemUser ?? '' }}"
                        required
                    >

                    <button type="submit" id="searchButton" class="search-button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <!-- CARD DE RESULTADO FORMATADO ABAIXO DO INPUT -->
                <div class="search-result-container {{ isset($resposta) ? 'active' : '' }}" id="searchResult">
                    
                    <!-- Cabeçalho da Resposta -->
                    <div class="result-header">
                        <div class="header-tag">
                            <i class="fa-solid fa-compass"></i>
                            <span id="resultTag">Guia Rotaguiada</span>
                        </div>
                    </div>

                    <!-- Corpo com Texto Formatado -->
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

</body>
</html>