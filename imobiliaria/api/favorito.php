<?php
/**
 * API - Gerenciar Favoritos
 */

require_once '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$imovelId = isset($data['imovel_id']) ? intval($data['imovel_id']) : 0;

if (!$imovelId) {
    echo json_encode(['success' => false, 'error' => 'Imóvel inválido']);
    exit;
}

$sessionId = session_id();

try {
    $db = getDB();
    
    // Verificar se já é favorito
    $stmt = $db->prepare("SELECT id FROM favoritos WHERE session_id = :session_id AND imovel_id = :imovel_id");
    $stmt->execute(['session_id' => $sessionId, 'imovel_id' => $imovelId]);
    $favorito = $stmt->fetch();
    
    if ($favorito) {
        // Remover favorito
        $stmt = $db->prepare("DELETE FROM favoritos WHERE id = :id");
        $stmt->execute(['id' => $favorito['id']]);
        echo json_encode(['success' => true, 'action' => 'removed']);
    } else {
        // Adicionar favorito
        $stmt = $db->prepare("INSERT INTO favoritos (session_id, imovel_id) VALUES (:session_id, :imovel_id)");
        $stmt->execute(['session_id' => $sessionId, 'imovel_id' => $imovelId]);
        echo json_encode(['success' => true, 'action' => 'added']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erro ao processar']);
}
