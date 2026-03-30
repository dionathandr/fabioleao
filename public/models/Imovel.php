<?php
require_once __DIR__ . '/../config/database.php';

class Imovel {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnection();
    }
    
    // Buscar todos os imóveis ativos
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT i.*, c.nome as categoria_nome, c.slug as categoria_slug 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE i.ativo = 1 
                ORDER BY i.destaque DESC, i.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        if ($limit) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar imóveis em destaque
    public function getDestaques($limit = 6) {
        $sql = "SELECT i.*, c.nome as categoria_nome 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE i.ativo = 1 AND i.destaque = 1 
                ORDER BY i.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar imóvel por ID
    public function getById($id) {
        $sql = "SELECT i.*, c.nome as categoria_nome, c.slug as categoria_slug 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE i.id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Buscar imóvel por slug
    public function getBySlug($slug) {
        $sql = "SELECT i.*, c.nome as categoria_nome, c.slug as categoria_slug 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE i.slug = :slug AND i.ativo = 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Incrementar visualizações
    public function incrementViews($id) {
        $sql = "UPDATE imoveis SET visualizacoes = visualizacoes + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Buscar com filtros
    public function search($filters = [], $limit = 12, $offset = 0) {
        $where = ["i.ativo = 1"];
        $params = [];
        
        // Tipo de negócio
        if (!empty($filters['tipo_negocio'])) {
            $where[] = "i.tipo_negocio = :tipo_negocio";
            $params[':tipo_negocio'] = $filters['tipo_negocio'];
        }
        
        // Categoria
        if (!empty($filters['categoria'])) {
            $where[] = "c.slug = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        // Localização
        if (!empty($filters['localizacao'])) {
            $where[] = "(i.cidade LIKE :localizacao OR i.bairro LIKE :localizacao OR i.endereco LIKE :localizacao)";
            $params[':localizacao'] = '%' . $filters['localizacao'] . '%';
        }
        
        // Palavra-chave
        if (!empty($filters['keyword'])) {
            $where[] = "(i.titulo LIKE :keyword OR i.descricao LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        // Preço mínimo
        if (!empty($filters['preco_min'])) {
            $where[] = "i.preco >= :preco_min";
            $params[':preco_min'] = $filters['preco_min'];
        }
        
        // Preço máximo
        if (!empty($filters['preco_max'])) {
            $where[] = "i.preco <= :preco_max";
            $params[':preco_max'] = $filters['preco_max'];
        }
        
        // Quartos
        if (!empty($filters['quartos'])) {
            $where[] = "i.quartos >= :quartos";
            $params[':quartos'] = $filters['quartos'];
        }
        
        // Banheiros
        if (!empty($filters['banheiros'])) {
            $where[] = "i.banheiros >= :banheiros";
            $params[':banheiros'] = $filters['banheiros'];
        }
        
        // Área mínima
        if (!empty($filters['area_min'])) {
            $where[] = "i.area_construida >= :area_min";
            $params[':area_min'] = $filters['area_min'];
        }
        
        // Área máxima
        if (!empty($filters['area_max'])) {
            $where[] = "i.area_construida <= :area_max";
            $params[':area_max'] = $filters['area_max'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT i.*, c.nome as categoria_nome, c.slug as categoria_slug 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE {$whereClause} 
                ORDER BY i.destaque DESC, i.created_at DESC 
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
    
    // Contar resultados com filtros
    public function countSearch($filters = []) {
        $where = ["i.ativo = 1"];
        $params = [];
        
        if (!empty($filters['tipo_negocio'])) {
            $where[] = "i.tipo_negocio = :tipo_negocio";
            $params[':tipo_negocio'] = $filters['tipo_negocio'];
        }
        
        if (!empty($filters['categoria'])) {
            $where[] = "c.slug = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        if (!empty($filters['localizacao'])) {
            $where[] = "(i.cidade LIKE :localizacao OR i.bairro LIKE :localizacao OR i.endereco LIKE :localizacao)";
            $params[':localizacao'] = '%' . $filters['localizacao'] . '%';
        }
        
        if (!empty($filters['keyword'])) {
            $where[] = "(i.titulo LIKE :keyword OR i.descricao LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        if (!empty($filters['preco_min'])) {
            $where[] = "i.preco >= :preco_min";
            $params[':preco_min'] = $filters['preco_min'];
        }
        
        if (!empty($filters['preco_max'])) {
            $where[] = "i.preco <= :preco_max";
            $params[':preco_max'] = $filters['preco_max'];
        }
        
        if (!empty($filters['quartos'])) {
            $where[] = "i.quartos >= :quartos";
            $params[':quartos'] = $filters['quartos'];
        }
        
        if (!empty($filters['banheiros'])) {
            $where[] = "i.banheiros >= :banheiros";
            $params[':banheiros'] = $filters['banheiros'];
        }
        
        if (!empty($filters['area_min'])) {
            $where[] = "i.area_construida >= :area_min";
            $params[':area_min'] = $filters['area_min'];
        }
        
        if (!empty($filters['area_max'])) {
            $where[] = "i.area_construida <= :area_max";
            $params[':area_max'] = $filters['area_max'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT COUNT(*) as total 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE {$whereClause}";
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    // Buscar imagens do imóvel
    public function getImages($imovelId) {
        $sql = "SELECT * FROM imoveis_imagens WHERE imovel_id = :imovel_id ORDER BY ordem ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':imovel_id', $imovelId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar amenidades do imóvel
    public function getAmenidades($imovelId) {
        $sql = "SELECT a.* FROM amenidades a 
                INNER JOIN imoveis_amenidades ia ON a.id = ia.amenidade_id 
                WHERE ia.imovel_id = :imovel_id AND a.ativo = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':imovel_id', $imovelId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar imóveis similares
    public function getSimilares($imovelId, $categoriaId, $limit = 4) {
        $sql = "SELECT i.*, c.nome as categoria_nome 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE i.id != :imovel_id 
                AND i.categoria_id = :categoria_id 
                AND i.ativo = 1 
                ORDER BY RAND() 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':imovel_id', $imovelId, PDO::PARAM_INT);
        $stmt->bindValue(':categoria_id', $categoriaId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar últimos imóveis
    public function getRecentes($limit = 5) {
        $sql = "SELECT i.*, c.nome as categoria_nome 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE i.ativo = 1 
                ORDER BY i.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // ==========================================
    // MÉTODOS PARA O ADMIN
    // ==========================================
    
    // Buscar todos (incluindo inativos)
    public function getAllAdmin($limit = 20, $offset = 0, $search = '') {
        $where = "1=1";
        $params = [];
        
        if (!empty($search)) {
            $where .= " AND (i.titulo LIKE :search OR i.cidade LIKE :search OR i.bairro LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        $sql = "SELECT i.*, c.nome as categoria_nome 
                FROM imoveis i 
                LEFT JOIN categorias c ON i.categoria_id = c.id 
                WHERE {$where}
                ORDER BY i.created_at DESC 
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
    
    // Contar total para paginação admin
    public function countAllAdmin($search = '') {
        $where = "1=1";
        $params = [];
        
        if (!empty($search)) {
            $where .= " AND (i.titulo LIKE :search OR i.cidade LIKE :search OR i.bairro LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        $sql = "SELECT COUNT(*) as total FROM imoveis i WHERE {$where}";
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    // Criar imóvel
    public function create($data) {
        $sql = "INSERT INTO imoveis (
            titulo, slug, descricao, descricao_curta, categoria_id, tipo_negocio, 
            preco, preco_condominio, preco_iptu, quartos, banheiros, suites, 
            vagas_garagem, area_total, area_construida, ano_construcao,
            endereco, numero, complemento, bairro, cidade, estado, cep,
            imagem_principal, destaque, status, ativo
        ) VALUES (
            :titulo, :slug, :descricao, :descricao_curta, :categoria_id, :tipo_negocio,
            :preco, :preco_condominio, :preco_iptu, :quartos, :banheiros, :suites,
            :vagas_garagem, :area_total, :area_construida, :ano_construcao,
            :endereco, :numero, :complemento, :bairro, :cidade, :estado, :cep,
            :imagem_principal, :destaque, :status, :ativo
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':titulo' => $data['titulo'],
            ':slug' => $this->createSlug($data['titulo']),
            ':descricao' => $data['descricao'] ?? null,
            ':descricao_curta' => $data['descricao_curta'] ?? null,
            ':categoria_id' => $data['categoria_id'] ?? null,
            ':tipo_negocio' => $data['tipo_negocio'] ?? 'venda',
            ':preco' => $data['preco'],
            ':preco_condominio' => $data['preco_condominio'] ?? null,
            ':preco_iptu' => $data['preco_iptu'] ?? null,
            ':quartos' => $data['quartos'] ?? 0,
            ':banheiros' => $data['banheiros'] ?? 0,
            ':suites' => $data['suites'] ?? 0,
            ':vagas_garagem' => $data['vagas_garagem'] ?? 0,
            ':area_total' => $data['area_total'] ?? null,
            ':area_construida' => $data['area_construida'] ?? null,
            ':ano_construcao' => $data['ano_construcao'] ?? null,
            ':endereco' => $data['endereco'] ?? null,
            ':numero' => $data['numero'] ?? null,
            ':complemento' => $data['complemento'] ?? null,
            ':bairro' => $data['bairro'] ?? null,
            ':cidade' => $data['cidade'] ?? null,
            ':estado' => $data['estado'] ?? null,
            ':cep' => $data['cep'] ?? null,
            ':imagem_principal' => $data['imagem_principal'] ?? null,
            ':destaque' => $data['destaque'] ?? 0,
            ':status' => $data['status'] ?? 'disponivel',
            ':ativo' => $data['ativo'] ?? 1
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    // Atualizar imóvel
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        $allowedFields = [
            'titulo', 'descricao', 'descricao_curta', 'categoria_id', 'tipo_negocio',
            'preco', 'preco_condominio', 'preco_iptu', 'quartos', 'banheiros', 'suites',
            'vagas_garagem', 'area_total', 'area_construida', 'ano_construcao',
            'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'estado', 'cep',
            'imagem_principal', 'destaque', 'status', 'ativo'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = :{$field}";
                $params[":{$field}"] = $data[$field];
            }
        }
        
        if (isset($data['titulo'])) {
            $fields[] = "slug = :slug";
            $params[':slug'] = $this->createSlug($data['titulo'], $id);
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE imoveis SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    // Deletar imóvel
    public function delete($id) {
        $sql = "DELETE FROM imoveis WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Criar slug único
    private function createSlug($titulo, $excludeId = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', 
            iconv('UTF-8', 'ASCII//TRANSLIT', $titulo))));
        
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
        $sql = "SELECT id FROM imoveis WHERE slug = :slug";
        $params = [':slug' => $slug];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }
    
    // Adicionar imagem à galeria
    public function addImage($imovelId, $imagem, $ordem = 0) {
        $sql = "INSERT INTO imoveis_imagens (imovel_id, imagem, ordem) VALUES (:imovel_id, :imagem, :ordem)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':imovel_id' => $imovelId,
            ':imagem' => $imagem,
            ':ordem' => $ordem
        ]);
    }
    
    // Remover imagem da galeria
    public function removeImage($imageId) {
        $sql = "DELETE FROM imoveis_imagens WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $imageId]);
    }
    
    // Atualizar amenidades do imóvel
    public function updateAmenidades($imovelId, $amenidadeIds = []) {
        // Remover todas as amenidades atuais
        $sql = "DELETE FROM imoveis_amenidades WHERE imovel_id = :imovel_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':imovel_id' => $imovelId]);
        
        // Adicionar novas amenidades
        if (!empty($amenidadeIds)) {
            $sql = "INSERT INTO imoveis_amenidades (imovel_id, amenidade_id) VALUES (:imovel_id, :amenidade_id)";
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($amenidadeIds as $amenidadeId) {
                $stmt->execute([
                    ':imovel_id' => $imovelId,
                    ':amenidade_id' => $amenidadeId
                ]);
            }
        }
        
        return true;
    }
    
    // Estatísticas para dashboard
    public function getStats() {
        $stats = [];
        
        // Total de imóveis
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM imoveis");
        $stats['total'] = $stmt->fetch()['total'];
        
        // Imóveis ativos
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM imoveis WHERE ativo = 1");
        $stats['ativos'] = $stmt->fetch()['total'];
        
        // Para venda
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM imoveis WHERE tipo_negocio = 'venda' AND ativo = 1");
        $stats['venda'] = $stmt->fetch()['total'];
        
        // Para aluguel
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM imoveis WHERE tipo_negocio = 'aluguel' AND ativo = 1");
        $stats['aluguel'] = $stmt->fetch()['total'];
        
        // Total de visualizações
        $stmt = $this->pdo->query("SELECT SUM(visualizacoes) as total FROM imoveis");
        $stats['visualizacoes'] = $stmt->fetch()['total'] ?? 0;
        
        return $stats;
    }
}
?>
