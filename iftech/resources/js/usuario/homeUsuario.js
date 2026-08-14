document.addEventListener("DOMContentLoaded", () => {

    const searchInput = document.getElementById("searchInput");

    if (!searchInput) {
        return;
    }

    /* Remove erro quando o usuário começa a digitar */

    searchInput.addEventListener("input", () => {
        searchInput.classList.remove("input-error");
    });

});