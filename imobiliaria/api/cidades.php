<?php
/**
 * API - Buscar Cidades por Estado
 */

require_once '../config/config.php';

header('Content-Type: application/json');

$estadoId = isset($_GET['estado_id']) ? intval($_GET['estado_id']) : 0;

if (!$estadoId) {
    echo json_encode([]);
    exit;
}

$cidades = getCidadesByEstado($estadoId);
echo json_encode($cidades);
