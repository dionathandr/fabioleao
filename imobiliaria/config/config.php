<?php
/**
 * FABIO LEÃO IMOBILIÁRIA
 * Configurações Gerais do Sistema
 */

// Iniciar sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configurações de erro (desativar em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// URLs Base
define('BASE_URL', 'https://fabioleao.com.br/'); // Altere para sua URL
define('ADMIN_URL', BASE_URL . 'admin/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('UPLOADS_URL', BASE_URL . 'uploads/');

// Caminhos
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');
define('ADMIN_PATH', ROOT_PATH . 'admin/');

// Configurações de Upload
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
define('THUMB_WIDTH', 400);
define('THUMB_HEIGHT', 300);

// Paginação
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// Incluir arquivos necessários
require_once CONFIG_PATH . 'database.php';
require_once INCLUDES_PATH . 'functions.php';

// Carregar configurações do banco de dados
function getSiteConfig($chave = null) {
    static $configs = null;
    
    if ($configs === null) {
        try {
            $db = getDB();
            $stmt = $db->query("SELECT chave, valor FROM configuracoes");
            $configs = [];
            while ($row = $stmt->fetch()) {
                $configs[$row['chave']] = $row['valor'];
            }
        } catch (Exception $e) {
            $configs = [];
        }
    }
    
    if ($chave !== null) {
        return $configs[$chave] ?? '';
    }
    
    return $configs;
}
