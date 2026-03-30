<?php
/**
 * FABIO LEÃO IMOBILIÁRIA
 * Configuração do Banco de Dados
 */

// Configurações do Banco de Dados
define('DB_HOST', '127.0.0.1:3306');
define('DB_NAME', 'u856175843_imobiliaria');
define('DB_USER', 'u856175843_imobiliaria'); // Altere para seu usuário
define('DB_PASS', '$b3Ma>sneinR');   // Altere para sua senha
define('DB_CHARSET', 'utf8mb4');

// Classe de Conexão com o Banco
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Prevenir clonagem
    private function __clone() {}

    // Prevenir deserialização
    public function __wakeup() {
        throw new Exception("Não é permitido deserializar singleton");
    }
}

// Função auxiliar para obter conexão
function getDB() {
    return Database::getInstance()->getConnection();
}
