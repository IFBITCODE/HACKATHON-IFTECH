<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prefeitura | Acesso Administrativo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite([
        'resources/css/prefeitura/loginPrefeitura.css',
        'resources/js/prefeitura/loginPrefeitura.js'
    ])
</head>
<body>
    <main class="login-wrapper">
        <section class="login-brand">
            <div class="brand-content">
                <div class="brand-icon">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <h2 class="brand-title">
                    Gestão <span>Turística</span>
                </h2>
                <p class="brand-description">
                    Painel administrativo para gestão, acompanhamento
                    e desenvolvimento do turismo municipal.
                </p>
            </div>

            <div class="brand-footer">
                <i class="fa-solid fa-shield-halved"></i>
                Ambiente administrativo seguro
            </div>
        </section>

        <section class="login-area">
            <div class="login-header">
                <div class="tag">
                    <i class="fa-solid fa-lock"></i>
                    Acesso administrativo
                </div>

                <h1>Bem-vindo de volta</h1>

                <p>
                    Entre com suas credenciais para acessar
                    o painel da Prefeitura.
                </p>
            </div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">E-mail</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Digite seu e-mail"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Digite sua senha"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    id="loginButton"
                    class="login-button"
                >
                    <span>Entrar no painel</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div id="mensagem"></div>

            <div class="security-info">
                <i class="fa-solid fa-circle-check"></i>
                Acesso restrito a usuários autorizados
            </div>
        </section>
    </main>
</body>
</html>