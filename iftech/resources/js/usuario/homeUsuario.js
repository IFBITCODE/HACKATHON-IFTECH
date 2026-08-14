document.addEventListener("DOMContentLoaded", () => {
    const searchForm = document.getElementById("searchForm");
    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const searchResult = document.getElementById("searchResult");
    const resultTag = document.getElementById("resultTag");
    const resultBody = document.getElementById("resultBody");

    if (!searchForm || !searchInput) return;

    searchForm.addEventListener("submit", async (e) => {
        e.preventDefault(); // Cancela o submit padrão para EVITAR o pop-up ou refresh

        const mensagem = searchInput.value.trim();
        if (!mensagem) return;

        // Animação de carregamento no botão e na tela
        searchButton.disabled = true;
        searchButton.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i>`;
        
        searchResult.classList.add("active");
        resultTag.textContent = "Consultando Guia...";
        resultBody.innerHTML = `
            <div class="loading-state">
                <i class="fa-solid fa-sparkles"></i> Buscando os melhores locais para você...
            </div>
        `;

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        try {
            const response = await fetch(searchForm.action, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ mensagem: mensagem })
            });

            const data = await response.json();

            if (response.ok && data.resposta) {
                resultTag.textContent = "Sugestões de Turismo";
                
                // Formata o texto recebido para destacar trechos de parceiros
                let textoFormatado = data.resposta
                    .replace(/\n/g, "<br>")
                    .replace(/(se voce for nesse que é nosso parceiro voce pode ganhar algumas moedas de troca)/gi, 
                             '<span class="badge-parceiro"><i class="fa-solid fa-coins"></i> $1</span>');

                resultBody.innerHTML = textoFormatado;
            } else {
                resultTag.textContent = "Aviso do Sistema";
                resultBody.innerHTML = `<div class="error-msg">${data.resposta || data.erro || "Não foi possível obter resposta no momento."}</div>`;
            }

        } catch (error) {
            console.error("Erro na busca:", error);
            resultTag.textContent = "Erro de Conexão";
            resultBody.innerHTML = `<div class="error-msg">Ocorreu um erro ao conectar ao servidor. Tente novamente.</div>`;
        } finally {
            searchButton.disabled = false;
            searchButton.innerHTML = `<i class="fa-solid fa-magnifying-glass"></i>`;
            searchResult.scrollIntoView({ behavior: "smooth", block: "nearest" });
        }
    });
});