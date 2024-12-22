<?php

// Añade esto como primera línea, antes de los require_once
if ($_SERVER['REQUEST_URI'] !== '/' && (strpos($_SERVER['REQUEST_URI'], 'index.php') !== false)) {
    $baseUrl = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $baseUrl . '/');
    exit();
}

require_once('validaciones.php');
require_once 'connect.php';

$conexion = conexion();

$ip = $_SERVER['REMOTE_ADDR'];
estadoCuota($conexion, $ip, $quota, $used, $used_quota_24h, $nombre_institucion, $error);

$quota_new = round(($quota / 1024) / 1024, 2);
$used_new = round(($used / 1024) / 1024, 2);



// Para mostrar los datos de la parte su cuota asignada de
$CuotaAsignada = round(($quota / 1024) / 1024, 2);

// Cambiar a GB si supera los 1024 MB
if ($CuotaAsignada >= 1024) {
    $CuotaAsignada = round($CuotaAsignada / 1024, 2);
    $unidadCuotaAsignada = 'GB';
} elseif ($CuotaAsignada >= 1) {
    $unidadCuotaAsignada = 'MB';
} else {
    $CuotaAsignada = round($CuotaAsignada * 1024, 2);
    $unidadCuotaAsignada = 'KB';
}

// Para mostrar los datos de la parte - Consumo
$ConsumoUser = round(($used / 1024) / 1024, 2);

// Cambiar a GB si supera los 1024 MB
if ($ConsumoUser >= 1024) {
    $ConsumoUser = round($ConsumoUser / 1024, 2);
    $unidadConsumoUser = 'GB';
} elseif ($ConsumoUser >= 1) {
    $unidadConsumoUser = 'MB';
} else {
    $ConsumoUser = round($ConsumoUser * 1024, 2);
    $unidadConsumoUser = 'KB';
}


$used_24h = round(($used_quota_24h / 1024) / 1024, 2);

// Cambiar a GB si supera los 1024 MB
if ($used_24h >= 1024) {
    $used_24h = round($used_24h / 1024, 2);
    $unidad = 'GB';
} elseif ($used_24h >= 1) {
    $unidad = 'MB';
} else {
    $used_24h = round($used_24h * 1024, 2);
    $unidad = 'KB';
}

// Para mostrar los datos de la parte - Disponibilidad
$disponibilidad = $quota_new - $used_new;

if ($disponibilidad <= 0) {
    $disponibilidad = '0';
} else {
    // Ajustar a la unidad correspondiente
    if ($disponibilidad >= 1024) {
        $disponibilidad = round($disponibilidad / 1024, 2) . ' GB';
    } elseif ($disponibilidad >= 1) {
        $disponibilidad = round($disponibilidad, 2) . ' MB';
    } else {
        $disponibilidad = round($disponibilidad * 1024, 2) . ' KB';
    }
}



$utilizacion = ($quota_new != 0) ? ($used_new * 100 / $quota_new) : 0;

$resto = 100 - $utilizacion;

if ($utilizacion >= 100) {
    $utilizacion = '100';
}



function estadoCuota($conexion, $ip, &$quota = 0, &$used = 0, &$used_quota_24h = 0, &$nombre_institucion = '', &$error = '')
{
    $query = 'SELECT ' . QUOTA . ',' . USED . ',' . USED_QUOTA_24H . ',' . ORGANIZATION . ' FROM ' . TABLE_NAME . ' WHERE ' . CLIENTE_IP . '=\'' . $conexion->real_escape_string($ip) . '\'';
    $result = $conexion->query($query);

    if ($result) {
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $quota = $row[QUOTA];
            $used = $row[USED];
            $used_quota_24h = $row[USED_QUOTA_24H];
            $nombre_institucion = $row[ORGANIZATION];
            
            if (!$quota) {
                $error = '1';
            }
        }

        if ($result->num_rows == 0) {
            $error = '2';
        }
    } else {
        $error = $conexion->error;
    }
    $conexion->close();
}


// Funcion para los colores de la barra
function getColorByPercentage($percentage) {
    $colorStart = [76, 175, 80]; // Verde claro
    $colorMiddle = [255, 235, 59]; // Amarillo claro
    $colorEnd = [244, 67, 54]; // Rojo oscuro

    if ($percentage >= 100) {
        // Rojo oscuro para 100% o más
        return "rgb(" . implode(",", $colorEnd) . ")";
    } elseif ($percentage <= 50) {
        // Mezcla de verde a amarillo
        $ratio = $percentage / 50;
        $color = blendColors($colorStart, $colorMiddle, $ratio);
    } else {
        // Mezcla de amarillo a rojo
        $ratio = ($percentage - 50) / 50;
        $color = blendColors($colorMiddle, $colorEnd, $ratio);
    }

    return "rgb(" . implode(",", $color) . ")";
}

function blendColors($start, $end, $ratio) {
    $result = [];
    for ($i = 0; $i < 3; $i++) {
        $result[] = round($start[$i] * (1 - $ratio) + $end[$i] * $ratio);
    }
    return $result;
}




include 'header.php'; // Encabezado HTML

?>
