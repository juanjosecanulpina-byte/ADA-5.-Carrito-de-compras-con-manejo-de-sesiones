<?php
session_start();
include("conexion.php");

// Guardar o actualizar el nombre del usuario en la sesión
if (isset($_POST['guardar_usuario']) && !empty($_POST['nombre_usuario'])) {
    $_SESSION['usuario'] = htmlspecialchars($_POST['nombre_usuario']);
}

// Cerrar sesión (opcional para cambiar de usuario)
if (isset($_GET['cerrar_sesion'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Añadir producto al carrito
if (isset($_POST['agregar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = (int)$_POST['cantidad'];

    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$id] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];
    }
    $mensaje = "Producto agregado al carrito.";
}

$resultado = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda ADA 5 - Sesiones</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f6f9; }
        .contenedor { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0056b3; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #0056b3; color: white; }
        .btn { padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn-carrito { background: #ff9800; }
        .alerta { background: #d4edda; color: #155724; padding: 10px; margin-top: 10px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="contenedor">
    <div class="header">
        <h2>Catálogo de Productos</h2>
        <div>
            <?php if (isset($_SESSION['usuario'])): ?>
                <strong>Bienvenido, <?php echo $_SESSION['usuario']; ?></strong> | 
                <a href="?cerrar_sesion=1" style="color:red; font-size:12px;">Cambiar usuario</a>
            <?php else: ?>
                <form method="POST" style="display:inline;">
                    <input type="text" name="nombre_usuario" placeholder="Ingresa tu nombre" required>
                    <button type="submit" name="guardar_usuario" class="btn">Iniciar Sesión</button>
                </form>
            <?php endif; ?>
            <a href="carrito.php" class="btn btn-carrito" style="margin-left: 10px;">
                Ver Carrito (<?php echo count($_SESSION['carrito']); ?>)
            </a>
        </div>
    </div>

    <?php if (isset($mensaje)) echo "<div class='alerta'>$mensaje</div>"; ?>

    <table>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Acción</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $fila['nombre']; ?></td>
            <td>$<?php echo number_format($fila['precio'], 2); ?></td>
            <td>
                <form method="POST" style="display:flex; gap:5px;">
                    <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $fila['nombre']; ?>">
                    <input type="hidden" name="precio" value="<?php echo $fila['precio']; ?>">
                    <input type="number" name="cantidad" value="1" min="1" style="width: 50px;">
            </td>
            <td>
                    <button type="submit" name="agregar" class="btn">Añadir</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>