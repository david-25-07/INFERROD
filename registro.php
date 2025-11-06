<?php

include_once 'session.php';
include_once 'config.php';
$conn = getConnection();

require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - TechHardware</title>
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
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #00ff41;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #00ff41;
        }

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

        .btn:hover {
            background: #00cc33;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            background: #00ff4120;
            border: 1px solid #00ff41;
            color: #00ff41;
        }

        .error {
            background: #ff353520;
            border: 1px solid #ff3535;
            color: #ff3535;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            color: #00ff41;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        #verificationStep {
            display: none;
        }

        .code-input {
            font-size: 24px;
            text-align: center;
            letter-spacing: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear Cuenta</h1>
        
        <!-- Paso 1: Registro -->
        <div id="registerStep">
            <div id="registerMessage"></div>
            <form onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" id="nombre" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label>Contrasena (mínimo 6 caracteres)</label>
                    <input type="password" id="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" id="confirmPassword" required minlength="6">
                </div>
                <button type="submit" class="btn">Crear Cuenta</button>
            </form>
            <div class="link">
                ¿Ya tienes cuenta? <a href="iniciar_sesion.php">Inicia Sesión</a>
            </div>
        </div>

        <!-- Paso 2: Verificación -->
        <div id="verificationStep">
            <div id="verifyMessage"></div>
            <p style="text-align: center; margin-bottom: 20px; color: #00ff41;">
                Te hemos enviado un código de verificación a tu email
            </p>
            <form onsubmit="handleVerification(event)">
                <div class="form-group">
                    <label>Código de Verificación (6 dígitos)</label>
                    <input type="text" id="code" class="code-input" maxlength="6" required pattern="[0-9]{6}">
                </div>
                <button type="submit" class="btn">Verificar</button>
            </form>
            <div class="link">
                <a href="#" onclick="resendCode()">Reenviar código</a>
            </div>
        </div>

        <div class="link" style="margin-top: 30px;">
            <a href="index.php">← Volver al inicio</a>
        </div>
    </div>

    <script>
        let userEmail = '';

        async function handleRegister(e) {
            e.preventDefault();
            
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            // Validar contraseñas
            if (password !== confirmPassword) {
                showMessage('registerMessage', 'Las contraseñas no coinciden', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('email', email);
            formData.append('password', password);
            
            try {
                const response = await fetch('process_register.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    userEmail = email;
                    showMessage('registerMessage', data.message, 'success');
                    setTimeout(() => {
                        document.getElementById('registerStep').style.display = 'none';
                        document.getElementById('verificationStep').style.display = 'block';
                    }, 1500);
                } else {
                    showMessage('registerMessage', data.message, 'error');
                }
            } catch (error) {
                showMessage('registerMessage', 'Error al registrar. Intenta de nuevo.', 'error');
            }
        }

        async function handleVerification(e) {
            e.preventDefault();
            
            const code = document.getElementById('code').value;
            
            const formData = new FormData();
            formData.append('email', userEmail);
            formData.append('code', code);
            
            try {
                const response = await fetch('verify_code.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage('verifyMessage', '¡Cuenta verificada! Redirigiendo...', 'success');
                    setTimeout(() => {
                        window.location.href = 'iniciar_sesion.php';
                    }, 2000);
                } else {
                    showMessage('verifyMessage', data.message, 'error');
                }
            } catch (error) {
                showMessage('verifyMessage', 'Error al verificar. Intenta de nuevo.', 'error');
            }
        }

        async function resendCode() {
            const formData = new FormData();
            formData.append('email', userEmail);
            
            try {
                const response = await fetch('resend_code.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage('verifyMessage', 'Código reenviado a tu email', 'success');
                } else {
                    showMessage('verifyMessage', data.message, 'error');
                }
            } catch (error) {
                showMessage('verifyMessage', 'Error al reenviar código', 'error');
            }
        }

        function showMessage(elementId, message, type) {
            document.getElementById(elementId).innerHTML = `<div class="message ${type}">${message}</div>`;
        }
    </script>
</body>
</html>