<?php
// Conexión a la base de datos SQLite
try {
    $conexion = new PDO('sqlite:gladius.db');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}
?>
