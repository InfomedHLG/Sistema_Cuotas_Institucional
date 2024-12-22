<?php
require_once('connect.php');
require_once('constants.php');

// Obtener el IP del cliente
$ip = $_SERVER['REMOTE_ADDR'];

// Crear conexión
$conn = conexion();

// Consulta SQL usando las constantes
$sql = "SELECT * FROM " . TABLE_NAME . " WHERE " . CLIENTE_IP . " = '$ip'";
$result = $conn->query($sql);

// Función para formatear bytes con valor y unidad separados
function formatBytesWithUnit($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return [
        'value' => round($bytes, $precision),
        'unit' => $units[$pow]
    ];
}

// Función para formatear bytes (formato string)
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Cálculos
    $quota = $row[QUOTA];
    $used = $row[USED];
    $used_24h = $row[USED_QUOTA_24H];
    
    // Formatear los valores
    $formattedQuota = formatBytes($quota);
    $formattedUsed = formatBytes($used);
    $formattedUsed24h = formatBytes($used_24h);
    
    // Calcular utilización y disponibilidad
    $utilizacion = ($quota > 0) ? ($used / $quota) * 100 : 0;
    $disponibilidad = formatBytes(max(0, $quota - $used));

    // Si no tiene cuota asignada ($quota es 0 o muy pequeño)
    if ($quota <= 1024) { // 1KB como umbral mínimo
        $response = [
            'error' => '1',
            'ConsumoUser' => $formattedUsed,  // Esto ya incluye el valor y la unidad
            'consumoSinCuota' => $formattedUsed,  // Redundante pero explícito
            'disponibilidad' => '0 B',
            'used_24h' => $formattedUsed24h,
            'nombre_institucion' => $row['nombre_institucion'] ?? 'Institución Desconocida',
            'ip' => $ip
        ];
    } else {
        // Respuesta normal cuando tiene cuota
        $response = [
            'error' => '',
            'utilizacion' => round($utilizacion, 1),
            'CuotaAsignada' => $formattedQuota,
            'disponibilidad' => $disponibilidad,
            'ConsumoUser' => $formattedUsed,
            'used_24h' => $formattedUsed24h,
            'nombre_institucion' => $row['nombre_institucion'] ?? 'Institución Desconocida',
            'ip' => $ip
        ];
    }
} else {
    // No se encontró la IP en la base de datos
    $response = [
        'error' => '2',
        'message' => 'No se encontraron datos para esta IP',
        'ip' => $ip
    ];
}

$conn->close();

// Devolver JSON
header('Content-Type: application/json');
echo json_encode($response);
?>