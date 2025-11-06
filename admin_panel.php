<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo administrador
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$conn = getConnection();

// Productos
$productos = $conn->query("SELECT * FROM productos ORDER BY id_producto ASC")->fetch_all(MYSQLI_ASSOC);

// Usuarios (excepto admin principal si quieres protegerlo)
$usuarios = $conn->query("SELECT * FROM usuarios ORDER BY id_usuario ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Admin - Inferrod</title>
<style>
body { font-family: Arial, sans-serif; background: #0a0e27; color: #e0e0e0; padding: 20px; }
h1 { color: #00ff41; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
th, td { padding: 10px; border: 1px solid #00ff41; text-align: center; }
input, select { padding: 5px; width: 90%; }
.btn { padding: 5px 10px; background: #00ff41; color: #0a0e27; border: none; cursor: pointer; border-radius: 5px; }
.btn:hover { background: #00cc33; }
.message { position: fixed; top: 20px; right: 20px; background: #00ff4120; color: #00ff41; padding: 12px; border: 1px solid #00ff41; border-radius: 5px; display: none; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
@keyframes fadein { from {opacity: 0;} to {opacity: 1;} }
@keyframes fadeout { from {opacity:1;} to {opacity:0;} }
</style>
</head>
<body>
<h1>Panel Administrador</h1>
<p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?> | <a href="logout.php" style="color:#00ff41;">Cerrar sesión</a></p>

<h2>Productos</h2>
<table id="tabla_productos">
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Cantidad</th>
<th>Disponible</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach($productos as $p): ?>
<tr>
<td><?php echo $p['id_producto']; ?></td>
<td><input type="text" value="<?php echo htmlspecialchars($p['nombre_producto']); ?>" id="nombre-<?php echo $p['id_producto']; ?>"></td>
<td><input type="number" value="<?php echo $p['cantidad']; ?>" min="0" id="cantidad-<?php echo $p['id_producto']; ?>"></td>
<td>
<select id="disponible-<?php echo $p['id_producto']; ?>">
<option value="1" <?php echo $p['disponible'] ? 'selected' : ''; ?>>Disponible</option>
<option value="0" <?php echo !$p['disponible'] ? 'selected' : ''; ?>>No disponible</option>
</select>
</td>
<td>
<button class="btn" onclick="actualizarProducto(<?php echo $p['id_producto']; ?>)">Guardar</button>
<button class="btn" onclick="eliminarProducto(<?php echo $p['id_producto']; ?>)">Eliminar</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<h2>Usuarios</h2>
<table id="tabla_usuarios">
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Correo</th>
<th>Rol</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach($usuarios as $u): ?>
<tr>
<td><?php echo $u['id_usuario']; ?></td>
<td><input type="text" value="<?php echo htmlspecialchars($u['nombre_usuario']); ?>" id="nombre-<?php echo $u['id_usuario']; ?>"></td>
<td><input type="email" value="<?php echo htmlspecialchars($u['correo']); ?>" id="correo-<?php echo $u['id_usuario']; ?>"></td>
<td>
<select id="rol-<?php echo $u['id_usuario']; ?>">
<option value="cliente" <?php echo $u['rol']=='cliente'?'selected':'';?>>Cliente</option>
<option value="vendedor" <?php echo $u['rol']=='vendedor'?'selected':'';?>>Vendedor</option>
<option value="admin" <?php echo $u['rol']=='admin'?'selected':'';?>>Admin</option>
</select>
</td>
<td>
<button class="btn" onclick="actualizarUsuario(<?php echo $u['id_usuario']; ?>)">Guardar</button>
<button class="btn" onclick="eliminarUsuario(<?php echo $u['id_usuario']; ?>)">Eliminar</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<h2>Agregar nuevo usuario</h2>
<table>
<tr>
<td>Nombre:</td><td><input type="text" id="nuevo_nombre" placeholder="Nombre"></td>
<td>Correo:</td><td><input type="email" id="nuevo_correo" placeholder="Correo"></td>
<td>Contraseña:</td><td><input type="password" id="nuevo_password" placeholder="Contraseña"></td>
<td>Rol:</td>
<td>
<select id="nuevo_rol">
<option value="cliente">Cliente</option>
<option value="vendedor">Vendedor</option>
<option value="admin">Admin</option>
</select>
</td>
<td><button class="btn" onclick="agregarUsuario()">Agregar</button></td>
</tr>
</table>

<div class="message" id="message"></div>

<script>
// Mini cinemática
function showMessage(text, success=true) {
    const msg = document.getElementById('message');
    msg.style.display = 'block';
    msg.style.background = success ? '#00ff4120' : '#ff353520';
    msg.style.color = success ? '#00ff41' : '#ff3535';
    msg.textContent = text;
    setTimeout(()=>msg.style.display='none',3000);
}

// Productos
async function actualizarProducto(id){
    const nombre = document.getElementById('nombre-'+id).value;
    const cantidad = document.getElementById('cantidad-'+id).value;
    const disponible = document.getElementById('disponible-'+id).value;
    const fd = new FormData();
    fd.append('id_producto', id);
    fd.append('nombre', nombre);
    fd.append('cantidad', cantidad);
    fd.append('disponible', disponible);

    const res = await fetch('admin_update_product.php',{method:'POST', body:fd});
    const data = await res.json();
    showMessage(data.message, data.success);
}

async function eliminarProducto(id){
    if(!confirm('¿Eliminar producto?')) return;
    const fd = new FormData();
    fd.append('id_producto', id);
    const res = await fetch('admin_delete_product.php',{method:'POST', body:fd});
    const data = await res.json();
    showMessage(data.message, data.success);
    if(data.success) location.reload();
}

// Usuarios
async function actualizarUsuario(id){
    const nombre = document.getElementById('nombre-'+id).value;
    const correo = document.getElementById('correo-'+id).value;
    const rol = document.getElementById('rol-'+id).value;
    const fd = new FormData();
    fd.append('id_usuario', id);
    fd.append('nombre', nombre);
    fd.append('correo', correo);
    fd.append('rol', rol);

    const res = await fetch('admin_update_user.php',{method:'POST', body:fd});
    const data = await res.json();
    showMessage(data.message, data.success);
}

async function eliminarUsuario(id){
    if(!confirm('¿Eliminar usuario?')) return;
    const fd = new FormData();
    fd.append('id_usuario', id);
    const res = await fetch('admin_delete_user.php',{method:'POST', body:fd});
    const data = await res.json();
    showMessage(data.message, data.success);
    if(data.success) location.reload();
}

async function agregarUsuario() {
    const nombre = document.getElementById('nuevo_nombre').value.trim();
    const correo = document.getElementById('nuevo_correo').value.trim();
    const password = document.getElementById('nuevo_password').value.trim();
    const rol = document.getElementById('nuevo_rol').value;

    if (!nombre || !correo || !password) {
        showMessage('Todos los campos son obligatorios', false);
        return;
    }

    try {
        const fd = new FormData();
        fd.append('nombre', nombre);
        fd.append('correo', correo);
        fd.append('password', password);
        fd.append('rol', rol);

        // 📡 Enviar los datos al archivo PHP
        const res = await fetch('admin_add_user.php', {
            method: 'POST',
            body: fd
        });

        // 🧩 Verificar si la respuesta es JSON válida
        const text = await res.text();
        let data;

        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error("Respuesta no válida del servidor:", text);
            showMessage("Error interno del servidor. Revisa la consola.", false);
            return;
        }

        if (data.success) {
            showMessage(data.message || 'Usuario agregado correctamente', true);
            document.getElementById('nuevo_nombre').value = '';
            document.getElementById('nuevo_correo').value = '';
            document.getElementById('nuevo_password').value = '';
            document.getElementById('nuevo_rol').value = 'cliente';

            // 🔄 Recargar la lista de usuarios
            if (typeof cargarUsuarios === 'function') cargarUsuarios();
        } else {
            showMessage(data.message || 'No se pudo agregar el usuario', false);
        }

    } catch (err) {
        console.error('Error al conectar con el servidor:', err);
        showMessage('Error al conectar con el servidor', false);
    }
}

</script>
</body>
</html>
