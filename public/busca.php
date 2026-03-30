<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Imovel.php';
require_once __DIR__ . '/models/Categoria.php';

$db = new Database();
$conn = $db->getConnection();

$imovelModel = new Imovel($conn);
$categoriaModel = new Categoria($conn);

// Parâmetros de busca
$filtros = [
    'finalidade' => $_GET['finalidade'] ?? '',
    'categoria' => $_GET['categoria'] ?? '',
    'cidade' => $_GET['cidade'] ?? '',
    'bairro' => $_GET['bairro'] ?? '',
    'preco_min' => $_GET['preco_min'] ?? '',
    'preco_max' => $_GET['preco_max'] ?? '',
    'quartos' => $_GET['quartos'] ?? '',
    'banheiros' => $_GET['banheiros'] ?? '',
    'vagas' => $_GET['vagas'] ?? '',
    'area_min' => $_GET['area_min'] ?? '',
    'area_max' => $_GET['area_max'] ?? '',
    'ordenar' => $_GET['ordenar'] ?? 'recentes'
];

// Paginação
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$porPagina = 12;
$offset = ($pagina - 1) * $porPagina;

// Buscar imóveis
$resultado = $imovelModel->buscar($filtros, $porPagina, $offset);
$totalImoveis = $imovelModel->contarBusca($filtros);
$totalPaginas = ceil($totalImoveis / $porPagina);

// Categorias para filtro
$categorias = $categoriaModel->listar();

// Título da página
$tituloPage = 'Imóveis';
if ($filtros['finalidade'] == 'venda') $tituloPage = 'Imóveis à Venda';
elseif ($filtros['finalidade'] == 'aluguel') $tituloPage = 'Imóveis para Alugar';

