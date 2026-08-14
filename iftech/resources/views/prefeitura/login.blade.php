<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Prefeitura</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f3f4f6;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #1f2937;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #111827;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        #mensagem {
            margin-top: 20px;
            text-align: center;
            color: #dc2626;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <h1>Prefeitura</h1>

        <p class="subtitle">
            Acesso administrativo
        </p>

        <form id="loginForm">

            <div class="form-group">
                <label for="email">E-mail</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    placeholder="Digite seu e-mail"
                >
            </div>

            <div class="form-group">
                <label for="password">Senha</label>

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

    <script>
        const form = document.getElementById('loginForm');
        const mensagem = document.getElementById('mensagem');
        const loginButton = document.getElementById('loginButton');

        form.addEventListener('submit', async function(event) {

            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            mensagem.textContent = '';
            loginButton.disabled = true;
            loginButton.textContent = 'Entrando...';

            try {

                const response = await fetch('/api/login', {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },

                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.message || 'Erro ao realizar login.'
                    );
                }

                if (data.user.role !== 'prefeito') {

                    mensagem.textContent =
                        'Acesso permitido somente para usuários da Prefeitura.';

                    return;
                }

                localStorage.setItem('token', data.token);
                localStorage.setItem(
                    'user',
                    JSON.stringify(data.user)
                );

                window.location.href = '/home-prefeitura';

            } catch (error) {

                mensagem.textContent = error.message;

            } finally {

                loginButton.disabled = false;
                loginButton.textContent = 'Entrar';

            }

        });
    </script>

</body>
</html>