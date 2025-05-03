
<?php
// Configurar conexión a SQLite
$db = new SQLite3('Inventario.db');

// Obtener datos JSON enviados desde el frontend
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo "No se recibieron datos.";
    exit;
}

// Crear tabla si no existe
$db->exec("CREATE TABLE IF NOT EXISTS pedidos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tamano TEXT,
    tipo TEXT,
    sin_ingredientes TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $db->prepare("INSERT INTO pedidos (tamano, tipo, sin_ingredientes) VALUES (:tamano, :tipo, :sin_ingredientes)");

foreach ($data as $pedido) {
    $tamano = $pedido['tamano'];
    $tipo = $pedido['tipo'];
    $sin_ingredientes = implode(', ', $pedido['ingredientes']);

    $stmt->bindValue(':tamano', $tamano, SQLITE3_TEXT);
    $stmt->bindValue(':tipo', $tipo, SQLITE3_TEXT);
    $stmt->bindValue(':sin_ingredientes', $sin_ingredientes, SQLITE3_TEXT);
    $stmt->execute();
}

echo "Pedidos guardados correctamente.";
?>
