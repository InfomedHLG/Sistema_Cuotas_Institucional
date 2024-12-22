<?php

function validaIp($ip){
    $ipPattern = '^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$';
    if (preg_match('/'.$ipPattern.'/', $ip, $arr)){
        $biger = 0;
        $i = 0;
        while ((!$biger) && ($i<=count($arr)) ){
            if ($arr[$i] > 255){
                $biger = 1;
                return false;
            }
            $i++;
        }
        return true;
    }else
    {
        return false;
    }
}

function validaCuota($quota){
    $idPattern = '^[0-9]+$';
    if (preg_match('/'.$idPattern.'/', $quota)){
        return true;
    }
    else{
        return false;
    }
}


// Añade esta función a tu archivo validaciones.php
function generarCodigoSeguro($ip) {
    // Genera un código único de 12 caracteres
    $codigo = bin2hex(random_bytes(6));
    
    // Guarda el código en una sesión con tiempo de vida limitado
    if (!isset($_SESSION)) {
        session_start();
    }
    
    $_SESSION['codigos'][$codigo] = [
        'ip' => $ip,
        'timestamp' => time()
    ];
    
    // Limpia códigos antiguos (más de 1 hora)
    foreach ($_SESSION['codigos'] as $key => $data) {
        if (time() - $data['timestamp'] > 3600) {
            unset($_SESSION['codigos'][$key]);
        }
    }
    
    return $codigo;
}

function obtenerIpDesdeCodigo($codigo) {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (isset($_SESSION['codigos'][$codigo])) {
        return $_SESSION['codigos'][$codigo]['ip'];
    }
    
    return false;
}


?>
