<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de E-mail</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f9f9f9; padding:30px; }
        .container { max-width:500px; margin:0 auto; background:#fff; padding:30px; border-radius:8px; text-align:center; }
        button { background:#007bff; border:none; padding:12px 20px; border-radius:5px; color:#fff; font-size:16px; cursor:pointer; }
        button:hover { background:#0056b3; }
        .msg { margin-bottom:15px; color:#555; }
        .success { color:green; margin-bottom:15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verifique seu E-mail</h2>
        <p class="msg">
            Obrigado por se registrar!  
            Antes de começar, confirme seu e-mail clicando no link que enviamos para você.  
            Se não recebeu, clique abaixo para reenviar.
        </p>

        @if (session('message'))
            <p class="success">{{ session('message') }}</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">Reenviar e-mail de verificação</button>
        </form>
    </div>
</body>
</html>
