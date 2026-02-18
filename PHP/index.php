<?php
session_start();

// Verificar si el usuario está logueado
$logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Si NO está logueado, mostrar solo la página de bloqueo
if (!$logged_in) {
    // Si la petición es AJAX (por ejemplo, fetch), devolver JSON de error
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Sesión expirada', 'redirect' => 'iniciar_sesion.php']);
        exit();
    }
    // Si es POST (fetch sin X-Requested-With), devolver JSON también
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Sesión expirada', 'redirect' => 'iniciar_sesion.php']);
        exit();
    }
    // Si es acceso normal, mostrar la página de bloqueo
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFERROD S.A - Acceso Restringido</title>
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
            overflow:hidden;
            position:relative;
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .matrix-bg{
            position:fixed; 
            top:0; 
            left:0; 
            width:100%; 
            height:100%; 
            z-index:-1; 
            opacity:0.2;
        }
        #loginOverlay{
            position:relative;
            z-index:10;
            text-align:center;
            padding:40px;
            background:rgba(26,31,58,0.9);
            border:2px solid var(--primary);
            border-radius:20px;
            max-width:600px;
            backdrop-filter:blur(10px);
            box-shadow:0 0 50px var(--glow);
        }
        #loginOverlay h1{
            font-size:48px; 
            margin-bottom:20px; 
            color:var(--primary);
            text-shadow:0 0 20px var(--glow);
            animation:pulse 2s infinite;
        }
        @keyframes pulse{
            0%, 100%{text-shadow:0 0 20px var(--glow);}
            50%{text-shadow:0 0 40px var(--glow), 0 0 60px var(--glow);}
        }
        #loginOverlay .lock-icon{
            font-size:80px;
            margin-bottom:20px;
            animation:bounce 2s infinite;
        }
        @keyframes bounce{
            0%, 100%{transform:translateY(0);}
            50%{transform:translateY(-10px);}
        }
        #loginOverlay p{
            font-size:18px; 
            margin-bottom:30px; 
            color:var(--secondary);
            line-height:1.6;
        }
        #loginOverlay .btn{
            display:inline-block;
            font-size:18px; 
            padding:15px 40px; 
            background:var(--primary);
            color:var(--dark);
            border:none;
            border-radius:10px;
            text-decoration:none;
            font-weight:bold;
            font-family:inherit;
            cursor:pointer;
            transition:all 0.3s;
            box-shadow:0 5px 20px var(--glow);
        }
        #loginOverlay .btn:hover{
            transform:translateY(-3px);
            box-shadow:0 8px 30px var(--glow);
            background:var(--secondary);
        }
        #loginOverlay .divider{
            margin:20px 0;
            color:var(--text);
            font-size:14px;
        }
    </style>
    </head>
    <body>
    <div class="matrix-bg"><canvas id="matrixCanvas"></canvas></div>

    <div id="loginOverlay">
        <div class="lock-icon">🔒</div>
        <h1>Acceso Restringido</h1>
        <p>Esta página es exclusiva para miembros registrados de INFERROD S.A.</p>
        <p>Por favor, inicia sesión para acceder al catálogo de productos y realizar compras.</p>
        <a href="iniciar_sesion.php" class="btn">🚀 Iniciar Sesión</a>
        <div class="divider">- o -</div>
        <a href="registro.php" class="btn" style="background:transparent; border:2px solid var(--primary); color:var(--primary);">📝 Registrarse</a>
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
    <?php
    exit();
}

// Si está logueado, obtener datos del usuario
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuario';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

