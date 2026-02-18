<?php

session_start();

// Procesar acciones de actualización/eliminación ANTES de cualquier salida HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        echo json_encode(['success'=>false, 'error'=>'Sesión expirada', 'redirect'=>'iniciar_sesion.php']);
        exit();
    }
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update' && isset($_POST['id'], $_POST['change'])) {
            $pid = intval($_POST['id']);
            $change = intval($_POST['change']);
            if (isset($_SESSION['cart'][$pid])) {
                $newQty = $_SESSION['cart'][$pid] + $change;
                if ($newQty > 10) {
                    echo json_encode(['success'=>false, 'error'=>'Máximo 10 unidades por producto']);
                    exit;
                }
                $_SESSION['cart'][$pid] = $newQty;
                if ($_SESSION['cart'][$pid] < 1) {
                    unset($_SESSION['cart'][$pid]);
                }
            }
            echo json_encode(['success'=>true, 'cart'=>$_SESSION['cart']]);
            exit;
        }
        if ($_POST['action'] === 'remove' && isset($_POST['id'])) {
            $pid = intval($_POST['id']);
            unset($_SESSION['cart'][$pid]);
            echo json_encode(['success'=>true, 'cart'=>$_SESSION['cart']]);
            exit;
        }
    }
}

// Verificar login
if (!isset($_SESSION['user_id'])) {
    // Si la petición es AJAX (fetch), responde JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success'=>false, 'error'=>'Sesión expirada', 'redirect'=>'iniciar_sesion.php']);
        exit();
    }
    header('Location: iniciar_sesion.php');
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Usuario';

// Obtener productos reales del carrito
$cart_items = [];
if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
    // Simulación de catálogo
    $catalog = [
        1 => ['name'=>'Martillo Profesional','price'=>45000,'icon'=>'🔨'],
        2 => ['name'=>'Taladro Eléctrico','price'=>320000,'icon'=>'🔧'],
        3 => ['name'=>'Cable Eléctrico 10m','price'=>28000,'icon'=>'⚡'],
        4 => ['name'=>'Sierra Eléctrica','price'=>420000,'icon'=>'🪚'],
        5 => ['name'=>'Destornillador Set','price'=>35000,'icon'=>'🔩'],
        6 => ['name'=>'Cinta Métrica 5m','price'=>15000,'icon'=>'📏']
    ];
    foreach($_SESSION['cart'] as $pid=>$qty){
        if(isset($catalog[$pid])){
            $cart_items[] = [
                'id'=>$pid,
                'name'=>$catalog[$pid]['name'],
                'price'=>$catalog[$pid]['price'],
                'qty'=>$qty,
                'icon'=>$catalog[$pid]['icon']
            ];
        }
    }
}

