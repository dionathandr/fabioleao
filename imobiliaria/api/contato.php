<?php
/**
 * API - Enviar Contato/Lead
 */

require_once '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

// Validar dados
$imovelId = isset($_POST['imovel_id']) ? intval($_POST['imovel_id']) : null;
$nome = isset($_POST['nome']) ? sanitize($_POST['nome']) : '';
$email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
$telefone = isset($_POST['telefone']) ? sanitize($_POST['telefone']) : '';
$mensagem = isset($_POST['mensagem']) ? sanitize($_POST['mensagem']) : '';

// Validações
if (empty($nome)) {
    echo json_encode(['success' => false, 'error' => 'Nome é obrigatório']);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'E-mail inválido']);
    exit;
}

try {
    $db = getDB();
    
    $sql = "INSERT INTO contatos (imovel_id, nome, email, telefone, mensagem) VALUES (:imovel_id, :nome, :email, :telefone, :mensagem)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'imovel_id' => $imovelId,
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone,
        'mensagem' => $mensagem
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erro ao enviar mensagem']);
}
