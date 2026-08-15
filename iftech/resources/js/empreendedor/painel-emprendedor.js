/* =====================================================
   CSRF
   ===================================================== */
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* =====================================================
   LOGOUT
   ===================================================== */
const btnLogout = document.getElementById('btnLogout');

btnLogout?.addEventListener('click', async () => {
    const textoOriginal = btnLogout.innerHTML;
    try {
        btnLogout.disabled = true;
        btnLogout.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saindo...';

        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            throw new Error('Não foi possível sair da conta.');
        }
    } catch (error) {
        console.error('Erro ao sair:', error);
    } finally {
        localStorage.removeItem('auth_token');
        window.location.href = '/login-empreendedor';
    }
});

/* =====================================================
   ELEMENTOS DA FILA ROTATIVA
   ===================================================== */
const btnAtualizarFila = document.getElementById('btnAtualizarFila');
const quantidadeMoedas = document.getElementById('quantidadeMoedas');
const codigoGerado = document.getElementById('codigoGerado');
const badgeMoedas = document.getElementById('badgeMoedas');
const btnCopiarCodigo = document.getElementById('btnCopiarCodigo');

/* =====================================================
   ATUALIZAR VALOR DA FILA
   ===================================================== */
btnAtualizarFila?.addEventListener('click', async () => {
    const textoOriginal = btnAtualizarFila.innerHTML;

    try {
        const moedas = Number(quantidadeMoedas.value);

        if (!Number.isInteger(moedas) || moedas < 1 || moedas > 10000) {
            alert('Informe uma quantidade de moedas válida (entre 1 e 10.000).');
            quantidadeMoedas.focus();
            return;
        }

        btnAtualizarFila.disabled = true;
        btnAtualizarFila.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        const response = await fetch('/empreendedor/fila/atualizar', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ moedas: moedas })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Erro ao atualizar a fila.');
        }

        // Atualiza os dados na tela visualmente
        codigoGerado.textContent = data.codigo;
        badgeMoedas.innerHTML = `<i class="bi bi-coin"></i> ${data.moedas} moedas`;

        // Feedback de sucesso visual no botão
        btnAtualizarFila.classList.replace('btn-warning', 'btn-success');
        btnAtualizarFila.innerHTML = '<i class="bi bi-check-lg"></i> Salvo!';

        setTimeout(() => {
            btnAtualizarFila.classList.replace('btn-success', 'btn-warning');
            btnAtualizarFila.innerHTML = textoOriginal;
        }, 2000);

    } catch (error) {
        console.error('Erro ao atualizar fila:', error);
        alert(error.message);
        btnAtualizarFila.innerHTML = textoOriginal;
    } finally {
        btnAtualizarFila.disabled = false;
    }
});

/* =====================================================
   COPIAR CÓDIGO ATUAL
   ===================================================== */
btnCopiarCodigo?.addEventListener('click', async () => {
    try {
        await navigator.clipboard.writeText(codigoGerado.textContent);

        const conteudoAntigo = btnCopiarCodigo.innerHTML;
        btnCopiarCodigo.innerHTML = '<i class="bi bi-check me-1"></i>Copiado!';

        setTimeout(() => {
            btnCopiarCodigo.innerHTML = conteudoAntigo;
        }, 2000);
    } catch (error) {
        alert('Não foi possível copiar o código.');
    }
});