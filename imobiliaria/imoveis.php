<?php
/**
 * FABIO LEÃO IMOBILIÁRIA
 * Página de Listagem de Imóveis
 */

require_once 'config/config.php';

$currentPage = 'imoveis';
$pageTitle = 'Imóveis';

// Obter parâmetros de filtro
$filters = [
    'finalidade' => $_GET['finalidade'] ?? '',
    'tipo_id' => $_GET['tipo_id'] ?? '',
    'pais_id' => $_GET['pais_id'] ?? '',
    'estado_id' => $_GET['estado_id'] ?? '',
    'cidade_id' => $_GET['cidade_id'] ?? '',
    'quartos' => $_GET['quartos'] ?? '',
    'preco_min' => $_GET['preco_min'] ?? '',
    'preco_max' => $_GET['preco_max'] ?? '',
    'busca' => $_GET['busca'] ?? '',
];

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Buscar imóveis
$result = searchImoveis($filters, $page);
$imoveis = $result['imoveis'];
$pagination = $result['pagination'];

// Dados para filtros
$tiposImoveis = getTiposImoveis();
$paises = getPaises();

// Estados e cidades se já filtrados
$estados = $filters['pais_id'] ? getEstadosByPais($filters['pais_id']) : [];
$cidades = $filters['estado_id'] ? getCidadesByEstado($filters['estado_id']) : [];

// Título da página baseado no filtro
if ($filters['finalidade'] == 'venda') {
    $pageTitle = 'Imóveis à Venda';
} elseif ($filters['finalidade'] == 'aluguel') {
    $pageTitle = 'Imóveis para Alugar';
}

require_once INCLUDES_PATH . 'header.php';
?>

<!-- Page Header -->
<section class="property-detail" style="padding-bottom: 0;">
    <div class="container">
        <div class="section-header" style="text-align: left; margin-bottom: var(--spacing-lg);">
            <nav style="font-size: 0.875rem; color: var(--text-light); margin-bottom: var(--spacing-sm);">
                <a href="<?php echo BASE_URL; ?>">Início</a> / 
                <span><?php echo htmlspecialchars($pageTitle); ?></span>
            </nav>
            <h1 class="section-title" style="font-size: 2rem;"><?php echo htmlspecialchars($pageTitle); ?></h1>
            <p class="section-subtitle" style="text-align: left; margin: 0;">
                <?php echo $pagination['total']; ?> imóveis encontrados
            </p>
        </div>
    </div>
</section>

