const form = document.getElementById('loginForm');
const mensagem = document.getElementById('mensagem');
const loginButton = document.getElementById('loginButton');

form.addEventListener('submit', async function(event) {

    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Limpa mensagens anteriores
    mensagem.textContent = '';
    mensagem.className = '';

    // Mostra apenas o spinner
    loginButton.disabled = true;

    loginButton.innerHTML = `
        <span class="spinner"></span>
    `;

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

        // Verifica se a requisição retornou erro
        if (!response.ok) {

            throw new Error(
                data.message || 'Erro ao realizar login.'
            );

        }

        // Verifica se o usuário possui permissão
        if (!data.user || data.user.role !== 'prefeito') {

            mensagem.textContent =
                'Acesso permitido somente para usuários da Prefeitura.';

            mensagem.className = 'error';

            loginButton.disabled = false;

            loginButton.innerHTML = `
                Entrar
            `;

            return;
        }

        // Salva o token
        localStorage.setItem(
            'token',
            data.token
        );

        // Salva os dados do usuário
        localStorage.setItem(
            'user',
            JSON.stringify(data.user)
        );

        // Efeito de piscar a tela
        document.body.classList.add('login-success');

        // Aguarda o efeito antes de mudar de página
        setTimeout(() => {

            window.location.href =
                '/logado-prefeitura';

        }, 300);

    } catch (error) {

        // Exibe apenas erros
        mensagem.textContent =
            error.message;

        mensagem.className =
            'error';

        // Restaura o botão
        loginButton.disabled = false;

        loginButton.innerHTML = `
            Entrar
        `;

    }

});