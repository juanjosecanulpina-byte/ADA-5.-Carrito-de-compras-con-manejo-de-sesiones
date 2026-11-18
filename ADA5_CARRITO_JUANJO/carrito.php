<?php
session_start();

// Modificar cantidad de un producto
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nueva_cantidad = (int)$_POST['cantidad'];
    if ($nueva_cantidad > 0) {
        $_SESSION['carrito'][$id]['cantidad'] = $nueva_cantidad;
    } else {
        unset($_SESSION['carrito'][$id]);
    }
}

// Eliminar un producto
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    unset($_SESSION['carrito'][$id]);
    header("Location: carrito.php");
    exit();
}

// Vaciar todo el carrito
if (isset($_GET['vaciar'])) {
    unset($_SESSION['carrito']);
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito - ADA 5</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f6f9; }
        .contenedor { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0056b3; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #0056b3; color: white; }
        .btn { padding: 6px 12px; background: #0056b3; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn-danger { background: #dc3545; }
    </style>
</head>
<body>
<div class="contenedor">
    <div class="header">
        <h2>Carrito de Compras</h2>
        <div>
            <span>Usuario en sesión: <strong><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Invitado'; ?></strong></span>
            <a href="index.php" class="btn" style="margin-left:10px;">Seguir Comprando</a>
        </div>
    </div>

    <?php if (!empty($_SESSION['carrito'])): ?>
        <table>
            <tr>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Cantidad (Modificar)</th>
                <th>Subtotal</th>
                <th>Eliminar</th>
            </tr>
            <?php 
            $total = 0;
            foreach ($_SESSION['carrito'] as $id => $item): 
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo $item['nombre']; ?></td>
                <td>$<?php echo number_format($item['precio'], 2); ?></td>
                <td>
                    <form method="POST" style="display:flex; gap:5px;">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" min="1" style="width: 50px;">
                        <button type="submit" name="actualizar" class="btn">Actualizar</button>
                    </form>
                </td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <a href="?eliminar=<?php echo $id; ?>" class="btn btn-danger">Quitar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align:right;"><strong>Total a Pagar:</strong></td>
                <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </table>
        <br>
        <a href="?vaciar=1" class="btn btn-danger">Vaciar Carrito</a>
    <?php else: ?>
        <p style="margin-top:20px;">Tu carrito está vacío. Ve al catálogo para añadir productos.</p>
    <?php endif; ?>
</div>
</body>
</html>