<!-- Filters -->
<section style="padding: var(--spacing-lg) 0;">
    <div class="container">
        <div class="search-box" style="margin-top: 0;">
            <form action="imoveis.php" method="GET" class="search-form">
                <div class="form-group">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="busca" class="form-control" placeholder="Título, bairro..." value="<?php echo htmlspecialchars($filters['busca']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Finalidade</label>
                    <select name="finalidade" class="form-control">
                        <option value="">Todas</option>
                        <option value="venda" <?php echo $filters['finalidade'] == 'venda' ? 'selected' : ''; ?>>Venda</option>
                        <option value="aluguel" <?php echo $filters['finalidade'] == 'aluguel' ? 'selected' : ''; ?>>Aluguel</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipo</label>
                    <select name="tipo_id" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($tiposImoveis as $tipo): ?>
                            <option value="<?php echo $tipo['id']; ?>" <?php echo $filters['tipo_id'] == $tipo['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tipo['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">País</label>
                    <select name="pais_id" id="pais_id" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($paises as $pais): ?>
                            <option value="<?php echo $pais['id']; ?>" <?php echo $filters['pais_id'] == $pais['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($pais['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="estado_id" id="estado_id" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?php echo $estado['id']; ?>" <?php echo $filters['estado_id'] == $estado['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($estado['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Cidade</label>
                    <select name="cidade_id" id="cidade_id" class="form-control">
                        <option value="">Todas</option>
                        <?php foreach ($cidades as $cidade): ?>
                            <option value="<?php echo $cidade['id']; ?>" <?php echo $filters['cidade_id'] == $cidade['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cidade['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Quartos</label>
                    <select name="quartos" class="form-control">
                        <option value="">Qualquer</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $filters['quartos'] == $i ? 'selected' : ''; ?>>
                                <?php echo $i; ?>+
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Preço Máximo</label>
                    <select name="preco_max" class="form-control">
                        <option value="">Sem Limite</option>
                        <option value="200000" <?php echo $filters['preco_max'] == '200000' ? 'selected' : ''; ?>>Até R$ 200.000</option>
                        <option value="500000" <?php echo $filters['preco_max'] == '500000' ? 'selected' : ''; ?>>Até R$ 500.000</option>
                        <option value="1000000" <?php echo $filters['preco_max'] == '1000000' ? 'selected' : ''; ?>>Até R$ 1.000.000</option>
                        <option value="2000000" <?php echo $filters['preco_max'] == '2000000' ? 'selected' : ''; ?>>Até R$ 2.000.000</option>
                        <option value="5000000" <?php echo $filters['preco_max'] == '5000000' ? 'selected' : ''; ?>>Até R$ 5.000.000</option>
                    </select>
                </div>

                <div class="search-submit">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Properties Grid -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <?php if (empty($imoveis)): ?>
            <div style="text-align: center; padding: var(--spacing-3xl);">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-light)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: var(--spacing-md);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <h3 style="color: var(--text-medium); margin-bottom: var(--spacing-sm);">Nenhum imóvel encontrado</h3>
                <p style="color: var(--text-light);">Tente ajustar os filtros de busca</p>
                <a href="imoveis.php" class="btn btn-outline" style="margin-top: var(--spacing-md);">Limpar Filtros</a>
            </div>
        <?php else: ?>
            <div class="properties-grid">
                <?php foreach ($imoveis as $imovel): ?>
                <article class="property-card">
                    <div class="property-image">
                        <?php 
                        $imgSrc = $imovel['imagem_principal'] 
                            ? UPLOADS_URL . $imovel['imagem_principal']
                            : ASSETS_URL . 'images/no-image.jpg';
                        ?>
                        <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($imovel['titulo']); ?>">
                        <span class="property-badge <?php echo $imovel['finalidade'] == 'aluguel' ? 'rent' : 'sale'; ?>">
                            <?php echo $imovel['finalidade'] == 'aluguel' ? 'Aluguel' : 'Venda'; ?>
                        </span>
                        <button class="property-favorite" data-id="<?php echo $imovel['id']; ?>" aria-label="Adicionar aos favoritos">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </button>
                    </div>
                    <div class="property-content">
                        <div class="property-location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span><?php echo htmlspecialchars($imovel['cidade_nome'] . ', ' . $imovel['pais_nome']); ?></span>
                        </div>
                        <h3 class="property-title">
                            <a href="imovel.php?id=<?php echo $imovel['id']; ?>"><?php echo htmlspecialchars($imovel['titulo']); ?></a>
                        </h3>
                        <div class="property-features">
                            <span class="property-feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"></path><path d="M2 8h18a2 2 0 0 1 2 2v10"></path><path d="M2 17h20"></path><path d="M6 8v9"></path></svg>
                                <?php echo $imovel['quartos']; ?> Quartos
                            </span>
                            <span class="property-feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"></path><line x1="10" y1="5" x2="8" y2="7"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg>
                                <?php echo $imovel['banheiros']; ?> Banheiros
                            </span>
                            <span class="property-feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
                                <?php echo formatArea($imovel['area_construida']); ?>
                            </span>
                        </div>
                        <div class="property-footer">
                            <span class="property-price">
                                <?php 
                                if ($imovel['finalidade'] == 'aluguel') {
                                    echo formatPrice($imovel['preco_aluguel']);
                                    echo '<small>/mês</small>';
                                } else {
                                    echo formatPrice($imovel['preco_venda']);
                                }
                                ?>
                            </span>
                            <span class="property-type"><?php echo htmlspecialchars($imovel['tipo_nome']); ?></span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="pagination">
                <?php if ($pagination['has_previous']): ?>
                    <a href="?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['current_page'] - 1])); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </a>
                <?php endif; ?>

                <?php 
                $start = max(1, $pagination['current_page'] - 2);
                $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                
                if ($start > 1): ?>
                    <a href="?<?php echo http_build_query(array_merge($filters, ['page' => 1])); ?>">1</a>
                    <?php if ($start > 2): ?><span>...</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <?php if ($i == $pagination['current_page']): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?<?php echo http_build_query(array_merge($filters, ['page' => $i])); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($end < $pagination['total_pages']): ?>
                    <?php if ($end < $pagination['total_pages'] - 1): ?><span>...</span><?php endif; ?>
                    <a href="?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['total_pages']])); ?>"><?php echo $pagination['total_pages']; ?></a>
                <?php endif; ?>

                <?php if ($pagination['has_next']): ?>
                    <a href="?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['current_page'] + 1])); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once INCLUDES_PATH . 'footer.php'; ?>
