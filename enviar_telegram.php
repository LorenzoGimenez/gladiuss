<?php
// Función para enviar el mensaje a Telegram
function sendMessageToTelegram($message, $botToken, $chatID) {
    $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatID&parse_mode=HTML&text=" . urlencode($message);
    file_get_contents($url);
}

// Llama a esta función después de imprimir el contenido en la página
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_evento'])) {
    // Código para obtener los detalles del evento y el denunciante

    // Construye el mensaje
    $message = "<b>Detalles del Evento y Denunciante</b>\n";
    $message .= "ID de Evento: " . $detalle_evento['id_evento'] . "\n";
    $message .= "Tipo: " . $detalle_evento['tipo'] . "\n";
    $message .= "Subtipo: " . $detalle_evento['subtipo'] . "\n";
    $message .= "Descripción: " . $detalle_evento['descripcion'] . "\n";
    $message .= "Fecha y Hora: " . $detalle_evento['fecha_hora'] . "\n";
    $message .= "Estado: " . $detalle_evento['estado'] . "\n";
    $message .= "Nombre del Usuario: " . $detalle_evento['nombre_usuario'] . "\n";
    $message .= "Agente: " . $detalle_evento['agente'] . "\n";
    $message .= "\n";
    $message .= "<b>Detalles del Denunciante</b>\n";
    $message .= "Nombre: " . $detalle_denunciante['nombre'] . "\n";
    $message .= "Apellido: " . $detalle_denunciante['apellido'] . "\n";
    $message .= "Tipo de Documento: " . $detalle_denunciante['tipo_documento'] . "\n";
    $message .= "Documento: " . $detalle_denunciante['documento'] . "\n";
    $message .= "Teléfono: " . $detalle_denunciante['tel'] . "\n";

    // Aquí debes reemplazar 'YOUR_BOT_TOKEN' con el token de tu bot
    // y 'YOUR_CHAT_ID' con el ID de tu canal
    $botToken = '7056538489:AAHvG4cXMBnlo8lhLs_uaRXF-cgZ_GLy8xI';
    $chatID = '1998249772';

    // Envía el mensaje a Telegram
    sendMessageToTelegram($message, $botToken, $chatID);
}
?>
