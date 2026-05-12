<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Depósito confirmado</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f4f4f4; padding:20px;">

    <div style="max-width:600px;margin:auto;background:white;padding:20px;border-radius:10px;">

        <h2 style="color:#28a745;">✔ Depósito confirmado</h2>

        <p>Hola 👋,</p>

        <p>Hemos recibido correctamente tu depósito en la plataforma.</p>

        <div style="background:#f8f9fa;padding:15px;border-radius:8px;margin:20px 0;">
            <p><strong>Monto ingresado:</strong> {{ $monto }} €</p>
            <p><strong>Nuevo saldo:</strong> {{ $saldo }} €</p>
        </div>

        <p>Ya puedes seguir comprando skins en la tienda 🎮</p>

        <hr>

        <p style="font-size:12px;color:#888;">
            Este es un mensaje automático, no respondas a este correo.
        </p>

    </div>

</body>
</html>