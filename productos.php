<?php
$conn = getConnection();
$result = $conn->query("SELECT * FROM productos WHERE disponible=1");
while ($producto = $result->fetch_assoc()):
?>
<div style="margin-bottom:20px;">
    <h3><?= htmlspecialchars($producto['nombre_producto']) ?></h3>
    <p>Precio: $<?= number_format($producto['precio'],0) ?></p>
    <form method="post" action="añadir_carrito.php">
        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
        <input type="number" name="cantidad" value="1" min="1" max="<?= $producto['stock'] ?>">
        <button type="submit">Añadir al carrito</button>
    </form>
</div>
<?php endwhile; ?>
