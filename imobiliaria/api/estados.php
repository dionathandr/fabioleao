<?php
/**
 * API - Buscar Estados por País
 */

require_once '../config/config.php';

header('Content-Type: application/json');

$paisId = isset($_GET['pais_id']) ? intval($_GET['pais_id']) : 0;

if (!$paisId) {
    echo json_encode([]);
    exit;
}

$estados = getEstadosByPais($paisId);
echo json_encode($estados);
