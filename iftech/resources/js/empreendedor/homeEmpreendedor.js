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
    registerForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        const password = document.getElementById("password");
        const passwordConfirm = document.getElementById("password_confirmation");

        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity("As senhas não coincidem.");
        } else {
            passwordConfirm.setCustomValidity("");
        }

        if (!registerForm.checkValidity()) {
            registerForm.classList.add("was-validated");
            return;
        }

        try {
            const resUser = await fetch("/api/register", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json" },
                body: JSON.stringify({
                    name: document.getElementById("businessName").value,
                    email: document.getElementById("email").value,
                    password: password.value,
                    password_confirmation: passwordConfirm.value,
                    role: "empreendedor",
                }),
            });

            const userData = await resUser.json();
            if (!resUser.ok) throw new Error(userData.message || "Erro ao criar conta");

            const resEmpreendedor = await fetch("/api/empreendedores", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json" },
                body: JSON.stringify({
                    user_id: userData.user.id,
                    nome_fantasia: document.getElementById("businessName").value,
                    cpf_cnpj: document.getElementById("document").value,
                    descricao: document.getElementById("description").value,
                    endereco: document.getElementById("address").value,
                    bairro: document.getElementById("neighborhood").value,
                    acessivel: document.getElementById("acc_rampa").checked,
                    recursos_acessibilidade: [
                        document.getElementById("acc_rampa").checked ? "Rampa" : null,
                        document.getElementById("acc_libras").checked ? "Libras" : null,
                        document.getElementById("acc_banheiro").checked ? "Banheiro adaptado" : null,
                    ].filter(Boolean).join(", "),
                }),
            });

            const empreendedorData = await resEmpreendedor.json();
            if (!resEmpreendedor.ok) throw new Error(empreendedorData.message || "Erro ao cadastrar negócio");

            registerForm.style.display = "none";
            statusAlert.classList.remove("d-none");
            window.scrollTo({ top: 0, behavior: "smooth" });

        } catch (err) {
            alert("Erro: " + err.message);
        }
    });
}

    // --- ENVIO DO FORMULÁRIO DE LOGIN ---
    const loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        if (!loginForm.checkValidity()) {
            loginForm.classList.add("was-validated");
            return;
        }

        try {
            const res = await fetch("/api/login", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json" },
                body: JSON.stringify({
                    email: document.getElementById("login_email").value,
                    password: document.getElementById("login_password").value,
                }),
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.message || "Erro ao entrar");

            localStorage.setItem("auth_token", data.token);
            alert("Login realizado com sucesso!");
            
            const emailDigitado = document.getElementById("login_email").value;
            window.location.href = '/empreendedor/controle?email=' + emailDigitado;
            
        } catch (err) {
            alert("Erro: " + err.message);
        }
    });
}

});