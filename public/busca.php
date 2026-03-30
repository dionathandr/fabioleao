<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Imovel.php';
require_once __DIR__ . '/models/Categoria.php';

$imovelModel = new Imovel();
$categoriaModel = new Categoria();

// Obter parâmetros de busca
$filters = [
    'tipo_negocio' => isset($_GET['tipo_negocio']) ? sanitize($_GET['tipo_negocio']) : '',
    'categoria' => isset($_GET['categoria']) ? sanitize($_GET['categoria']) : '',
    'localizacao' => isset($_GET['localizacao']) ? sanitize($_GET['localizacao']) : '',
    'keyword' => isset($_GET['keyword']) ? sanitize($_GET['keyword']) : '',
    'preco_min' => isset($_GET['preco_min']) && is_numeric($_GET['preco_min']) ? (float)$_GET['preco_min'] : '',
    'preco_max' => isset($_GET['preco_max']) && is_numeric($_GET['preco_max']) ? (float)$_GET['preco_max'] : '',
    'quartos' => isset($_GET['quartos']) && is_numeric($_GET['quartos']) ? (int)$_GET['quartos'] : '',
    'banheiros' => isset($_GET['banheiros']) && is_numeric($_GET['banheiros']) ? (int)$_GET['banheiros'] : '',
    'area_min' => isset($_GET['area_min']) && is_numeric($_GET['area_min']) ? (float)$_GET['area_min'] : '',
    'area_max' => isset($_GET['area_max']) && is_numeric($_GET['area_max']) ? (float)$_GET['area_max'] : '',
];

// Paginação
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Buscar imóveis
$imoveis = $imovelModel->search($filters, $perPage, $offset);
$totalImoveis = $imovelModel->countSearch($filters);
$totalPages = ceil($totalImoveis / $perPage);

// Buscar categorias
$categorias = $categoriaModel->getAll();

// Obter categoria selecionada
$categoriaSelecionada = null;
if ($filters['categoria']) {
    $categoriaSelecionada = $categoriaModel->getBySlug($filters['categoria']);
}

// Construir título da página
$pageTitle = 'Imóveis';
if ($filters['tipo_negocio'] == 'venda') {
    $pageTitle = 'Imóveis à Venda';
} elseif ($filters['tipo_negocio'] == 'aluguel') {
    $pageTitle = 'Imóveis para Alugar';
}
if ($categoriaSelecionada) {
    $pageTitle = $categoriaSelecionada['nome'] . ' - ' . $pageTitle;
}

