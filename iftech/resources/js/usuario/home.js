document.addEventListener('DOMContentLoaded', () => {

    const chatToggle = document.getElementById('chat-toggle');
    const chatClose = document.getElementById('chat-close');
    const chatWidget = document.getElementById('chat-widget');

    if (!chatToggle || !chatClose || !chatWidget) {
        return;
    }

    function abrirChat() {
        chatWidget.classList.add('active');
        chatWidget.setAttribute('aria-hidden', 'false');
    }

    function fecharChat() {
        chatWidget.classList.remove('active');
        chatWidget.setAttribute('aria-hidden', 'true');
    }

    chatToggle.addEventListener('click', () => {
        const aberto = chatWidget.classList.contains('active');

        if (aberto) {
            fecharChat();
        } else {
            abrirChat();
        }
    });

    chatClose.addEventListener('click', fecharChat);

});