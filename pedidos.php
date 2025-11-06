<?php
session_start();

// Verificar login
if (!isset($_SESSION['user_id'])) {
    // Si la petición es AJAX (fetch) o POST, responde JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success'=>false, 'error'=>'Sesión expirada', 'redirect'=>'iniciar_sesion.php']);
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success'=>false, 'error'=>'Sesión expirada', 'redirect'=>'iniciar_sesion.php']);
        exit();
    }
    header('Location: iniciar_sesion.php');
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Usuario';
$success_message = isset($_GET['success']) ? true : false;

// Simulación de pedidos (en producción, obtener de base de datos)
$orders = [
    [
        'id' => 'ORD-2025-001',
        'date' => '2025-10-28',
        'status' => 'entregado',
        'total' => 365000,
        'items' => [
            ['name'=>'Martillo Profesional','qty'=>2,'price'=>45000],
            ['name'=>'Taladro Eléctrico','qty'=>1,'price'=>275000]
        ]
    ],
    [
        'id' => 'ORD-2025-002',
        'date' => '2025-10-29',
        'status' => 'en_transito',
        'total' => 520000,
        'items' => [
            ['name'=>'Sierra Eléctrica','qty'=>1,'price'=>420000],
            ['name'=>'Guantes Industriales','qty'=>5,'price'=>20000]
        ]
    ],
    [
        'id' => 'ORD-2025-003',
        'date' => '2025-10-30',
        'status' => 'procesando',
        'total' => 145000,
        'items' => [
            ['name'=>'Cable Eléctrico 10m','qty'=>3,'price'=>28000],
            ['name'=>'Cinta Métrica','qty'=>1,'price'=>33000]
        ]
    ]
];