function buildQuery($params, $exclude = []) {
    $q = [];
    foreach ($params as $k => $v) {
        if (!in_array($k, $exclude) && $v !== '') $q[$k] = $v;
    }
    return http_build_query($q);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPage) ?> - FABIOLEAO Imobiliária</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header header-white" id="header">
        <div class="container">
            <div class="header-inner">
                <a href="index.php" class="logo">
                    <div class="logo-icon">FL</div>
                    <span class="logo-text">FABIO<span>LEAO</span></span>
                </a>
                <nav class="nav-menu">
                    <a href="index.php" class="nav-link">Início</a>
                    <a href="busca.php?finalidade=venda" class="nav-link <?= $filtros['finalidade'] == 'venda' ? 'active' : '' ?>">Comprar</a>
                    <a href="busca.php?finalidade=aluguel" class="nav-link <?= $filtros['finalidade'] == 'aluguel' ? 'active' : '' ?>">Alugar</a>
                    <a href="busca.php" class="nav-link <?= empty($filtros['finalidade']) ? 'active' : '' ?>">Imóveis</a>
                    <a href="index.php#contato" class="nav-link">Contato</a>
                </nav>
                <div class="header-actions">
                    <a href="tel:+5511999999999" class="header-phone">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>(11) 99999-9999</span>
                    </a>
                    <a href="admin/login.php" class="btn btn-primary">Área Admin</a>
                </div>
                <button class="menu-toggle" id="menuToggle"><span></span><span></span><span></span></button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-menu-close" id="mobileMenuClose">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <nav class="mobile-nav">
            <a href="index.php" class="nav-link">Início</a>
            <a href="busca.php?finalidade=venda" class="nav-link">Comprar</a>
            <a href="busca.php?finalidade=aluguel" class="nav-link">Alugar</a>
            <a href="busca.php" class="nav-link">Imóveis</a>
            <a href="index.php#contato" class="nav-link">Contato</a>
        </nav>
    </div>

    <!-- Search Results Header -->
    <section class="search-results-header">
        <div class="container">
            <nav class="breadcrumb">
                <a href="index.php">Início</a>
                <span class="breadcrumb-separator">/</span>
                <span><?= htmlspecialchars($tituloPage) ?></span>
            </nav>
            <h1 class="search-results-title"><?= htmlspecialchars($tituloPage) ?></h1>
            <p class="search-results-count"><?= $totalImoveis ?> <?= $totalImoveis == 1 ? 'imóvel encontrado' : 'imóveis encontrados' ?></p>
        </div>
    </section>

    <!-- Filters Bar -->
    <div class="search-filters-bar">
        <div class="container">
            <form class="filters-bar-inner" method="GET" action="busca.php" id="filterForm">
                <div class="filters-group">
                    <select name="finalidade" class="filter-select" onchange="this.form.submit()">
                        <option value="">Finalidade</option>
                        <option value="venda" <?= $filtros['finalidade'] == 'venda' ? 'selected' : '' ?>>Comprar</option>
                        <option value="aluguel" <?= $filtros['finalidade'] == 'aluguel' ? 'selected' : '' ?>>Alugar</option>
                    </select>
                    <select name="categoria" class="filter-select" onchange="this.form.submit()">
                        <option value="">Tipo de Imóvel</option>
                        <?php if($categorias && $categorias->rowCount() > 0): ?>
                            <?php $categorias->execute(); while($cat = $categorias->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?= $cat['id'] ?>" <?= $filtros['categoria'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nome']) ?></option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                    <select name="quartos" class="filter-select" onchange="this.form.submit()">
                        <option value="">Quartos</option>
                        <option value="1" <?= $filtros['quartos'] == '1' ? 'selected' : '' ?>>1+</option>
                        <option value="2" <?= $filtros['quartos'] == '2' ? 'selected' : '' ?>>2+</option>
                        <option value="3" <?= $filtros['quartos'] == '3' ? 'selected' : '' ?>>3+</option>
                        <option value="4" <?= $filtros['quartos'] == '4' ? 'selected' : '' ?>>4+</option>
                    </select>
                    <select name="preco_max" class="filter-select" onchange="this.form.submit()">
                        <option value="">Preço Máximo</option>
                        <option value="200000" <?= $filtros['preco_max'] == '200000' ? 'selected' : '' ?>>Até R$ 200.000</option>
                        <option value="400000" <?= $filtros['preco_max'] == '400000' ? 'selected' : '' ?>>Até R$ 400.000</option>
                        <option value="600000" <?= $filtros['preco_max'] == '600000' ? 'selected' : '' ?>>Até R$ 600.000</option>
                        <option value="1000000" <?= $filtros['preco_max'] == '1000000' ? 'selected' : '' ?>>Até R$ 1.000.000</option>
                        <option value="2000000" <?= $filtros['preco_max'] == '2000000' ? 'selected' : '' ?>>Até R$ 2.000.000</option>
                    </select>
                    <input type="text" name="cidade" class="filter-select" placeholder="Cidade ou bairro" value="<?= htmlspecialchars($filtros['cidade']) ?>" style="min-width: 180px;">
                    <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                    <?php if(!empty(array_filter($filtros, fn($v) => $v !== '' && $v !== 'recentes'))): ?>
                        <a href="busca.php" class="btn btn-outline btn-sm">Limpar</a>
                    <?php endif; ?>
                </div>
                <div class="filters-sort">
                    <select name="ordenar" class="filter-select" onchange="this.form.submit()">
                        <option value="recentes" <?= $filtros['ordenar'] == 'recentes' ? 'selected' : '' ?>>Mais recentes</option>
                        <option value="menor_preco" <?= $filtros['ordenar'] == 'menor_preco' ? 'selected' : '' ?>>Menor preço</option>
                        <option value="maior_preco" <?= $filtros['ordenar'] == 'maior_preco' ? 'selected' : '' ?>>Maior preço</option>
                        <option value="maior_area" <?= $filtros['ordenar'] == 'maior_area' ? 'selected' : '' ?>>Maior área</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Results -->
    <section class="search-results-content">
        <div class="container">
            <?php if($resultado && $resultado->rowCount() > 0): ?>
                <div class="properties-grid search-results-grid">
                    <?php while($imovel = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
                        <article class="property-card">
                            <a href="imovel.php?id=<?= $imovel['id'] ?>" class="property-image">
                                <img src="<?= !empty($imovel['imagem_principal']) ? 'uploads/imoveis/' . $imovel['imagem_principal'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop' ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>" loading="lazy">
                                <div class="property-badges">
                                    <span class="property-badge <?= $imovel['finalidade'] == 'venda' ? 'sale' : 'rent' ?>"><?= $imovel['finalidade'] == 'venda' ? 'Venda' : 'Aluguel' ?></span>
                                    <?php if(!empty($imovel['destaque']) && $imovel['destaque']): ?>
                                        <span class="property-badge featured">Destaque</span>
                                    <?php endif; ?>
                                </div>
                                <button class="property-favorite" type="button" aria-label="Favoritar">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                </button>
                            </a>
                            <div class="property-content">
                                <span class="property-type"><?= htmlspecialchars($imovel['categoria_nome'] ?? 'Imóvel') ?></span>
                                <h3 class="property-title"><a href="imovel.php?id=<?= $imovel['id'] ?>"><?= htmlspecialchars($imovel['titulo']) ?></a></h3>
                                <div class="property-location">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <?= htmlspecialchars($imovel['bairro'] ?? '') ?><?= !empty($imovel['bairro']) && !empty($imovel['cidade']) ? ', ' : '' ?><?= htmlspecialchars($imovel['cidade'] ?? '') ?>
                                </div>
                                <div class="property-features">
                                    <?php if(!empty($imovel['quartos']) && $imovel['quartos'] > 0): ?>
                                    <div class="property-feature">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/></svg>
                                        <?= $imovel['quartos'] ?> <?= $imovel['quartos'] == 1 ? 'Quarto' : 'Quartos' ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!empty($imovel['banheiros']) && $imovel['banheiros'] > 0): ?>
                                    <div class="property-feature">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="2" x2="22" y1="12" y2="12"/></svg>
                                        <?= $imovel['banheiros'] ?> <?= $imovel['banheiros'] == 1 ? 'Banheiro' : 'Banheiros' ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!empty($imovel['area']) && $imovel['area'] > 0): ?>
                                    <div class="property-feature">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                                        <?= number_format($imovel['area'], 0, ',', '.') ?> m²
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="property-footer">
                                    <div class="property-price">
                                        R$ <?= number_format($imovel['preco'], 0, ',', '.') ?>
                                        <?php if($imovel['finalidade'] == 'aluguel'): ?><span>/mês</span><?php endif; ?>
                                    </div>
                                    <a href="imovel.php?id=<?= $imovel['id'] ?>" class="btn btn-sm btn-outline">Ver mais</a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if($totalPaginas > 1): ?>
                <nav class="pagination">
                    <?php if($pagina > 1): ?>
                        <a href="?<?= buildQuery(array_merge($filtros, ['pagina' => $pagina - 1])) ?>" class="pagination-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15,18 9,12 15,6"/></svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php 
                    $start = max(1, $pagina - 2);
                    $end = min($totalPaginas, $pagina + 2);
                    
                    if($start > 1): ?>
                        <a href="?<?= buildQuery(array_merge($filtros, ['pagina' => 1])) ?>" class="pagination-btn">1</a>
                        <?php if($start > 2): ?><span class="pagination-dots">...</span><?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for($i = $start; $i <= $end; $i++): ?>
                        <a href="?<?= buildQuery(array_merge($filtros, ['pagina' => $i])) ?>" class="pagination-btn <?= $i == $pagina ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    
                    <?php if($end < $totalPaginas): ?>
                        <?php if($end < $totalPaginas - 1): ?><span class="pagination-dots">...</span><?php endif; ?>
                        <a href="?<?= buildQuery(array_merge($filtros, ['pagina' => $totalPaginas])) ?>" class="pagination-btn"><?= $totalPaginas ?></a>
                    <?php endif; ?>
                    
                    <?php if($pagina < $totalPaginas): ?>
                        <a href="?<?= buildQuery(array_merge($filtros, ['pagina' => $pagina + 1])) ?>" class="pagination-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9,18 15,12 9,6"/></svg>
                        </a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                    <h3>Nenhum imóvel encontrado</h3>
                    <p>Tente ajustar os filtros ou realizar uma nova busca.</p>
                    <a href="busca.php" class="btn btn-primary">Ver todos os imóveis</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; <?= date('Y') ?> FABIOLEAO Imobiliária. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
