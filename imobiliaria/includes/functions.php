<?php
/**
 * FABIO LEÃO IMOBILIÁRIA
 * Funções Auxiliares
 */

// Formatar preço
function formatPrice($value, $currency = 'R$') {
    if (!$value) return 'Consulte';
    return $currency . ' ' . number_format($value, 2, ',', '.');
}

// Formatar área
function formatArea($value) {
    if (!$value) return '-';
    return number_format($value, 2, ',', '.') . ' m²';
}

// Limpar entrada de dados
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Gerar slug
function generateSlug($string) {
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $slug = preg_replace('/[^a-zA-Z0-9]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = strtolower(trim($slug, '-'));
    return $slug;
}

// Verificar se usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Verificar nível de acesso
function hasAccess($nivel) {
    if (!isLoggedIn()) return false;
    $niveis = ['corretor' => 1, 'editor' => 2, 'admin' => 3];
    $userNivel = $_SESSION['user_nivel'] ?? 'corretor';
    return ($niveis[$userNivel] ?? 0) >= ($niveis[$nivel] ?? 0);
}

// Redirecionar
function redirect($url) {
    header("Location: $url");
    exit;
}

// Mensagens Flash
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Upload de imagem
function uploadImage($file, $folder = 'imoveis') {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Erro no upload'];
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'error' => 'Extensão não permitida'];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'Arquivo muito grande'];
    }

    $targetDir = UPLOADS_PATH . $folder . '/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $fileName = uniqid() . '_' . time() . '.' . $extension;
    $targetPath = $targetDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Criar thumbnail
        createThumbnail($targetPath, $targetDir . 'thumb_' . $fileName);
        
        return [
            'success' => true,
            'filename' => $fileName,
            'path' => $folder . '/' . $fileName
        ];
    }

    return ['success' => false, 'error' => 'Falha ao mover arquivo'];
}

// Criar thumbnail
function createThumbnail($source, $destination, $width = THUMB_WIDTH, $height = THUMB_HEIGHT) {
    $imageInfo = getimagesize($source);
    if (!$imageInfo) return false;

    $mime = $imageInfo['mime'];
    
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($source);
            break;
        default:
            return false;
    }

    $origWidth = imagesx($image);
    $origHeight = imagesy($image);

    // Calcular proporção
    $ratio = min($width / $origWidth, $height / $origHeight);
    $newWidth = round($origWidth * $ratio);
    $newHeight = round($origHeight * $ratio);

    $thumb = imagecreatetruecolor($newWidth, $newHeight);

    // Preservar transparência para PNG
    if ($mime == 'image/png') {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    }

    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($thumb, $destination, 85);
            break;
        case 'image/png':
            imagepng($thumb, $destination, 8);
            break;
        case 'image/gif':
            imagegif($thumb, $destination);
            break;
        case 'image/webp':
            imagewebp($thumb, $destination, 85);
            break;
    }

    imagedestroy($image);
    imagedestroy($thumb);

    return true;
}

// Deletar imagem
function deleteImage($path) {
    $fullPath = UPLOADS_PATH . $path;
    $thumbPath = UPLOADS_PATH . dirname($path) . '/thumb_' . basename($path);
    
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
    if (file_exists($thumbPath)) {
        unlink($thumbPath);
    }
}