$subtotal = 0;
foreach($cart_items as $item){
    $subtotal += $item['price'] * $item['qty'];
}
$tax = $subtotal * 0.19; // IVA 19%
$total = $subtotal + $tax;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrito de Compras - INFERROD S.A</title>
<link rel="icon" type="image/png" href="img/logo.png">
<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>
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
    .cart-layout{display:grid;grid-template-columns:2fr 1fr;gap:30px;}
    .cart-items{background:var(--card-bg);border:1px solid var(--primary);border-radius:15px;padding:25px;}
    .cart-item{display:grid;grid-template-columns:80px 1fr auto;gap:20px;align-items:center;padding:20px;background:var(--darker);border:1px solid var(--primary);border-radius:10px;margin-bottom:15px;}
    .item-icon{font-size:50px;text-align:center;}
    .item-info h3{color:var(--primary);margin-bottom:8px;}
    .item-info p{color:var(--text);font-size:14px;}
    .item-controls{display:flex;flex-direction:column;gap:10px;align-items:flex-end;}
    .qty-controls{display:flex;gap:10px;align-items:center;}
    .qty-btn{width:30px;height:30px;background:var(--primary);color:var(--dark);border:none;border-radius:5px;cursor:pointer;font-weight:bold;font-size:18px;}
    .qty-btn:hover{background:var(--secondary);}
    .remove-btn{background:var(--secondary);color:white;border:none;padding:8px 15px;border-radius:5px;cursor:pointer;font-size:12px;}
    .remove-btn:hover{background:#ff4520;}
    .order-summary{background:var(--card-bg);border:2px solid var(--primary);border-radius:15px;padding:25px;height:fit-content;position:sticky;top:20px;}
    .order-summary h2{color:var(--primary);margin-bottom:20px;text-align:center;}
    .summary-row{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid rgba(0,255,65,0.2);}
    .summary-row.total{border-top:2px solid var(--primary);border-bottom:none;margin-top:15px;padding-top:15px;font-size:20px;font-weight:bold;color:var(--primary);}
    .checkout-btn{width:100%;padding:15px;background:var(--primary);color:var(--dark);border:none;border-radius:8px;font-weight:bold;font-size:16px;cursor:pointer;margin-top:20px;transition:all 0.3s;font-family:inherit;}
    .checkout-btn:hover{background:var(--secondary);box-shadow:0 0 25px rgba(255,107,53,0.5);transform:translateY(-2px);}
    .empty-cart{text-align:center;padding:60px 20px;}
    .empty-cart-icon{font-size:80px;margin-bottom:20px;}
    .paypal-container{margin-top:20px;}
    .notification{position:fixed;top:20px;right:20px;background:var(--primary);color:var(--dark);padding:20px 30px;border-radius:8px;font-weight:bold;z-index:9999;animation:slideIn 0.3s ease;box-shadow:0 5px 20px var(--glow);}
    @keyframes slideIn{from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
    @media (max-width:768px){
        .cart-layout{grid-template-columns:1fr;}
        .cart-item{grid-template-columns:60px 1fr;gap:15px;}
        .item-controls{grid-column:1/-1;justify-content:space-between;flex-direction:row;}
    }
</style>
</head>
<body>
<div class="matrix-bg"><canvas id="matrixCanvas"></canvas></div>

<header>
    <nav>
        <a href="index.php"><img src="img/logo.png" alt="Logo" class="logo"></a>
        <a href="index.php" class="back-btn">⬅ Volver a la tienda</a>
    </nav>
</header>

<div class="container">
    <h1>🛒 Tu Carrito de Compras</h1>
    
    <?php if(empty($cart_items)): ?>
        <div class="empty-cart">
            <div class="empty-cart-icon">🛒</div>
            <h2 style="color:var(--primary);margin-bottom:15px;">Tu carrito está vacío</h2>
            <p style="margin-bottom:25px;">Agrega productos para comenzar tu compra</p>
            <a href="index.php" class="checkout-btn" style="display:inline-block;width:auto;padding:15px 40px;text-decoration:none;">Ir a la tienda</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <div class="cart-items">
                <?php foreach($cart_items as $item): ?>
                    <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                        <div class="item-icon"><?php echo $item['icon']; ?></div>
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Precio unitario: $<?php echo number_format($item['price'], 0, ',', '.'); ?> COP</p>
                            <p style="color:var(--secondary);font-weight:bold;margin-top:5px;">
                                Subtotal: $<?php echo number_format($item['price'] * $item['qty'], 0, ',', '.'); ?> COP
                            </p>
                        </div>
                        <div class="item-controls">
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="updateQty(<?php echo $item['id']; ?>, -1)">-</button>
                                <span class="qty-value" style="font-weight:bold;font-size:16px;"><?php echo $item['qty']; ?></span>
                                <button class="qty-btn" onclick="updateQty(<?php echo $item['id']; ?>, 1)">+</button>
                            </div>
                            <button class="remove-btn" onclick="removeItem(<?php echo $item['id']; ?>)">🗑️ Eliminar</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="order-summary">
                <h2>📊 Resumen del Pedido</h2>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($subtotal, 0, ',', '.'); ?> COP</span>
                </div>
                <div class="summary-row">
                    <span>IVA (19%):</span>
                    <span>$<?php echo number_format($tax, 0, ',', '.'); ?> COP</span>
                </div>
                <div class="summary-row">
                    <span>Envío:</span>
                    <span style="color:var(--primary);">GRATIS</span>
                </div>
                <div class="summary-row total">
                    <span>TOTAL:</span>
                    <span>$<?php echo number_format($total, 0, ',', '.'); ?> COP</span>
                </div>
                
                <p style="text-align:center;margin-top:20px;font-size:12px;color:var(--text);">
                    ≈ $<?php echo number_format($total / 4000, 2); ?> USD
                </p>
                
                <button class="checkout-btn" onclick="showPayPalButtons()">
                    💳 Proceder al Pago
                </button>
                
                <!-- Contenedor de botones PayPal -->
                <div id="paypal-button-container" class="paypal-container" style="display:none;"></div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
let paypalRendered = false;

function showPayPalButtons(){
    if(paypalRendered) return;
    
    document.getElementById('paypal-button-container').style.display = 'block';
    
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo number_format($total / 4000, 2, '.', ''); ?>', // Convertir COP a USD
                        currency_code: 'USD'
                    },
                    description: 'Compra en INFERROD S.A'
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                showNotification('✅ Pago completado: ' + details.payer.name.given_name);
                
                // Enviar datos al servidor para procesar el pedido
                fetch('procesar_pedido.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        orderID: data.orderID,
                        details: details
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        window.location.href = 'pedidos.php?success=1';
                    }
                });
            });
        },
        onError: function(err) {
            showNotification('❌ Error en el pago. Intenta nuevamente.');
            console.error(err);
        }
    }).render('#paypal-button-container');
    
    paypalRendered = true;
}

function updateQty(id, change){
    fetch('carrito.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=update&id=${id}&change=${change}`
    })
        .then(response => response.json().catch(() => ({success:false,error:'Error inesperado'})))
        .then(data => {
            if(data.success && data.cart){
                // Actualizar la cantidad en pantalla sin recargar
                const cartItem = document.querySelector(`.cart-item[data-id='${id}']`);
                if(cartItem){
                    const qtySpan = cartItem.querySelector('.qty-value');
                    const newQty = data.cart[id] || 0;
                    if(qtySpan){
                        qtySpan.textContent = newQty;
                    }
                    if(newQty < 1){
                        cartItem.remove();
                    }
                }
                // Opcional: recargar totales y PayPal si quieres
                location.reload();
            } else if(data.error && data.redirect){
                alert('Sesión expirada, redirigiendo...');
                window.location.href = data.redirect;
            } else {
                alert(data.error || 'Error al actualizar cantidad');
            }
        });
}

function removeItem(id){
    if(confirm('¿Eliminar este producto del carrito?')){
        fetch('carrito.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=remove&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                showNotification('🗑️ Producto eliminado');
                setTimeout(() => location.reload(), 100);
            } else {
                showNotification('Error al eliminar');
            }
        })
        .catch(()=>showNotification('Error de conexión'));
    }
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

// ...el bloque de procesamiento POST ya está al inicio...