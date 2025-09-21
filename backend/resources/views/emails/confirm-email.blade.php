<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmação de E-mail - FinanceUni</title>
</head>
<body>
    <center>
        <img src="{{ asset('images/logo_financeuni.png') }}" alt="FinanceUni" width="120">
    </center>

    <h2>Olá, {{ $userName }}!</h2>

    <p>Obrigado por se cadastrar no <strong>FinanceUni</strong>.</p>
    <p>Para finalizar seu cadastro, confirme seu e-mail clicando no link abaixo:</p>

    <p>
        <a href="{{ $verificationUrl }}" style="background-color:#34d399;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">
            Confirmar E-mail
        </a>
    </p>

    <p>Se você não se cadastrou, apenas ignore este e-mail.</p>

    <p>Obrigado,<br>
    <strong>Equipe FinanceUni</strong></p>
</body>
</html>