// Paginação
function paginate($total, $perPage, $currentPage) {
    $totalPages = ceil($total / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;

    return [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'has_previous' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages
    ];
}

// Buscar imóveis com filtros
function searchImoveis($filters = [], $page = 1, $perPage = ITEMS_PER_PAGE) {
    $db = getDB();
    
    $where = ['i.ativo = 1'];
    $params = [];

    if (!empty($filters['finalidade'])) {
        $where[] = "(i.finalidade = :finalidade OR i.finalidade = 'ambos')";
        $params['finalidade'] = $filters['finalidade'];
    }

    if (!empty($filters['tipo_id'])) {
        $where[] = 'i.tipo_id = :tipo_id';
        $params['tipo_id'] = $filters['tipo_id'];
    }

    if (!empty($filters['pais_id'])) {
        $where[] = 'i.pais_id = :pais_id';
        $params['pais_id'] = $filters['pais_id'];
    }

    if (!empty($filters['estado_id'])) {
        $where[] = 'i.estado_id = :estado_id';
        $params['estado_id'] = $filters['estado_id'];
    }

    if (!empty($filters['cidade_id'])) {
        $where[] = 'i.cidade_id = :cidade_id';
        $params['cidade_id'] = $filters['cidade_id'];
    }

    if (!empty($filters['quartos'])) {
        $where[] = 'i.quartos >= :quartos';
        $params['quartos'] = $filters['quartos'];
    }

    if (!empty($filters['preco_min'])) {
        $where[] = '(i.preco_venda >= :preco_min OR i.preco_aluguel >= :preco_min)';
        $params['preco_min'] = $filters['preco_min'];
    }

    if (!empty($filters['preco_max'])) {
        $where[] = '(i.preco_venda <= :preco_max OR i.preco_aluguel <= :preco_max)';
        $params['preco_max'] = $filters['preco_max'];
    }

    if (!empty($filters['busca'])) {
        $where[] = '(i.titulo LIKE :busca OR i.descricao LIKE :busca OR i.bairro LIKE :busca)';
        $params['busca'] = '%' . $filters['busca'] . '%';
    }

    $whereClause = implode(' AND ', $where);

    // Contar total
    $countSql = "SELECT COUNT(*) FROM imoveis i WHERE $whereClause";
    $stmt = $db->prepare($countSql);
    $stmt->execute($params);
    $total = $stmt->fetchColumn();

    // Paginação
    $pagination = paginate($total, $perPage, $page);

    // Buscar imóveis
    $sql = "SELECT i.*, t.nome as tipo_nome, p.nome as pais_nome, e.nome as estado_nome, c.nome as cidade_nome,
            (SELECT caminho FROM imoveis_imagens WHERE imovel_id = i.id AND principal = 1 LIMIT 1) as imagem_principal
            FROM imoveis i
            LEFT JOIN tipos_imoveis t ON i.tipo_id = t.id
            LEFT JOIN paises p ON i.pais_id = p.id
            LEFT JOIN estados e ON i.estado_id = e.id
            LEFT JOIN cidades c ON i.cidade_id = c.id
            WHERE $whereClause
            ORDER BY i.destaque DESC, i.created_at DESC
            LIMIT :offset, :limit";

    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->execute();

    return [
        'imoveis' => $stmt->fetchAll(),
        'pagination' => $pagination
    ];
}

// Buscar imóvel por ID
function getImovelById($id) {
    $db = getDB();
    
    $sql = "SELECT i.*, t.nome as tipo_nome, p.nome as pais_nome, e.nome as estado_nome, c.nome as cidade_nome
            FROM imoveis i
            LEFT JOIN tipos_imoveis t ON i.tipo_id = t.id
            LEFT JOIN paises p ON i.pais_id = p.id
            LEFT JOIN estados e ON i.estado_id = e.id
            LEFT JOIN cidades c ON i.cidade_id = c.id
            WHERE i.id = :id AND i.ativo = 1";

    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $imovel = $stmt->fetch();

    if ($imovel) {
        // Buscar imagens
        $stmt = $db->prepare("SELECT * FROM imoveis_imagens WHERE imovel_id = :id ORDER BY principal DESC, ordem ASC");
        $stmt->execute(['id' => $id]);
        $imovel['imagens'] = $stmt->fetchAll();

        // Buscar características
        $stmt = $db->prepare("SELECT c.* FROM caracteristicas c 
                              INNER JOIN imoveis_caracteristicas ic ON c.id = ic.caracteristica_id 
                              WHERE ic.imovel_id = :id");
        $stmt->execute(['id' => $id]);
        $imovel['caracteristicas'] = $stmt->fetchAll();

        // Incrementar visualizações
        $db->prepare("UPDATE imoveis SET visualizacoes = visualizacoes + 1 WHERE id = :id")->execute(['id' => $id]);
    }

    return $imovel;
}

// Buscar imóveis em destaque
function getImoveisDestaque($limit = 6) {
    $db = getDB();
    
    $sql = "SELECT i.*, t.nome as tipo_nome, p.nome as pais_nome, c.nome as cidade_nome,
            (SELECT caminho FROM imoveis_imagens WHERE imovel_id = i.id AND principal = 1 LIMIT 1) as imagem_principal
            FROM imoveis i
            LEFT JOIN tipos_imoveis t ON i.tipo_id = t.id
            LEFT JOIN paises p ON i.pais_id = p.id
            LEFT JOIN cidades c ON i.cidade_id = c.id
            WHERE i.ativo = 1 AND i.destaque = 1
            ORDER BY i.created_at DESC
            LIMIT :limit";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

// Listar tipos de imóveis
function getTiposImoveis() {
    $db = getDB();
    return $db->query("SELECT * FROM tipos_imoveis ORDER BY nome")->fetchAll();
}

// Listar países
function getPaises() {
    $db = getDB();
    return $db->query("SELECT * FROM paises ORDER BY nome")->fetchAll();
}

// Listar estados por país
function getEstadosByPais($paisId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM estados WHERE pais_id = :pais_id ORDER BY nome");
    $stmt->execute(['pais_id' => $paisId]);
    return $stmt->fetchAll();
}

// Listar cidades por estado
function getCidadesByEstado($estadoId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM cidades WHERE estado_id = :estado_id ORDER BY nome");
    $stmt->execute(['estado_id' => $estadoId]);
    return $stmt->fetchAll();
}

// Listar características
function getCaracteristicas() {
    $db = getDB();
    return $db->query("SELECT * FROM caracteristicas ORDER BY nome")->fetchAll();
}

// Imóveis similares
function getImoveisSimilares($imovel, $limit = 4) {
    $db = getDB();
    
    $sql = "SELECT i.*, t.nome as tipo_nome, c.nome as cidade_nome,
            (SELECT caminho FROM imoveis_imagens WHERE imovel_id = i.id AND principal = 1 LIMIT 1) as imagem_principal
            FROM imoveis i
            LEFT JOIN tipos_imoveis t ON i.tipo_id = t.id
            LEFT JOIN cidades c ON i.cidade_id = c.id
            WHERE i.ativo = 1 
            AND i.id != :id 
            AND (i.tipo_id = :tipo_id OR i.cidade_id = :cidade_id)
            ORDER BY RAND()
            LIMIT :limit";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $imovel['id'], PDO::PARAM_INT);
    $stmt->bindValue(':tipo_id', $imovel['tipo_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cidade_id', $imovel['cidade_id'], PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

// Estatísticas para o dashboard admin
function getDashboardStats() {
    $db = getDB();
    
    return [
        'total_imoveis' => $db->query("SELECT COUNT(*) FROM imoveis WHERE ativo = 1")->fetchColumn(),
        'total_venda' => $db->query("SELECT COUNT(*) FROM imoveis WHERE ativo = 1 AND finalidade IN ('venda', 'ambos')")->fetchColumn(),
        'total_aluguel' => $db->query("SELECT COUNT(*) FROM imoveis WHERE ativo = 1 AND finalidade IN ('aluguel', 'ambos')")->fetchColumn(),
        'total_contatos' => $db->query("SELECT COUNT(*) FROM contatos WHERE lido = 0")->fetchColumn(),
        'total_visualizacoes' => $db->query("SELECT SUM(visualizacoes) FROM imoveis")->fetchColumn() ?: 0,
    ];
}