function getStatusBadge($status){
    $badges = [
        'procesando' => ['icon'=>'⏳', 'text'=>'Procesando', 'color'=>'#ff6b35'],
        'en_transito' => ['icon'=>'🚚', 'text'=>'En Tránsito', 'color'=>'#00a8ff'],
        'entregado' => ['icon'=>'✅', 'text'=>'Entregado', 'color'=>'#00ff41']
    ];
    return $badges[$status] ?? $badges['procesando'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis Pedidos - INFERROD S.A</title>
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
    body{font-family:'Courier New',monospace;background:var(--dark);color:var(--text);overflow-x:hidden;}
    .matrix-bg{position:fixed;top:0;left:0;width:100%;height:100%;z-index:-1;opacity:0.15;pointer-events:none;}
    header{background:linear-gradient(135deg,var(--darker) 0%,var(--card-bg) 100%);padding:20px 0;box-shadow:0 4px 20px rgba(0,255,65,0.2);border-bottom:2px solid var(--primary);}
    nav{max-width:1400px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center;}
    .logo{width:70px;height:auto;}
    .back-btn{color:var(--text);text-decoration:none;padding:10px 20px;border:1px solid var(--primary);border-radius:5px;transition:all 0.3s;}
    .back-btn:hover{background:var(--primary);color:var(--dark);box-shadow:0 0 15px var(--glow);}
    .container{max-width:1200px;margin:40px auto;padding:0 20px;}
    h1{color:var(--primary);text-align:center;margin-bottom:40px;font-size:36px;text-shadow:0 0 20px var(--glow);}
    .success-banner{background:rgba(0,255,65,0.2);border:2px solid var(--primary);border-radius:10px;padding:20px;margin-bottom:30px;text-align:center;animation:slideDown 0.5s ease;}
    @keyframes slideDown{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}
    .success-banner h2{color:var(--primary);margin-bottom:10px;}
    .order-card{background:var(--card-bg);border:2px solid var(--primary);border-radius:15px;padding:25px;margin-bottom:25px;transition:all 0.3s;}
    .order-card:hover{box-shadow:0 0 30px var(--glow);transform:translateY(-5px);}
    .order-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:15px;border-bottom:1px solid rgba(0,255,65,0.2);}
    .order-id{color:var(--primary);font-size:20px;font-weight:bold;}
    .order-date{color:var(--text);font-size:14px;}
    .status-badge{display:inline-flex;align-items:center;gap:8px;padding:8px 15px;border-radius:20px;font-size:14px;font-weight:bold;}
    .order-items{margin:20px 0;}
    .order-item{display:flex;justify-content:space-between;padding:12px;background:var(--darker);border-radius:8px;margin-bottom:10px;}
    .order-footer{display:flex;justify-content:space-between;align-items:center;margin-top:20px;padding-top:15px;border-top:1px solid rgba(0,255,65,0.2);}
    .order-total{color:var(--primary);font-size:24px;font-weight:bold;}
    .order-actions{display:flex;gap:10px;}
    .btn{padding:10px 20px;border:none;border-radius:8px;cursor:pointer;font-weight:bold;transition:all 0.3s;font-family:inherit;}
    .btn-primary{background:var(--primary);color:var(--dark);}
    .btn-primary:hover{background:var(--secondary);}
    .btn-secondary{background:transparent;border:1px solid var(--primary);color:var(--primary);}
    .btn-secondary:hover{background:var(--primary);color:var(--dark);}
    .empty-state{text-align:center;padding:60px 20px;}
    .empty-icon{font-size:80px;margin-bottom:20px;}
    .new-order-btn{display:inline-block;margin:30px auto;padding:15px 40px;background:var(--primary);color:var(--dark);text-decoration:none;border-radius:8px;font-weight:bold;transition:all 0.3s;}
    .new-order-btn:hover{background:var(--secondary);box-shadow:0 0 25px rgba(255,107,53,0.5);transform:translateY(-2px);}
    .notification{position:fixed;top:20px;right:20px;background:var(--primary);color:var(--dark);padding:20px 30px;border-radius:8px;font-weight:bold;z-index:9999;animation:slideIn 0.3s ease;box-shadow:0 5px 20px var(--glow);}
    #orderModal{display:flex;align-items:center;justify-content:center;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:10000;}
    .modal-overlay{position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(10,14,39,0.7);z-index:10001;}
    .modal-content{background:var(--card-bg);border:2px solid var(--primary);border-radius:20px;box-shadow:0 0 40px var(--glow);padding:40px;max-width:400px;width:90vw;z-index:10002;position:relative;}
    @keyframes slideIn{from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
    @media (max-width:768px){
        .order-header{flex-direction:column;gap:10px;align-items:flex-start;}
        .order-footer{flex-direction:column;gap:15px;align-items:flex-start;}
        .order-actions{width:100%;}
        .btn{flex:1;}
    }
</style>
</head>
<body>
<div class="matrix-bg"><canvas id="matrixCanvas"></canvas></div>

<header>
    <nav>
        <a href="index.php"><img src="img/logo.png" alt="Logo" class="logo"></a>
        <a href="index.php" class="back-btn">⬅ Volver al inicio</a>
    </nav>
</header>

<div class="container">
    <h1>📦 Mis Pedidos</h1>
    
    <?php if($success_message): ?>
        <div class="success-banner">
            <h2>🎉 ¡Pedido Realizado Exitosamente!</h2>
            <p>Tu pedido ha sido procesado y recibirás un correo de confirmación pronto.</p>
        </div>
    <?php endif; ?>
    
    <div style="text-align:center;margin-bottom:30px;">
        <a href="index.php#productos" class="new-order-btn">🛍️ Hacer Nuevo Pedido</a>
    </div>
    
    <?php if(empty($orders)): ?>
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h2 style="color:var(--primary);margin-bottom:15px;">No tienes pedidos aún</h2>
            <p style="margin-bottom:25px;">Explora nuestro catálogo y realiza tu primera compra</p>
        </div>
    <?php else: ?>
        <?php foreach($orders as $order):
            $badge = getStatusBadge($order['status']);
        ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-id">🔖 <?php echo htmlspecialchars($order['id']); ?></div>
                        <div class="order-date">📅 <?php echo date('d/m/Y', strtotime($order['date'])); ?></div>
                    </div>
                    <div class="status-badge" style="background:<?php echo $badge['color']; ?>20;border:1px solid <?php echo $badge['color']; ?>;color:<?php echo $badge['color']; ?>;">
                        <?php echo $badge['icon']; ?> <?php echo $badge['text']; ?>
                    </div>
                </div>
                
                <div class="order-items">
                    <strong style="color:var(--primary);display:block;margin-bottom:10px;">📋 Productos:</strong>
                    <?php foreach($order['items'] as $item): ?>
                        <div class="order-item">
                            <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['qty']; ?></span>
                            <span style="color:var(--secondary);">$<?php echo number_format($item['price'] * $item['qty'], 0, ',', '.'); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-footer">
                    <div class="order-total">
                        💰 Total: $<?php echo number_format($order['total'], 0, ',', '.'); ?> COP
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-primary" onclick="viewDetails('<?php echo $order['id']; ?>')">
                            👁️ Ver Detalles
                        </button>
                        <?php if($order['status'] !== 'entregado'): ?>
                            <button class="btn btn-secondary" onclick="trackOrder('<?php echo $order['id']; ?>')">
                                📍 Rastrear
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>

// Modal para detalles de pedido
function viewDetails(orderId){
    const orders = <?php echo json_encode($orders); ?>;
    const order = orders.find(o => o.id === orderId);
    if(!order){
        showNotification('Pedido no encontrado');
        return;
    }
    let html = `<div class='modal-overlay' onclick='closeModal(event)'></div><div class='modal-content'>`;
    html += `<h2 style='color:var(--primary);margin-bottom:10px;'>Detalles del Pedido ${order.id}</h2>`;
    html += `<p><strong>Fecha:</strong> ${order.date}</p>`;
    html += `<p><strong>Estado:</strong> ${order.status}</p>`;
    html += `<div style='margin:15px 0;'><strong>Productos:</strong><ul style='padding-left:20px;'>`;
    order.items.forEach(item => {
        html += `<li>${item.name} x${item.qty} <span style='color:var(--secondary);'>($${(item.price*item.qty).toLocaleString('es-CO')})</span></li>`;
    });
    html += `</ul></div>`;
    html += `<div style='font-size:18px;color:var(--primary);margin-top:10px;'><strong>Total:</strong> $${order.total.toLocaleString('es-CO')} COP</div>`;
    html += `<button class='btn' style='margin-top:20px;background:var(--secondary);color:white;' onclick='closeModal()'>Cerrar</button>`;
    html += `</div>`;
    let modal = document.createElement('div');
    modal.id = 'orderModal';
    modal.innerHTML = html;
    document.body.appendChild(modal);
}

function trackOrder(orderId){
    try {
        // Elimina cualquier modal anterior
        const oldModal = document.getElementById('orderModal');
        if(oldModal) oldModal.remove();
        const orders = <?php echo json_encode($orders); ?>;
        const order = orders.find(o => o.id === orderId);
        let html = `<div class='modal-overlay' onclick='closeModal(event)'></div><div class='modal-content'>`;
        html += `<h2 style='color:var(--primary);margin-bottom:10px;'>Rastreo de Pedido ${orderId}</h2>`;
        if(!order){
            html += `<p>No se encontró información de este pedido.</p>`;
        } else if(order.status === 'procesando'){
            html += `<p>⏳ Tu pedido está siendo procesado.<br>Fecha de pedido: <strong>${order.date}</strong></p>`;
            html += `<p>Pronto será enviado. Recibirás notificación cuando esté en tránsito.</p>`;
        } else if(order.status === 'en_transito'){
            html += `<p>🚚 Tu pedido está en tránsito.<br>Fecha de envío: <strong>${order.date}</strong></p>`;
            html += `<p>El transportista está en camino. Tiempo estimado de entrega: 2-5 días hábiles.</p>`;
        } else if(order.status === 'entregado'){
            html += `<p>✅ Tu pedido fue entregado.<br>Fecha de entrega: <strong>${order.date}</strong></p>`;
            html += `<p>¡Gracias por tu compra!</p>`;
        }
        html += `<button class='btn' style='margin-top:20px;background:var(--secondary);color:white;' onclick='closeModal()'>Cerrar</button>`;
        html += `</div>`;
        let modal = document.createElement('div');
        modal.id = 'orderModal';
        modal.innerHTML = html;
        document.body.appendChild(modal);
        console.log('Modal de rastreo mostrado para pedido:', orderId);
    } catch(e) {
        showNotification('Error al mostrar rastreo');
        console.error('Error en trackOrder:', e);
    }
}

function closeModal(e){
    if(e && e.target.classList.contains('modal-overlay')){
        document.getElementById('orderModal').remove();
    } else if(!e) {
        document.getElementById('orderModal').remove();
    }
}

function trackOrder(orderId){
    showNotification('📍 Rastreando pedido ' + orderId);
    // Aquí implementar sistema de rastreo
}

function showNotification(msg){
    const n=document.createElement('div');
    n.className='notification';
    n.textContent=msg;
    document.body.appendChild(n);
    setTimeout(()=>{
        n.style.opacity='0';
        n.style.transition='opacity 0.5s';
        setTimeout(()=>n.remove(),500);
    },3000);
}

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