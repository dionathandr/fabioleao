<?php
require_once __DIR__ . '/../config/database.php';

class Amenidade {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnection();
    }
    
    // Buscar todas as amenidades ativas
    public function getAll() {
        $sql = "SELECT * FROM amenidades WHERE ativo = 1 ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar todas para admin
    public function getAllAdmin() {
        $sql = "SELECT a.*, 
                (SELECT COUNT(*) FROM imoveis_amenidades WHERE amenidade_id = a.id) as total_imoveis 
                FROM amenidades a 
                ORDER BY a.nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar por ID
    public function getById($id) {
        $sql = "SELECT * FROM amenidades WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Criar amenidade
    public function create($data) {
        $sql = "INSERT INTO amenidades (nome, icone, ativo) VALUES (:nome, :icone, :ativo)";
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':nome' => $data['nome'],
            ':icone' => $data['icone'] ?? null,
            ':ativo' => $data['ativo'] ?? 1
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    // Atualizar amenidade
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['nome'])) {
            $fields[] = "nome = :nome";
            $params[':nome'] = $data['nome'];
        }
        
        if (isset($data['icone'])) {
            $fields[] = "icone = :icone";
            $params[':icone'] = $data['icone'];
        }
        
        if (isset($data['ativo'])) {
            $fields[] = "ativo = :ativo";
            $params[':ativo'] = $data['ativo'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE amenidades SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    // Deletar amenidade
    public function delete($id) {
        // Primeiro remove as relações
        $sql = "DELETE FROM imoveis_amenidades WHERE amenidade_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        // Depois remove a amenidade
        $sql = "DELETE FROM amenidades WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Buscar amenidades de um imóvel
    public function getByImovel($imovelId) {
        $sql = "SELECT a.* FROM amenidades a 
                INNER JOIN imoveis_amenidades ia ON a.id = ia.amenidade_id 
                WHERE ia.imovel_id = :imovel_id AND a.ativo = 1 
                ORDER BY a.nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':imovel_id', $imovelId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
