document.addEventListener("DOMContentLoaded", () => {

    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");

    function realizarBusca() {

        const destino = searchInput.value.trim();

        if (destino === "") {

            searchInput.focus();

            searchInput.classList.add("input-error");

            setTimeout(() => {
                searchInput.classList.remove("input-error");
            }, 500);

            return;
        }

        console.log("Destino pesquisado:", destino);

        /*
         * Aqui você poderá futuramente direcionar
         * para a página de resultados do Laravel.
         *
         * Exemplo:
         *
         * window.location.href =
         * `/destinos?busca=${encodeURIComponent(destino)}`;
         */

        alert(`Buscando por: ${destino}`);
    }


    /* Clique no botão */

    searchButton.addEventListener("click", realizarBusca);


    /* Enter no input */

    searchInput.addEventListener("keydown", (event) => {

        if (event.key === "Enter") {
            event.preventDefault();

            realizarBusca();
        }

    });


    /* Remove o estado de erro ao digitar */

    searchInput.addEventListener("input", () => {
        searchInput.classList.remove("input-error");
    });

});