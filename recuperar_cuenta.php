<?php
session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Aquí deberías verificar si el email existe en tu base de datos
        // y enviar un correo con un token de recuperación
        
        // Por ahora, simulamos el proceso
        $message = '✅ Si el correo existe, recibirás instrucciones para recuperar tu cuenta.';
        $message_type = 'success';
        
        // TODO: Implementar lógica real:
        // 1. Verificar si el email existe en la BD
        // 2. Generar token único
        // 3. Guardar token en BD con expiración
        // 4. Enviar email con enlace de recuperación
    } else {
        $message = '❌ Por favor ingresa un correo válido.';
        $message_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recuperar Cuenta - INFERROD S.A</title>
<link rel="icon" type="image/png" href="img/logo.png">
<style>
    *{margin:0;padding:0;box-sizing:border-box;}
    :root{
        --primary:#00ff41;
        --secondary:#ff6b35;
        --dark:#0a0e27;
        --darker:#050818;
        --card-bg:#1a1f3a;
        --text:#e0e0e0;
        --glow:rgba(0,255,65,0.3);
    }
    body{
        font-family:'Courier New', monospace;
        background:var(--dark);
        color:var(--text);
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        position:relative;
    }
    .matrix-bg{position:fixed;top:0;left:0;width:100%;height:100%;z-index:-1;opacity:0.15;}
    .container{
        max-width:500px;
        width:90%;
        background:var(--card-bg);
        padding:40px;
        border-radius:15px;
        border:2px solid var(--primary);
        box-shadow:0 0 50px var(--glow);
    }
    .logo-container{text-align:center;margin-bottom:30px;}
    .logo{width:80px;height:auto;}
    h1{
        color:var(--primary);
        text-align:center;
        margin-bottom:10px;
        font-size:32px;
        text-shadow:0 0 20px var(--glow);
    }
    .subtitle{
        text-align:center;
        color:var(--secondary);
        margin-bottom:30px;
        font-size:14px;
    }
    .form-group{margin-bottom:25px;}
    label{
        display:block;
        margin-bottom:8px;
        color:var(--primary);
        font-weight:bold;
    }
    input{
        width:100%;
        padding:12px 15px;
        background:var(--darker);
        border:1px solid var(--primary);
        border-radius:8px;
        color:var(--text);
        font-family:inherit;
        font-size:14px;
    }
    input:focus{
        outline:none;
        box-shadow:0 0 15px var(--glow);
    }
    .btn{
        width:100%;
        padding:15px;
        background:var(--primary);
        color:var(--dark);
        border:none;
        border-radius:8px;
        font-weight:bold;
        font-size:16px;
        cursor:pointer;
        transition:all 0.3s;
        font-family:inherit;
    }
    .btn:hover{
        background:var(--secondary);
        box-shadow:0 0 25px rgba(255,107,53,0.5);
        transform:translateY(-2px);
    }
    .links{
        text-align:center;
        margin-top:25px;
    }
    .links a{
        color:var(--primary);
        text-decoration:none;
        transition:all 0.3s;
    }
    .links a:hover{
        color:var(--secondary);
        text-shadow:0 0 10px var(--glow);
    }
    .message{
        padding:15px;
        border-radius:8px;
        margin-bottom:20px;
        text-align:center;
        font-weight:bold;
    }
    .message.success{
        background:rgba(0,255,65,0.2);
        border:1px solid var(--primary);
        color:var(--primary);
    }
    .message.error{
        background:rgba(255,107,53,0.2);
        border:1px solid var(--secondary);
        color:var(--secondary);
    }
    .info-box{
        background:rgba(0,255,65,0.1);
        border:1px solid var(--primary);
        padding:15px;
        border-radius:8px;
        margin-bottom:25px;
        font-size:13px;
        line-height:1.6;
    }
    .info-box ul{
        margin-left:20px;
        margin-top:10px;
    }
    .info-box li{
        margin-bottom:8px;
    }
</style>
</head>
<body>
<div class="matrix-bg"><canvas id="matrixCanvas"></canvas></div>

<div class="container">
    <div class="logo-container">
        <img src="img/logo.png" alt="Logo" class="logo">
    </div>
    
    <h1>🔐 Recuperar Cuenta</h1>
    <p class="subtitle">Ingresa tu correo y te enviaremos instrucciones</p>
    
    <?php if($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="info-box">
        <strong>📋 Instrucciones:</strong>
        <ul>
            <li>Ingresa el correo registrado en tu cuenta</li>
            <li>Recibirás un enlace de recuperación válido por 1 hora</li>
            <li>Haz clic en el enlace y crea una nueva contraseña</li>
        </ul>
    </div>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="email">📧 Correo Electrónico</label>
            <input type="email" id="email" name="email" required 
                   placeholder="tu@email.com" autocomplete="email">
        </div>
        
        <button type="submit" class="btn">🚀 Enviar Enlace de Recuperación</button>
    </form>
    
    <div class="links">
        <p>¿Recordaste tu contraseña? <a href="iniciar_sesion.php">Iniciar Sesión</a></p>
        <p style="margin-top:10px;">¿No tienes cuenta? <a href="registro.php">Registrarse</a></p>
    </div>
</div>

<script>
function initMatrix(){
    const canvas=document.getElementById('matrixCanvas');
    const ctx=canvas.getContext('2d');
    canvas.width=window.innerWidth;
    canvas.height=window.innerHeight;
    const chars='01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
    const fontSize=14;
    const cols=Math.floor(canvas.width/fontSize);
    const drops=Array(cols).fill(1);
    
    setInterval(()=>{
        ctx.fillStyle='rgba(10,14,39,0.05)';
        ctx.fillRect(0,0,canvas.width,canvas.height);
        ctx.fillStyle='#00ff41';
        ctx.font=fontSize+'px monospace';
        for(let i=0;i<drops.length;i++){
            const t=chars.charAt(Math.floor(Math.random()*chars.length));
            ctx.fillText(t,i*fontSize,drops[i]*fontSize);
            if(drops[i]*fontSize>canvas.height&&Math.random()>0.975)drops[i]=0;
            drops[i]++;
        }
    },33);
    
    window.addEventListener('resize',()=>{
        canvas.width=window.innerWidth;
        canvas.height=window.innerHeight;
    });
}

initMatrix();
</script>
</body>
</html>