// Inicializar carrito si no existe
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Manejar acciones del carrito
if(isset($_POST['action'])){
    if($_POST['action'] === 'add' && isset($_POST['product_id'])){
        $product_id = intval($_POST['product_id']);
        if(isset($_SESSION['cart'][$product_id])){
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        echo json_encode(['success' => true, 'count' => array_sum($_SESSION['cart'])]);
        exit();
    }
}

$cart_count = array_sum($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>INFERROD S.A</title>
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
    body{font-family:'Courier New', monospace; background:var(--dark); color:var(--text); overflow-x:hidden;}
    .matrix-bg{position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1; opacity:0.15; pointer-events:none;}
    header{background:linear-gradient(135deg,var(--darker) 0%, var(--card-bg) 100%); padding:20px 0; box-shadow:0 4px 20px rgba(0,255,65,0.2); position:sticky; top:0; z-index:1000; border-bottom:2px solid var(--primary);}
    nav{max-width:1400px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;}
    .logo{width:70px; height:auto;}
    .nav-links{display:flex; gap:30px; list-style:none;}
    .nav-links a{color:var(--text); text-decoration:none; padding:8px 15px; border:1px solid transparent; transition:all 0.3s;}
    .nav-links a:hover{color:var(--primary); border-color:var(--primary); box-shadow:0 0 15px var(--glow);}
    .user-section{display:flex; align-items:center; gap:20px; position:relative;}
    .user-info{color:var(--primary); display:flex; align-items:center; gap:10px;}
    .user-role{background:var(--secondary); color:white; padding:4px 12px; border-radius:15px; font-size:12px; font-weight:bold;}
    .cart-icon{position:relative; cursor:pointer; font-size:24px; color:var(--secondary); transition:transform 0.3s;}
    .cart-icon:hover{transform:scale(1.1);}
    .cart-count{position:absolute; top:-10px; right:-10px; background:var(--primary); color:var(--dark); border-radius:50%; width:20px; height:20px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold;}
    .container{max-width:1400px; margin:0 auto; padding:40px 20px;}
    .hero{text-align:center; padding:80px 20px; background:linear-gradient(135deg,var(--card-bg) 0%, var(--darker) 100%); border-radius:15px; margin-bottom:50px; position:relative; overflow:hidden;}
    .hero::before{content:''; position:absolute; top:0; left:-100%; width:100%; height:100%; background:linear-gradient(90deg, transparent, rgba(0,255,65,0.1), transparent); animation:scan 3s infinite;}
    @keyframes scan{0%{left:-100%;}100%{left:200%;}}
    .hero h1{font-size:48px; color:var(--primary); margin-bottom:20px; text-shadow:0 0 30px var(--glow);}
    .hero p{font-size:20px; color:var(--secondary);}
    .welcome-message{background:var(--card-bg); padding:20px; border-radius:10px; margin-bottom:30px; border:1px solid var(--primary); text-align:center;}
    .welcome-message h2{color:var(--primary); margin-bottom:10px;}
    .quick-actions{display:flex; justify-content:center; gap:20px; margin-bottom:30px; flex-wrap:wrap;}
    .quick-action-btn{padding:15px 30px; background:var(--card-bg); border:2px solid var(--primary); border-radius:10px; color:var(--primary); text-decoration:none; font-weight:bold; transition:all 0.3s; display:flex; align-items:center; gap:10px;}
    .quick-action-btn:hover{background:var(--primary); color:var(--dark); box-shadow:0 0 20px var(--glow); transform:translateY(-3px);}
    .products-grid{display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:30px;}
    .product-card{background:var(--card-bg); border:1px solid var(--primary); border-radius:10px; padding:20px; transition:all 0.3s;}
    .product-card:hover{transform:translateY(-10px); box-shadow:0 10px 30px var(--glow);}
    .product-img{width:100%; height:200px; background:var(--darker); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:60px; margin-bottom:15px;}
    .product-name{font-size:18px; color:var(--primary); margin-bottom:10px;}
    .product-price{font-size:24px; color:var(--secondary); margin-bottom:15px; font-weight:bold;}
    .btn{padding:12px 25px; background:var(--primary); color:var(--dark); border:none; cursor:pointer; font-weight:bold; transition:all 0.3s; border-radius:5px; font-family:inherit;}
    .btn:hover{background:var(--secondary); box-shadow:0 0 20px rgba(255,107,53,0.5);}
    .menu-toggle{cursor:pointer; padding:10px 15px; background:var(--card-bg); border:1px solid var(--primary); border-radius:5px; transition:all 0.3s; font-size:20px;}
    .menu-toggle:hover{background:var(--primary); color:var(--dark); box-shadow:0 0 15px var(--glow);}
    .dropdown-menu{display:none; position:absolute; top:calc(100% + 10px); right:0; background:var(--card-bg); border:2px solid var(--primary); border-radius:10px; padding:15px; min-width:220px; box-shadow:0 8px 25px rgba(0,255,65,0.3); z-index:2000;}
    .dropdown-menu.active{display:block; animation:slideDown 0.3s ease;}
    @keyframes slideDown{from{opacity:0; transform:translateY(-10px);} to{opacity:1; transform:translateY(0);}}
    .dropdown-menu button{display:block; width:100%; padding:12px 15px; margin:8px 0; background:var(--darker); color:var(--text); border:1px solid var(--primary); border-radius:6px; cursor:pointer; text-align:left; font-size:14px; transition:all 0.3s; font-family:inherit;}
    .dropdown-menu button:hover{background:var(--primary); color:var(--dark); transform:translateX(5px); box-shadow:0 0 15px var(--glow);}
    .dropdown-menu form{margin:0;}
    .dropdown-menu form button{background:var(--secondary); border-color:var(--secondary); color:white; font-weight:bold;}
    .dropdown-menu form button:hover{background:#ff4520; border-color:#ff4520;}
    footer{background:var(--darker); padding:40px 20px; margin-top:60px; border-top:2px solid var(--primary);}
    footer h3{color:var(--primary); margin-bottom:15px;}
    footer input, footer textarea{width:100%; padding:10px; margin:8px 0; background:var(--card-bg); border:1px solid var(--primary); border-radius:5px; color:var(--text); font-family:inherit;}
    footer input:focus, footer textarea:focus{outline:none; box-shadow:0 0 10px var(--glow);}
    footer p{margin:8px 0; color:var(--text);}
    .notification{position:fixed; top:20px; right:20px; background:var(--primary); color:var(--dark); padding:20px 30px; border-radius:8px; font-weight:bold; z-index:9999; animation:slideIn 0.3s ease; box-shadow:0 5px 20px var(--glow);}
    @keyframes slideIn{from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
    
    /* ESTILOS DEL CHATBOT */
    .chatbot-container{position:fixed;bottom:20px;right:20px;z-index:10000;font-family:'Courier New',monospace;}
    .chat-button{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));border:none;cursor:pointer;box-shadow:0 5px 25px var(--glow);display:flex;align-items:center;justify-content:center;font-size:28px;transition:all 0.3s;animation:chatPulse 2s infinite;}
    @keyframes chatPulse{0%,100%{box-shadow:0 5px 25px var(--glow);}50%{box-shadow:0 5px 40px var(--glow),0 0 60px var(--glow);}}
    .chat-button:hover{transform:scale(1.1);}
    .chat-window{position:absolute;bottom:80px;right:0;width:380px;height:550px;background:var(--card-bg);border:2px solid var(--primary);border-radius:20px;box-shadow:0 10px 50px var(--glow);display:none;flex-direction:column;overflow:hidden;animation:slideUp 0.3s ease;}
    @keyframes slideUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
    .chat-window.active{display:flex;}
    .chat-header{background:linear-gradient(135deg,var(--darker),var(--card-bg));padding:20px;border-bottom:2px solid var(--primary);display:flex;justify-content:space-between;align-items:center;}
    .chat-header h3{color:var(--primary);margin:0;font-size:18px;text-shadow:0 0 10px var(--glow);}
    .close-chat{background:transparent;border:none;color:var(--secondary);font-size:24px;cursor:pointer;transition:all 0.3s;}
    .close-chat:hover{color:var(--primary);transform:rotate(90deg);}
    .chat-messages{flex:1;padding:20px;overflow-y:auto;background:var(--darker);}
    .chat-messages::-webkit-scrollbar{width:8px;}
    .chat-messages::-webkit-scrollbar-track{background:var(--darker);}
    .chat-messages::-webkit-scrollbar-thumb{background:var(--primary);border-radius:4px;}
    .message{margin-bottom:15px;animation:fadeIn 0.3s ease;}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}
    .message.bot{display:flex;gap:10px;}
    .message.user{display:flex;justify-content:flex-end;}
    .bot-avatar{width:35px;height:35px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
    .message-content{max-width:75%;padding:12px 15px;border-radius:15px;line-height:1.5;font-size:14px;}
    .bot .message-content{background:var(--card-bg);border:1px solid var(--primary);color:var(--text);}
    .user .message-content{background:var(--primary);color:var(--dark);font-weight:bold;}
    .quick-questions{padding:15px 20px;border-top:1px solid var(--primary);background:var(--card-bg);}
    .quick-questions-title{color:var(--primary);font-size:12px;margin-bottom:10px;font-weight:bold;}
    .question-chips{display:flex;flex-wrap:wrap;gap:8px;}
    .question-chip{padding:8px 12px;background:var(--darker);border:1px solid var(--primary);border-radius:20px;font-size:12px;cursor:pointer;transition:all 0.3s;color:var(--text);}
    .question-chip:hover{background:var(--primary);color:var(--dark);box-shadow:0 0 15px var(--glow);}
    .chat-input-container{padding:15px 20px;border-top:2px solid var(--primary);background:var(--card-bg);display:flex;gap:10px;}
    .chat-input{flex:1;padding:12px 15px;background:var(--darker);border:1px solid var(--primary);border-radius:25px;color:var(--text);font-family:inherit;font-size:14px;}
    .chat-input:focus{outline:none;box-shadow:0 0 15px var(--glow);}
    .send-button{width:45px;height:45px;background:var(--primary);border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:20px;transition:all 0.3s;}
    .send-button:hover{background:var(--secondary);transform:scale(1.1);}
    .typing-indicator{display:flex;gap:4px;padding:12px 15px;background:var(--card-bg);border:1px solid var(--primary);border-radius:15px;width:fit-content;}
    .typing-dot{width:8px;height:8px;background:var(--primary);border-radius:50%;animation:typing 1.4s infinite;}
    .typing-dot:nth-child(2){animation-delay:0.2s;}
    .typing-dot:nth-child(3){animation-delay:0.4s;}
    @keyframes typing{0%,60%,100%{transform:translateY(0);}30%{transform:translateY(-10px);}}
    
    @media (max-width: 768px){
        nav{flex-direction:column; gap:15px;}
        .nav-links{flex-direction:column; width:100%; text-align:center;}
        .hero h1{font-size:32px;}
        .quick-actions{flex-direction:column;}
        .chat-window{width:calc(100vw - 40px);height:calc(100vh - 100px);}
    }
</style>
</head>
<body>
<div class="matrix-bg"><canvas id="matrixCanvas"></canvas></div>

<header>
    <nav>
        <a href="index.php"><img src="img/ChatGPT Image 20 oct 2025, 19_00_18.png" alt="Logo" class="logo"></a>
        <ul class="nav-links">
            <li><a href="#productos">Productos</a></li>
            <li><a href="pedidos.php">📦 Mis Pedidos</a></li>
            <li><a href="#contacto">Contacto</a></li>
        </ul>
        <div class="user-section">
            <?php if($logged_in): ?>
                <span class="user-info">
                    👤 <?php echo htmlspecialchars($user_name); ?>
                    <?php if($user_role): ?>
                        <span class="user-role"><?php echo htmlspecialchars(ucfirst($user_role)); ?></span>
                    <?php endif; ?>
                </span>
                <div class="menu-toggle" onclick="toggleMenu()">☰</div>
                <div id="userMenu" class="dropdown-menu">
                    <button onclick="openProfile()">🧑 Ver Perfil</button>
                    <?php if($user_role === 'vendedor'): ?>
                        <button onclick="window.location.href='seller_panel.php'">📊 Panel de Vendedor</button>
                    <?php endif; ?>
                    <button onclick="window.location.href='pedidos.php'">📦 Mis Pedidos</button>
                    <button onclick="customizeAccount()">⚙️ Personalizar Cuenta</button>
                    <form method="post" action="logout.php" onsubmit="return confirmLogout(event)">
                        <button type="submit">🚪 Cerrar Sesión</button>
                    </form>
                </div>
                <div class="cart-icon" onclick="window.location.href='carrito.php'">
                    🛒<span class="cart-count" id="cartCount"><?php echo $cart_count; ?></span>
                </div>
            <?php else: ?>
                <a href="iniciar_sesion.php" class="btn">🚀 Iniciar Sesión</a>
                <a href="registro.php" class="btn" style="background:transparent; border:2px solid var(--primary); color:var(--primary);">📝 Registrarse</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<div class="container">
    <div class="welcome-message">
        <h2>¡Bienvenido, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
        <p>Accediste como: <strong><?php echo htmlspecialchars(ucfirst($user_role)); ?></strong></p>
    </div>

    <div class="quick-actions">
        <a href="carrito.php" class="quick-action-btn">
            🛒 Ver Carrito (<?php echo $cart_count; ?>)
        </a>
        <a href="pedidos.php" class="quick-action-btn">
            📦 Mis Pedidos
        </a>
        <a href="#contacto" class="quick-action-btn">
            📞 Contacto
        </a>
    </div>

    <div class="hero">
        <h1>Ferretería INFERROD</h1>
        <p>Herramientas de última generación para profesionales</p>
    </div>

    <div id="productos" class="products-grid"></div>
</div>

<footer id="contacto">
    <div style="max-width:1400px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:40px; margin-bottom:30px;">
        <div>
            <h3>📧 Contacto</h3>
            <form onsubmit="sendContact(event)">
                <input type="email" placeholder="Tu Email" required>
                <textarea placeholder="Tu Mensaje" rows="4" required></textarea>
                <button type="submit" class="btn" style="width:100%;">Enviar</button>
            </form>
        </div>
        <div>
            <h3>ℹ️ Información</h3>
            <p>📍 Dirección: Calle Principal #123</p>
            <p>📞 Teléfono: +57 300 123 4567</p>
            <p>📧 Email: info@inferrod.com</p>
            <p>🕐 Horario: Lun-Vie 8AM-6PM</p>
            <p>🗓️ Sábados: 9AM-2PM</p>
        </div>
    </div>
    <div style="text-align:center; color:var(--primary); padding-top:20px; border-top:1px solid var(--primary);">
        © 2025 INFERROD S.A. Todos los derechos reservados.
    </div>
</footer>

<!-- CHATBOT -->
<div class="chatbot-container">
    <button class="chat-button" onclick="toggleChatbot()">💬</button>
    
    <div class="chat-window" id="chatbotWindow">
        <div class="chat-header">
            <h3>🤖 Asistente INFERROD</h3>
            <button class="close-chat" onclick="toggleChatbot()">✕</button>
        </div>
        
        <div class="chat-messages" id="chatbotMessages">
            <div class="message bot">
                <div class="bot-avatar">🤖</div>
                <div class="message-content">
                    ¡Hola! 👋 Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?
                </div>
            </div>
        </div>
        
        <div class="quick-questions">
            <div class="quick-questions-title">💡 PREGUNTAS FRECUENTES:</div>
            <div class="question-chips">
                <div class="question-chip" onclick="askChatbotQuestion('horarios')">🕐 Horarios</div>
                <div class="question-chip" onclick="askChatbotQuestion('envios')">📦 Envíos</div>
                <div class="question-chip" onclick="askChatbotQuestion('pagos')">💳 Pagos</div>
                <div class="question-chip" onclick="askChatbotQuestion('garantia')">✅ Garantía</div>
                <div class="question-chip" onclick="askChatbotQuestion('contacto')">📞 Contacto</div>
            </div>
        </div>
        
        <div class="chat-input-container">
            <input type="text" class="chat-input" id="chatbotInput" 
                   placeholder="Escribe tu pregunta..." 
                   onkeypress="handleChatbotKeyPress(event)">
            <button class="send-button" onclick="sendChatbotMessage()">🚀</button>
        </div>
    </div>
</div>

<script>
// PRODUCTOS
const products = [
    {id:1,name:'Martillo Profesional',price:45000,category:'herramientas',icon:'🔨'},
    {id:2,name:'Taladro Eléctrico',price:320000,category:'herramientas',icon:'🔧'},
    {id:3,name:'Cable Eléctrico 10m',price:28000,category:'electricidad',icon:'⚡'},
    {id:4,name:'Sierra Eléctrica',price:420000,category:'herramientas',icon:'🪚'},
    {id:5,name:'Destornillador Set',price:35000,category:'herramientas',icon:'🔩'},
    {id:6,name:'Cinta Métrica 5m',price:15000,category:'medicion',icon:'📏'}
];

function renderProducts(){
    const grid=document.querySelector('#productos');
    grid.innerHTML = products.map(p=>`
        <div class="product-card">
            <div class="product-img">${p.icon}</div>
            <div class="product-name">${p.name}</div>
            <div class="product-price">$${p.price.toLocaleString('es-CO')}</div>
            <button class="btn" onclick="addToCart(${p.id})">Agregar al carrito</button>
        </div>
    `).join('');
}

function addToCart(productId){
    fetch('index.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
        body: `action=add&product_id=${productId}`
    })
    .then(res => {
        // Si la respuesta no es JSON, mostrar error genérico
        return res.json().catch(() => ({success: false, error: 'Error inesperado'}));
    })
    .then(data => {
        if(data.success){
            document.getElementById('cartCount').textContent = data.count;
            showNotification('Producto agregado al carrito');
        } else if(data.error && data.redirect){
            showNotification('Sesión expirada, redirigiendo...');
            setTimeout(()=>{
                window.location.href = data.redirect;
            }, 1200);
        } else {
            showNotification(data.error || 'Error al agregar al carrito');
        }
    });
}

function showNotification(msg){
    const notif = document.createElement('div');
    notif.className = 'notification';
    notif.textContent = msg;
    document.body.appendChild(notif);
    setTimeout(()=>notif.remove(), 2000);
}

function confirmLogout(e){
    if(!confirm('¿Seguro que deseas cerrar sesión?')){
        e.preventDefault();
        return false;
    }
    return true;
}

function openProfile(){
    window.location.href = 'perfil.php';
}

function toggleMenu(){
    var menu = document.getElementById('userMenu');
    if(menu){
        menu.classList.toggle('active');
    }
}

// Cerrar el menú si se hace clic fuera
window.addEventListener('click', function(e){
    var menu = document.getElementById('userMenu');
    var toggle = document.querySelector('.menu-toggle');
    if(menu && !menu.contains(e.target) && !toggle.contains(e.target)){
        menu.classList.remove('active');
    }
});

renderProducts();

function toggleChatbot(){
    var win = document.getElementById('chatbotWindow');
    if(win){
        win.classList.toggle('active');
    }
}

function sendChatbotMessage(){
    var input = document.getElementById('chatbotInput');
    var msg = input.value.trim();
    if(!msg) return;
    appendChatMessage('user', msg);
    input.value = '';
    setTimeout(()=>{
        botReply(msg);
    }, 600);
}

function askChatbotQuestion(type){
    let questions = {
        'horarios': '¿Cuáles son los horarios de atención?',
        'envios': '¿Cómo funcionan los envíos?',
        'pagos': '¿Qué métodos de pago aceptan?',
        'garantia': '¿Tienen garantía los productos?',
        'contacto': '¿Cómo puedo contactar a INFERROD?'
    };
    var q = questions[type] || type;
    document.getElementById('chatbotInput').value = q;
    sendChatbotMessage();
}

function handleChatbotKeyPress(e){
    if(e.key === 'Enter') sendChatbotMessage();
}

function appendChatMessage(sender, text){
    var messages = document.getElementById('chatbotMessages');
    var div = document.createElement('div');
    div.className = 'message ' + sender;
    if(sender === 'bot'){
        div.innerHTML = '<div class="bot-avatar">🤖</div><div class="message-content">'+text+'</div>';
    }else{
        div.innerHTML = '<div class="message-content">'+text+'</div>';
    }
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
}

function botReply(msg){
    msg = msg.toLowerCase();
    let reply = '';
    if(msg.includes('horario')) reply = 'Nuestro horario es Lun-Vie 8AM-6PM y Sábados 9AM-2PM.';
    else if(msg.includes('envio')) reply = 'Realizamos envíos a todo el país. Tiempo estimado: 2-5 días hábiles.';
    else if(msg.includes('pago')) reply = 'Aceptamos pagos con tarjeta, transferencia y efectivo en tienda.';
    else if(msg.includes('garant')) reply = 'Todos los productos tienen garantía de 1 año.';
    else if(msg.includes('contact')) reply = 'Puedes contactarnos al correo info@inferrod.com o al +57 300 123 4567.';
    else reply = 'No entendí tu pregunta, ¿puedes reformularla?';
    appendChatMessage('bot', reply);
}
</script>
</body>
</html>
