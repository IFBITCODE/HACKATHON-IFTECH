// Gerenciador de Views (Alternância entre Login, Cadastro e Início)
window.switchView = function (viewName) {
    // 1. Esconder todas as seções
    const sections = document.querySelectorAll('.view-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    // 2. Remover classe ativa dos botões do nav
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(btn => {
        btn.classList.remove('active');
    });

    // 3. Exibir a seção solicitada
    const targetSection = document.getElementById(`view-${viewName}`);
    if (targetSection) {
        targetSection.classList.add('active');
    } else {
        console.error(`Seção view-${viewName} não encontrada!`);
    }

    // 4. Ativar o botão correto no menu do topo
    if (viewName === 'welcome') {
        document.getElementById('btnNavWelcome')?.classList.add('active');
    } else if (viewName === 'login') {
        document.getElementById('btnNavLogin')?.classList.add('active');
    } else if (viewName === 'register') {
        document.getElementById('btnNavRegister')?.classList.add('active');
    }

    // Rolar para o topo suavemente
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

document.addEventListener("DOMContentLoaded", function () {

    // --- CONTADOR DE CARACTERES ---
    const description = document.getElementById("description");
    const charCount = document.getElementById("charCount");

    if (description && charCount) {
        description.addEventListener("input", function () {
            const length = description.value.length;
            charCount.textContent = `${length} / 500`;
        });
    }

    // --- ENVIO DO FORMULÁRIO DE CADASTRO ---
    const registerForm = document.getElementById("entrepreneurForm");
    const statusAlert = document.getElementById("statusAlert");

    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const password = document.getElementById("password");
            const passwordConfirm = document.getElementById("password_confirmation");

            // Validação simples de confirmação de senha
            if (password && passwordConfirm) {
                if (password.value !== passwordConfirm.value) {
                    passwordConfirm.setCustomValidity("As senhas não coincidem.");
                } else {
                    passwordConfirm.setCustomValidity("");
                }
            }

            if (!registerForm.checkValidity()) {
                registerForm.classList.add("was-validated");
                return;
            }

            // Exibe mensagem de sucesso se o form estiver válido
            registerForm.style.display = "none";
            if (statusAlert) {
                statusAlert.classList.remove("d-none");
            }

            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    // --- ENVIO DO FORMULÁRIO DE LOGIN ---
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();

            if (!loginForm.checkValidity()) {
                loginForm.classList.add("was-validated");
                return;
            }

            alert("Login efetuado com sucesso (Simulação)!");
        });
    }
});