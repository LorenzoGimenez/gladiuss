<?php
// Incluir el archivo de conexión a la base de datos
require 'conexion.php';

// Verificar si se proporciona un ID de archivo para descargar
if (isset($_GET['id'])) {
    $id_archivo = $_GET['id'];

    // Obtener los detalles del archivo desde la base de datos
    try {
        $stmt = $conexion->prepare("SELECT nombre_archivo, archivo, tipo_archivo FROM repositorio WHERE id_repositorio = ?");
        $stmt->execute([$id_archivo]);
        $archivo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró el archivo en la base de datos
        if ($archivo) {
            // Establecer las cabeceras para la descarga del archivo
            header("Content-Type: " . $archivo['tipo_archivo']);
            header("Content-Disposition: attachment; filename=\"" . $archivo['nombre_archivo'] . "\"");

            // Imprimir los datos binarios del archivo
            echo $archivo['archivo'];
            exit;
        } else {
            echo "Archivo no encontrado.";
        }
    } catch (PDOException $e) {
        echo "Error al descargar el archivo: " . $e->getMessage();
    }
} else {
    echo "ID de archivo no proporcionado.";
}
?>
