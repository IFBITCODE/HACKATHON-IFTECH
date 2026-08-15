<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Prefeitura</title>

    @vite([
        'resources/css/prefeitura/loginPrefeitura.css',
        'resources/js/prefeitura/loginPrefeitura.js'
    ])
</head>

<body>
    <body style="background-image: url('{{ asset('imagens/fundo.png') }}');">

    <div class="login-container">

        <h1>ROTAGUIADA</h1>

        <p class="subtitle">
            Portal administrativo
        </p>

        <form
            id="loginForm"
            method="POST"
            action="{{ route('login-prefeitura.submit') }}"
        >
            @csrf

            <div class="form-group">

                <label for="email">
                    E-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    placeholder="Digite seu e-mail"
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Senha
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Digite sua senha"
                >

            </div>

            <button type="submit" id="loginButton">
                Entrar
            </button>

        </form>

        <div id="mensagem"></div>

    </div>

</body>

</html>