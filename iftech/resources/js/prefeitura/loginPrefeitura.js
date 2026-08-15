const form = document.getElementById('loginForm');
const mensagem = document.getElementById('mensagem');
const loginButton = document.getElementById('loginButton');

form.addEventListener('submit', async function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    mensagem.textContent = '';
    mensagem.className = '';

    loginButton.disabled = true;
    loginButton.innerHTML = `
        <span>Entrando...</span>
        <i class="fa-solid fa-circle-notch fa-spin"></i>
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

        if (!response.ok) {
            throw new Error(
                data.message || 'Erro ao realizar login.'
            );
        }

        if (data.user.role !== 'prefeito') {
            mensagem.textContent =
                'Acesso permitido somente para usuários da Prefeitura.';

            mensagem.className = 'error';
            return;
        }

        localStorage.setItem('token', data.token);

        localStorage.setItem(
            'user',
            JSON.stringify(data.user)
        );

        mensagem.textContent = 'Login realizado com sucesso.';
        mensagem.className = 'success';

        setTimeout(() => {
            window.location.href = '/logado-prefeitura';
        }, 500);

    } catch (error) {
        mensagem.textContent = error.message;
        mensagem.className = 'error';

    } finally {
        loginButton.disabled = false;

        loginButton.innerHTML = `
            <span>Entrar no painel</span>
            <i class="fa-solid fa-arrow-right"></i>
        `;
    }
});