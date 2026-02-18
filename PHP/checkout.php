<?php
require_once 'config.php';

// Carrito de la sesión
$cart = $_SESSION['cart'] ?? [];

// PayPal sandbox credentials
$paypalClientId = 'TU_CLIENT_ID_SANDBOX';
$paypalSecret = 'TU_SECRET_SANDBOX';

// Calcular total
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout - TechHardware</title>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypalClientId; ?>&currency=USD"></script>
</head>
<body>
    <h2>Resumen de tu compra</h2>
    <ul>
        <?php foreach ($cart as $id => $item): ?>
            <li><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?> - $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></li>
        <?php endforeach; ?>
    </ul>
    <p><strong>Total: $<?php echo number_format($total, 2); ?> USD</strong></p>

    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $total; ?>'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Compra realizada con éxito, ' + details.payer.name.given_name);
                    // Vaciar carrito
                    fetch('cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'action=clear'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
