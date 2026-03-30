<?php
require_once __DIR__ . '/../config/database.php';

class Categoria {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnection();
    }
    
    // Buscar todas as categorias ativas
    public function getAll() {
        $sql = "SELECT * FROM categorias WHERE ativo = 1 ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar todas (incluindo inativas) para admin
    public function getAllAdmin() {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM imoveis WHERE categoria_id = c.id) as total_imoveis 
                FROM categorias c 
                ORDER BY c.nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar por ID
    public function getById($id) {
        $sql = "SELECT * FROM categorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Buscar por slug
    public function getBySlug($slug) {
        $sql = "SELECT * FROM categorias WHERE slug = :slug AND ativo = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Criar categoria
    public function create($data) {
        $sql = "INSERT INTO categorias (nome, slug, icone, ativo) VALUES (:nome, :slug, :icone, :ativo)";
        $stmt = $this->pdo->prepare($sql);
        
        $slug = $this->createSlug($data['nome']);
        
        $stmt->execute([
            ':nome' => $data['nome'],
            ':slug' => $slug,
            ':icone' => $data['icone'] ?? null,
            ':ativo' => $data['ativo'] ?? 1
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    // Atualizar categoria
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['nome'])) {
            $fields[] = "nome = :nome";
            $params[':nome'] = $data['nome'];
            $fields[] = "slug = :slug";
            $params[':slug'] = $this->createSlug($data['nome'], $id);
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
        
        $sql = "UPDATE categorias SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    // Deletar categoria
    public function delete($id) {
        // Verificar se há imóveis vinculados
        $sql = "SELECT COUNT(*) as total FROM imoveis WHERE categoria_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result['total'] > 0) {
            return ['success' => false, 'error' => 'Não é possível excluir. Existem imóveis vinculados a esta categoria.'];
        }
        
        $sql = "DELETE FROM categorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return ['success' => true];
    }
    
    // Criar slug único
    private function createSlug($nome, $excludeId = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', 
            iconv('UTF-8', 'ASCII//TRANSLIT', $nome))));
        
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    // Verificar se slug existe
    private function slugExists($slug, $excludeId = null) {
        $sql = "SELECT id FROM categorias WHERE slug = :slug";
        $params = [':slug' => $slug];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }
}
?>
