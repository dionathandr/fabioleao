<?php
require_once __DIR__ . '/../config/database.php';

class Contato {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnection();
    }
    
    // Criar novo contato/lead
    public function create($data) {
        $sql = "INSERT INTO contatos (imovel_id, nome, email, telefone, mensagem) 
                VALUES (:imovel_id, :nome, :email, :telefone, :mensagem)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':imovel_id' => $data['imovel_id'] ?? null,
            ':nome' => $data['nome'],
            ':email' => $data['email'] ?? null,
            ':telefone' => $data['telefone'] ?? null,
            ':mensagem' => $data['mensagem'] ?? null
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    // Buscar todos os contatos para admin
    public function getAll($limit = 50, $offset = 0, $lido = null) {
        $where = "1=1";
        $params = [];
        
        if ($lido !== null) {
            $where .= " AND c.lido = :lido";
            $params[':lido'] = $lido;
        }
        
        $sql = "SELECT c.*, i.titulo as imovel_titulo, i.slug as imovel_slug 
                FROM contatos c 
                LEFT JOIN imoveis i ON c.imovel_id = i.id 
                WHERE {$where}
                ORDER BY c.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar por ID
    public function getById($id) {
        $sql = "SELECT c.*, i.titulo as imovel_titulo, i.slug as imovel_slug 
                FROM contatos c 
                LEFT JOIN imoveis i ON c.imovel_id = i.id 
                WHERE c.id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Marcar como lido
    public function markAsRead($id) {
        $sql = "UPDATE contatos SET lido = 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Marcar como não lido
    public function markAsUnread($id) {
        $sql = "UPDATE contatos SET lido = 0 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Deletar contato
    public function delete($id) {
        $sql = "DELETE FROM contatos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Contar não lidos
    public function countUnread() {
        $sql = "SELECT COUNT(*) as total FROM contatos WHERE lido = 0";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    // Contar total
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM contatos";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    // Estatísticas
    public function getStats() {
        $stats = [];
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM contatos");
        $stats['total'] = $stmt->fetch()['total'];
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM contatos WHERE lido = 0");
        $stats['nao_lidos'] = $stmt->fetch()['total'];
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM contatos WHERE DATE(created_at) = CURDATE()");
        $stats['hoje'] = $stmt->fetch()['total'];
        
        return $stats;
    }
}
?>