// Construir query string para paginação
function buildQueryString($params, $exclude = []) {
    $query = [];
    foreach ($params as $key => $value) {
        if (!in_array($key, $exclude) && $value !== '' && $value !== null) {
            $query[$key] = $value;
        }
    }
    return http_build_query($query);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($pageTitle) ?> - FABIOLEAO Imóveis</title>
    <meta name="description" content="<?= htmlspecialchars($pageTitle) ?>. Encontre o imóvel ideal para você.">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/search.css">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png">
</head>
<body>
    <!-- Preloader -->
    <div class="preload preload-container">
        <div class="preload-logo">
            <div class="spinner"></div>
            <i class="bi bi-house-door-fill"></i>
        </div>
    </div>
    
    <div id="wrapper">
        <!-- Header -->
        <header id="header" class="main-header fixed-header">
            <div class="header-lower">
                <div class="container">
                    <div class="inner-header">
                        <div class="inner-header-left">
                            <div class="logo-box">
                                <a href="index.php" class="logo">
                                    <span class="logo-icon"><i class="bi bi-house-door-fill"></i></span>
                                    <span class="logo-text">FABIOLEAO</span>
                                </a>
                            </div>
                            <nav class="main-menu">
                                <ul class="navigation">
                                    <li><a href="index.php">Início</a></li>
                                    <li class="dropdown2 <?= $filters['tipo_negocio'] ? 'current' : '' ?>">
                                        <a href="#">Imóveis</a>
                                        <ul>
                                            <li class="<?= $filters['tipo_negocio'] == 'venda' ? 'current' : '' ?>"><a href="busca.php?tipo_negocio=venda">Comprar</a></li>
                                            <li class="<?= $filters['tipo_negocio'] == 'aluguel' ? 'current' : '' ?>"><a href="busca.php?tipo_negocio=aluguel">Alugar</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown2 <?= $filters['categoria'] ? 'current' : '' ?>">
                                        <a href="#">Categorias</a>
                                        <ul>
                                            <?php foreach($categorias as $cat): ?>
                                            <li class="<?= $filters['categoria'] == $cat['slug'] ? 'current' : '' ?>"><a href="busca.php?categoria=<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['nome']) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                    <li><a href="index.php#contato">Contato</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="inner-header-right">
                            <a href="tel:+5511999999999" class="btn-contact">
                                <i class="bi bi-telephone"></i>
                                <span>(11) 99999-9999</span>
                            </a>
                            <a href="https://wa.me/5511999999999" target="_blank" class="tf-btn primary">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                        <div class="mobile-nav-toggler">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="mobile-menu">
                <div class="menu-backdrop"></div>
                <div class="close-btn"><i class="bi bi-x-lg"></i></div>
                <nav class="menu-box">
                    <div class="nav-logo">
                        <a href="index.php">
                            <span class="logo-icon"><i class="bi bi-house-door-fill"></i></span>
                            <span class="logo-text">FABIOLEAO</span>
                        </a>
                    </div>
                    <ul class="navigation-mobile">
                        <li><a href="index.php">Início</a></li>
                        <li><a href="busca.php?tipo_negocio=venda">Comprar</a></li>
                        <li><a href="busca.php?tipo_negocio=aluguel">Alugar</a></li>
                        <?php foreach($categorias as $cat): ?>
                        <li><a href="busca.php?categoria=<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['nome']) ?></a></li>
                        <?php endforeach; ?>
                        <li><a href="index.php#contato">Contato</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Início</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($pageTitle) ?></li>
                        </ol>
                    </nav>
                </div>
                <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
                <p class="page-subtitle"><?= number_format($totalImoveis, 0, ',', '.') ?> imóveis encontrados</p>
            </div>
        </section>
        
        <!-- Search Section -->
        <section class="search-section">
            <div class="container">
                <div class="search-wrapper">
                    <!-- Sidebar Filters -->
                    <aside class="search-sidebar">
                        <div class="sidebar-header">
                            <h3><i class="bi bi-funnel"></i> Filtros</h3>
                            <a href="busca.php" class="btn-clear">Limpar</a>
                        </div>
                        
                        <form action="busca.php" method="GET" class="filter-form" id="filterForm">
                            <!-- Tipo de Negócio -->
                            <div class="filter-group">
                                <label class="filter-label">Tipo de Negócio</label>
                                <div class="filter-options">
                                    <label class="filter-option">
                                        <input type="radio" name="tipo_negocio" value="" <?= $filters['tipo_negocio'] == '' ? 'checked' : '' ?>>
                                        <span>Todos</span>
                                    </label>
                                    <label class="filter-option">
                                        <input type="radio" name="tipo_negocio" value="venda" <?= $filters['tipo_negocio'] == 'venda' ? 'checked' : '' ?>>
                                        <span>Comprar</span>
                                    </label>
                                    <label class="filter-option">
                                        <input type="radio" name="tipo_negocio" value="aluguel" <?= $filters['tipo_negocio'] == 'aluguel' ? 'checked' : '' ?>>
                                        <span>Alugar</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Categoria -->
                            <div class="filter-group">
                                <label class="filter-label">Tipo de Imóvel</label>
                                <select name="categoria" class="form-select">
                                    <option value="">Todos os tipos</option>
                                    <?php foreach($categorias as $cat): ?>
                                    <option value="<?= $cat['slug'] ?>" <?= $filters['categoria'] == $cat['slug'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nome']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Localização -->
                            <div class="filter-group">
                                <label class="filter-label">Localização</label>
                                <input type="text" name="localizacao" class="form-control" placeholder="Cidade, bairro..." value="<?= htmlspecialchars($filters['localizacao']) ?>">
                            </div>
                            
                            <!-- Palavra-chave -->
                            <div class="filter-group">
                                <label class="filter-label">Palavra-chave</label>
                                <input type="text" name="keyword" class="form-control" placeholder="Ex: piscina, churrasqueira..." value="<?= htmlspecialchars($filters['keyword']) ?>">
                            </div>
                            
                            <!-- Preço -->
                            <div class="filter-group">
                                <label class="filter-label">Preço</label>
                                <div class="filter-row">
                                    <input type="number" name="preco_min" class="form-control" placeholder="Mínimo" value="<?= $filters['preco_min'] ?>">
                                    <span class="filter-separator">-</span>
                                    <input type="number" name="preco_max" class="form-control" placeholder="Máximo" value="<?= $filters['preco_max'] ?>">
                                </div>
                            </div>
                            
                            <!-- Quartos -->
                            <div class="filter-group">
                                <label class="filter-label">Quartos</label>
                                <div class="filter-buttons">
                                    <label class="filter-btn <?= $filters['quartos'] == '' ? 'active' : '' ?>">
                                        <input type="radio" name="quartos" value="" <?= $filters['quartos'] == '' ? 'checked' : '' ?>>
                                        <span>Todos</span>
                                    </label>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                    <label class="filter-btn <?= $filters['quartos'] == $i ? 'active' : '' ?>">
                                        <input type="radio" name="quartos" value="<?= $i ?>" <?= $filters['quartos'] == $i ? 'checked' : '' ?>>
                                        <span><?= $i ?>+</span>
                                    </label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <!-- Banheiros -->
                            <div class="filter-group">
                                <label class="filter-label">Banheiros</label>
                                <div class="filter-buttons">
                                    <label class="filter-btn <?= $filters['banheiros'] == '' ? 'active' : '' ?>">
                                        <input type="radio" name="banheiros" value="" <?= $filters['banheiros'] == '' ? 'checked' : '' ?>>
                                        <span>Todos</span>
                                    </label>
                                    <?php for($i = 1; $i <= 4; $i++): ?>
                                    <label class="filter-btn <?= $filters['banheiros'] == $i ? 'active' : '' ?>">
                                        <input type="radio" name="banheiros" value="<?= $i ?>" <?= $filters['banheiros'] == $i ? 'checked' : '' ?>>
                                        <span><?= $i ?>+</span>
                                    </label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <!-- Área -->
                            <div class="filter-group">
                                <label class="filter-label">Área (m²)</label>
                                <div class="filter-row">
                                    <input type="number" name="area_min" class="form-control" placeholder="Mínimo" value="<?= $filters['area_min'] ?>">
                                    <span class="filter-separator">-</span>
                                    <input type="number" name="area_max" class="form-control" placeholder="Máximo" value="<?= $filters['area_max'] ?>">
                                </div>
                            </div>
                            
                            <button type="submit" class="tf-btn primary w-100">
                                <i class="bi bi-search"></i> Buscar Imóveis
                            </button>
                        </form>
                    </aside>
                    
                    <!-- Results -->
                    <div class="search-results">
                        <!-- Results Header -->
                        <div class="results-header">
                            <div class="results-info">
                                <span>Mostrando <?= count($imoveis) ?> de <?= number_format($totalImoveis, 0, ',', '.') ?> resultados</span>
                            </div>
                            <div class="results-actions">
                                <button class="btn-view-toggle active" data-view="grid" title="Visualização em grade">
                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                </button>
                                <button class="btn-view-toggle" data-view="list" title="Visualização em lista">
                                    <i class="bi bi-list-ul"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Active Filters -->
                        <?php 
                        $activeFilters = array_filter($filters, function($v) { return $v !== ''; });
                        if (count($activeFilters) > 0): 
                        ?>
                        <div class="active-filters">
                            <span class="active-filters-label">Filtros ativos:</span>
                            <?php if($filters['tipo_negocio']): ?>
                            <a href="?<?= buildQueryString($filters, ['tipo_negocio']) ?>" class="filter-tag">
                                <?= $filters['tipo_negocio'] == 'venda' ? 'Comprar' : 'Alugar' ?>
                                <i class="bi bi-x"></i>
                            </a>
                            <?php endif; ?>
                            <?php if($filters['categoria']): ?>
                            <a href="?<?= buildQueryString($filters, ['categoria']) ?>" class="filter-tag">
                                <?= htmlspecialchars($categoriaSelecionada['nome'] ?? $filters['categoria']) ?>
                                <i class="bi bi-x"></i>
                            </a>
                            <?php endif; ?>
                            <?php if($filters['localizacao']): ?>
                            <a href="?<?= buildQueryString($filters, ['localizacao']) ?>" class="filter-tag">
                                <?= htmlspecialchars($filters['localizacao']) ?>
                                <i class="bi bi-x"></i>
                            </a>
                            <?php endif; ?>
                            <?php if($filters['keyword']): ?>
                            <a href="?<?= buildQueryString($filters, ['keyword']) ?>" class="filter-tag">
                                "<?= htmlspecialchars($filters['keyword']) ?>"
                                <i class="bi bi-x"></i>
                            </a>
                            <?php endif; ?>
                            <?php if($filters['preco_min'] || $filters['preco_max']): ?>
                            <a href="?<?= buildQueryString($filters, ['preco_min', 'preco_max']) ?>" class="filter-tag">
                                Preço: <?= $filters['preco_min'] ? formatPrice($filters['preco_min']) : '0' ?> - <?= $filters['preco_max'] ? formatPrice($filters['preco_max']) : 'Ilimitado' ?>
                                <i class="bi bi-x"></i>
                            </a>
                            <?php endif; ?>
                            <?php if($filters['quartos']): ?>
                            <a href="?<?= buildQueryString($filters, ['quartos']) ?>" class="filter-tag">
                                <?= $filters['quartos'] ?>+ quartos
                                <i class="bi bi-x"></i>
                            </a>
                            <?php endif; ?>
                            <?php if($filters['banheiros']): ?>
                            <a href="?<?= buildQueryString($filters, ['banheiros']) ?>" class="filter-tag">
                                <?= $filters['banheiros'] ?>+ banheiros
                                <i class="bi bi-x"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Properties Grid -->
                        <?php if(count($imoveis) > 0): ?>
                        <div class="properties-grid" id="propertiesGrid">
                            <?php foreach($imoveis as $imovel): ?>
                            <div class="property-card">
                                <div class="property-image">
                                    <a href="imovel.php?slug=<?= $imovel['slug'] ?>">
                                        <img src="assets/images/properties/<?= $imovel['imagem_principal'] ?: 'placeholder.jpg' ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>">
                                    </a>
                                    <div class="property-badges">
                                        <span class="badge badge-<?= $imovel['tipo_negocio'] ?>">
                                            <?= $imovel['tipo_negocio'] == 'venda' ? 'Venda' : 'Aluguel' ?>
                                        </span>
                                        <?php if($imovel['destaque']): ?>
                                        <span class="badge badge-featured">Destaque</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="property-actions">
                                        <button class="btn-action" title="Favoritar">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                        <button class="btn-action" title="Compartilhar">
                                            <i class="bi bi-share"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="property-content">
                                    <div class="property-category"><?= htmlspecialchars($imovel['categoria_nome'] ?? 'Imóvel') ?></div>
                                    <h3 class="property-title">
                                        <a href="imovel.php?slug=<?= $imovel['slug'] ?>"><?= htmlspecialchars($imovel['titulo']) ?></a>
                                    </h3>
                                    <div class="property-location">
                                        <i class="bi bi-geo-alt"></i>
                                        <span><?= htmlspecialchars($imovel['bairro'] . ', ' . $imovel['cidade']) ?></span>
                                    </div>
                                    <div class="property-features">
                                        <?php if($imovel['quartos'] > 0): ?>
                                        <div class="feature">
                                            <i class="bi bi-door-open"></i>
                                            <span><?= $imovel['quartos'] ?> Quartos</span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($imovel['banheiros'] > 0): ?>
                                        <div class="feature">
                                            <i class="bi bi-droplet"></i>
                                            <span><?= $imovel['banheiros'] ?> Banheiros</span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($imovel['area_construida'] > 0): ?>
                                        <div class="feature">
                                            <i class="bi bi-arrows-angle-expand"></i>
                                            <span><?= number_format($imovel['area_construida'], 0, ',', '.') ?> m²</span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="property-footer">
                                        <div class="property-price">
                                            <?= formatPrice($imovel['preco']) ?>
                                            <?php if($imovel['tipo_negocio'] == 'aluguel'): ?>
                                            <span class="price-period">/mês</span>
                                            <?php endif; ?>
                                        </div>
                                        <a href="imovel.php?slug=<?= $imovel['slug'] ?>" class="btn-details">
                                            Detalhes <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if($totalPages > 1): ?>
                        <nav class="pagination-wrapper">
                            <ul class="pagination">
                                <?php if($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= buildQueryString(array_merge($filters, ['page' => $page - 1])) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);
                                
                                if ($startPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= buildQueryString(array_merge($filters, ['page' => 1])) ?>">1</a>
                                </li>
                                <?php if($startPage > 2): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?<?= buildQueryString(array_merge($filters, ['page' => $i])) ?>"><?= $i ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if($endPage < $totalPages): ?>
                                <?php if($endPage < $totalPages - 1): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= buildQueryString(array_merge($filters, ['page' => $totalPages])) ?>"><?= $totalPages ?></a>
                                </li>
                                <?php endif; ?>
                                
                                <?php if($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= buildQueryString(array_merge($filters, ['page' => $page + 1])) ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                        
                        <?php else: ?>
                        <!-- No Results -->
                        <div class="no-results">
                            <div class="no-results-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <h3>Nenhum imóvel encontrado</h3>
                            <p>Tente ajustar os filtros ou realizar uma nova busca.</p>
                            <a href="busca.php" class="tf-btn primary">
                                <i class="bi bi-arrow-left"></i> Ver todos os imóveis
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="footer-top">
                <div class="container">
                    <div class="footer-grid">
                        <div class="footer-col">
                            <div class="footer-logo">
                                <span class="logo-icon"><i class="bi bi-house-door-fill"></i></span>
                                <span class="logo-text">FABIOLEAO</span>
                            </div>
                            <p>Sua imobiliária de confiança para encontrar o imóvel dos seus sonhos.</p>
                            <div class="footer-social">
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                                <a href="#"><i class="bi bi-youtube"></i></a>
                            </div>
                        </div>
                        
                        <div class="footer-col">
                            <h5>Links Rápidos</h5>
                            <ul>
                                <li><a href="index.php">Início</a></li>
                                <li><a href="busca.php?tipo_negocio=venda">Comprar</a></li>
                                <li><a href="busca.php?tipo_negocio=aluguel">Alugar</a></li>
                                <li><a href="index.php#contato">Contato</a></li>
                            </ul>
                        </div>
                        
                        <div class="footer-col">
                            <h5>Categorias</h5>
                            <ul>
                                <?php foreach(array_slice($categorias, 0, 6) as $cat): ?>
                                <li><a href="busca.php?categoria=<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['nome']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="footer-col">
                            <h5>Contato</h5>
                            <ul class="contact-list">
                                <li><i class="bi bi-geo-alt"></i> Av. Principal, 1000 - Centro, São Paulo - SP</li>
                                <li><i class="bi bi-telephone"></i> (11) 99999-9999</li>
                                <li><i class="bi bi-envelope"></i> contato@fabioleao.com.br</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="container">
                    <div class="footer-bottom-content">
                        <p>&copy; <?= date('Y') ?> FABIOLEAO Imóveis. Todos os direitos reservados.</p>
                        <div class="footer-links">
                            <a href="#">Política de Privacidade</a>
                            <a href="#">Termos de Uso</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- WhatsApp Float Button -->
        <a href="https://wa.me/5511999999999" target="_blank" class="whatsapp-float">
            <i class="bi bi-whatsapp"></i>
        </a>
        
        <!-- Back to Top -->
        <button class="back-to-top" id="backToTop">
            <i class="bi bi-arrow-up"></i>
        </button>
        
        <!-- Mobile Filter Toggle -->
        <button class="mobile-filter-toggle" id="mobileFilterToggle">
            <i class="bi bi-funnel"></i> Filtros
        </button>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Mobile filter toggle
        const mobileFilterToggle = document.getElementById('mobileFilterToggle');
        const searchSidebar = document.querySelector('.search-sidebar');
        
        if (mobileFilterToggle && searchSidebar) {
            mobileFilterToggle.addEventListener('click', function() {
                searchSidebar.classList.toggle('active');
                document.body.classList.toggle('filter-open');
            });
        }
        
        // View toggle
        const viewToggles = document.querySelectorAll('.btn-view-toggle');
        const propertiesGrid = document.getElementById('propertiesGrid');
        
        viewToggles.forEach(btn => {
            btn.addEventListener('click', function() {
                viewToggles.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.dataset.view;
                if (view === 'list') {
                    propertiesGrid.classList.add('list-view');
                } else {
                    propertiesGrid.classList.remove('list-view');
                }
            });
        });
        
        // Filter buttons active state
        const filterBtns = document.querySelectorAll('.filter-btn input');
        filterBtns.forEach(input => {
            input.addEventListener('change', function() {
                const group = this.closest('.filter-buttons');
                group.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                this.closest('.filter-btn').classList.add('active');
            });
        });
    </script>
</body>
</html>
