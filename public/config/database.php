<?php
// Configuração do Banco de Dados
define('DB_HOST', '127.0.0.1:3306');
define('DB_NAME', 'u856175843_imobiliaria');
define('DB_USER', 'u856175843_imobiliaria');
define('DB_PASS', '$b3Ma>sneinR');
define('DB_CHARSET', 'utf8mb4');

// Conexão com o banco de dados usando PDO
function getConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }
}

// URL base do site
define('BASE_URL', '/');

// Função para formatar preço em Real brasileiro
function formatPrice($price) {
    return 'R$ ' . number_format($price, 2, ',', '.');
}

// Função para limpar input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Função para upload de imagem
function uploadImage($file, $folder = 'properties') {
    $uploadDir = __DIR__ . '/../uploads/' . $folder . '/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'error' => 'Tipo de arquivo não permitido'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destination = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $filename, 'path' => 'uploads/' . $folder . '/' . $filename];
    }
    
    return ['success' => false, 'error' => 'Erro ao fazer upload'];
}

// Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Função para verificar se admin está logado
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
}

// Função para redirecionar
function redirect($url) {
    header("Location: " . $url);
    exit;
}
?>