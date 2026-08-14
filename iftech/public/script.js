(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    window.switchView = function (view) {
        document.querySelectorAll('.view-section').forEach(section => section.classList.remove('active'));
        const target = document.getElementById(`view-${view}`);
        if (target) target.classList.add('active');

        document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
        const nav = document.getElementById(`btnNav${view.charAt(0).toUpperCase()}${view.slice(1)}`);
        if (nav) nav.classList.add('active');
    };

    const loginForm = document.getElementById('loginForm');
    loginForm?.addEventListener('submit', async function (event) {
        event.preventDefault();

        const email = document.getElementById('login_email')?.value.trim();
        const password = document.getElementById('login_password')?.value;
        const button = loginForm.querySelector('button[type="submit"]');
        const original = button?.innerHTML;

        if (!email || !password) {
            alert('Informe seu e-mail e senha.');
            return;
        }

        try {
            if (button) {
                button.disabled = true;
                button.innerHTML = 'Entrando...';
            }

            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (!response.ok) throw new Error(data.message || 'E-mail ou senha inválidos.');

            if (data.user?.role && data.user.role !== 'empreendedor') {
                throw new Error('Esta conta não é uma conta de empreendedor.');
            }

            if (data.token) localStorage.setItem('auth_token', data.token);
            window.location.href = '/logado-empreendedor';
        } catch (error) {
            alert(error.message);
        } finally {
            if (button) {
                button.disabled = false;
                button.innerHTML = original;
            }
        }
    });

    const form = document.getElementById('entrepreneurForm');
    form?.addEventListener('submit', async function (event) {
        event.preventDefault();

        const name = document.getElementById('businessName')?.value.trim();
        const category = document.getElementById('category')?.value;
        const documentValue = document.getElementById('document')?.value.trim();
        const description = document.getElementById('description')?.value.trim();
        const email = document.getElementById('email')?.value.trim();
        const password = document.getElementById('password')?.value;
        const confirmation = document.getElementById('password_confirmation')?.value;
        const address = document.getElementById('address')?.value.trim();
        const neighborhood = document.getElementById('neighborhood')?.value.trim();
        const terms = document.getElementById('terms')?.checked;
        const button = form.querySelector('button[type="submit"]');
        const original = button?.innerHTML;

        if (!name || !documentValue || !description || !email || !password || !confirmation) {
            alert('Preencha todos os campos obrigatórios.');
            return;
        }
        if (password.length < 8) {
            alert('A senha deve ter no mínimo 8 caracteres.');
            return;
        }
        if (password !== confirmation) {
            alert('As senhas não conferem.');
            return;
        }
        if (!terms) {
            alert('Você precisa aceitar os termos para continuar.');
            return;
        }

        try {
            if (button) {
                button.disabled = true;
                button.innerHTML = 'Criando conta...';
            }

            const registerResponse = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                },
                body: JSON.stringify({
                    name,
                    email,
                    password,
                    password_confirmation: confirmation,
                    role: 'empreendedor'
                })
            });

            const registerData = await registerResponse.json();
            if (!registerResponse.ok) {
                const message = registerData.message || Object.values(registerData.errors || {}).flat().join('\n');
                throw new Error(message || 'Não foi possível criar a conta.');
            }

            const userId = registerData.user?.id;
            if (!userId) throw new Error('A conta foi criada, mas não foi possível identificar o usuário.');

            if (button) button.innerHTML = 'Salvando cadastro...';

            const entrepreneurResponse = await fetch('/api/empreendedores', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                },
                body: JSON.stringify({
                    user_id: userId,
                    nome_fantasia: name,
                    cpf_cnpj: documentValue,
                    category_id: null,
                    email,
                    endereco: address || null,
                    bairro: neighborhood || null,
                    descricao: description,
                    acessivel: [
                        document.getElementById('acc_rampa')?.checked,
                        document.getElementById('acc_libras')?.checked,
                        document.getElementById('acc_banheiro')?.checked
                    ].some(Boolean),
                    recursos_acessibilidade: [
                        document.getElementById('acc_rampa')?.checked ? 'Rampa de Acesso / Térreo' : null,
                        document.getElementById('acc_libras')?.checked ? 'Atendimento em Libras' : null,
                        document.getElementById('acc_banheiro')?.checked ? 'Banheiro Adaptado (PCD)' : null
                    ].filter(Boolean).join(', ') || null
                })
            });

            const entrepreneurData = await entrepreneurResponse.json();
            if (!entrepreneurResponse.ok) {
                throw new Error(entrepreneurData.message || Object.values(entrepreneurData.errors || {}).flat().join('\n') || 'Não foi possível concluir o cadastro.');
            }

            alert('Cadastro realizado com sucesso! Agora entre com seu e-mail e senha.');
            form.reset();
            const counter = document.getElementById('charCount');
            if (counter) counter.textContent = '0 / 500';
            switchView('login');
        } catch (error) {
            alert(error.message);
        } finally {
            if (button) {
                button.disabled = false;
                button.innerHTML = original;
            }
        }
    });

    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    description?.addEventListener('input', function () {
        if (charCount) charCount.textContent = `${this.value.length} / 500`;
    });
})();
