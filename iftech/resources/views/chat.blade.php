<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Primeiro Chatbot</title>
</head>
<body style="font-family: sans-serif; max-width: 600px; margin: 40px auto;">

    <h1>Chatbot do Projeto</h1>

    <!-- Área onde as mensagens aparecem (só aparece se o bot tiver respondido algo) -->
    @if(isset($resposta))
        <div style="background-color: #f1f1f1; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <p><strong>Você:</strong> {{ $mensagemUser }}</p>
            <p><strong>Bot:</strong> {{ $resposta }}</p>
        </div>
    @endif

    <!-- Formulário para digitar a mensagem -->
    <form action="/chat" method="POST">
        @csrf <!-- Diretiva de segurança obrigatória do Laravel -->
        
        <input type="text" name="mensagem" placeholder="Digite sua mensagem aqui..." required style="width: 70%; padding: 10px;">
        <button type="submit" style="padding: 10px 20px;">Enviar</button>
    </form>

</body>
</html>