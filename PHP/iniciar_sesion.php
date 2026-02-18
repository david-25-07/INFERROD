<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Inferrod</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            background: #0a0e27;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: #1a1f3a;
            border: 2px solid #00ff41;
            border-radius: 10px;
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        h1 { color: #00ff41; margin-bottom: 30px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #00ff41; }
        input {
            width: 100%;
            padding: 12px;
            background: #0a0e27;
            border: 1px solid #00ff41;
            color: #e0e0e0;
            border-radius: 5px;
            font-family: inherit;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #00ff41;
            color: #0a0e27;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 5px;
            font-family: inherit;
            font-size: 16px;
        }
        .btn:hover { background: #00cc33; }
        .message { padding: 12px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .success { background: #00ff4120; border: 1px solid #00ff41; color: #00ff41; }
        .error { background: #ff353520; border: 1px solid #ff3535; color: #ff3535; }
        .link { text-align: center; margin-top: 20px; }
        .link a { color: #00ff41; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <div id="loginMessage"></div>
        <form onsubmit="handleLogin(event)">
            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" id="email" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" id="password" required>
            </div>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
        <div class="link">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>
        <div class="link" style="margin-top: 30px;">
            <a href="index.php">← Volver al inicio</a>
        </div>
    </div>

    <script>
        async function handleLogin(e) {
            e.preventDefault();

            const correo = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            const formData = new FormData();
            formData.append('correo', correo);
            formData.append('password', password);

            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });

                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch {
                    console.error('Respuesta inválida del servidor:', text);
                    showMessage('loginMessage', 'Error en la respuesta del servidor.', 'error');
                    return;
                }

                if (data.success) {
                    showMessage('loginMessage', '¡Bienvenido! Redirigiendo...', 'success');
                    setTimeout(() => {
                        if (data.role === 'admin') window.location.href = 'admin_panel.php';
                        else if (data.role === 'vendedor') window.location.href = 'seller_panel.php';
                        else window.location.href = 'index.php';
                    }, 1000);
                } else {
                    showMessage('loginMessage', data.message, 'error');
                }

            } catch (error) {
                console.error(error);
                showMessage('loginMessage', 'Error al conectar con el servidor.', 'error');
            }
        }

        function showMessage(elementId, message, type) {
            document.getElementById(elementId).innerHTML =
                `<div class="message ${type}">${message}</div>`;
        }
    </script>
</body>
</html>
