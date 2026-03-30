<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Imovel.php';
require_once __DIR__ . '/models/Categoria.php';

$imovelModel = new Imovel();
$categoriaModel = new Categoria();

// Obter o slug do imóvel
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: index.php');
    exit;
}

// Buscar imóvel
$imovel = $imovelModel->getBySlug($slug);

if (!$imovel) {
    header('Location: index.php');
    exit;
}

// Incrementar visualizações
$imovelModel->incrementViews($imovel['id']);

// Buscar imagens da galeria
$imagens = $imovelModel->getImages($imovel['id']);

// Buscar amenidades
$amenidades = $imovelModel->getAmenidades($imovel['id']);

// Buscar imóveis similares
$similares = $imovelModel->getSimilares($imovel['id'], $imovel['categoria_id'], 4);

// Buscar categorias para o menu
$categorias = $categoriaModel->getAll();

// Buscar últimos imóveis para sidebar
$ultimosImoveis = $imovelModel->getRecentes(5);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($imovel['titulo']) ?> - FABIOLEAO Imóveis</title>
    <meta name="description" content="<?= htmlspecialchars($imovel['descricao_curta'] ?? substr($imovel['descricao'], 0, 160)) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($imovel['titulo']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($imovel['descricao_curta'] ?? substr($imovel['descricao'], 0, 160)) ?>">
    <meta property="og:image" content="assets/images/properties/<?= $imovel['imagem_principal'] ?>">
    <meta property="og:type" content="website">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    
    <!-- Fancybox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/property-details.css">
    
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
                                    <li class="dropdown2">
                                        <a href="#">Imóveis</a>
                                        <ul>
                                            <li><a href="busca.php?tipo_negocio=venda">Comprar</a></li>
                                            <li><a href="busca.php?tipo_negocio=aluguel">Alugar</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown2">
                                        <a href="#">Categorias</a>
                                        <ul>
                                            <?php foreach($categorias as $cat): ?>
                                            <li><a href="busca.php?categoria=<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['nome']) ?></a></li>
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
                            <a href="https://wa.me/5511999999999?text=Olá! Tenho interesse no imóvel: <?= urlencode($imovel['titulo']) ?>" target="_blank" class="tf-btn primary">
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
        
        <!-- Property Header -->
        <section class="property-header">
            <div class="container">
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Início</a></li>
                            <li class="breadcrumb-item"><a href="busca.php">Imóveis</a></li>
                            <?php if($imovel['categoria_nome']): ?>
                            <li class="breadcrumb-item"><a href="busca.php?categoria=<?= $imovel['categoria_slug'] ?>"><?= htmlspecialchars($imovel['categoria_nome']) ?></a></li>
                            <?php endif; ?>
                            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($imovel['titulo']) ?></li>
                        </ol>
                    </nav>
                </div>
                
                <div class="property-header-content">
                    <div class="property-header-left">
                        <div class="property-badges">
                            <span class="badge badge-<?= $imovel['tipo_negocio'] ?>">
                                <?= $imovel['tipo_negocio'] == 'venda' ? 'Venda' : 'Aluguel' ?>
                            </span>
                            <span class="badge badge-category"><?= htmlspecialchars($imovel['categoria_nome'] ?? 'Imóvel') ?></span>
                            <?php if($imovel['destaque']): ?>
                            <span class="badge badge-featured">Destaque</span>
                            <?php endif; ?>
                        </div>
                        <h1 class="property-title"><?= htmlspecialchars($imovel['titulo']) ?></h1>
                        <div class="property-location">
                            <i class="bi bi-geo-alt"></i>
                            <span>
                                <?php
                                $location = [];
                                if ($imovel['endereco']) $location[] = $imovel['endereco'];
                                if ($imovel['numero']) $location[] = $imovel['numero'];
                                if ($imovel['bairro']) $location[] = $imovel['bairro'];
                                if ($imovel['cidade']) $location[] = $imovel['cidade'];
                                if ($imovel['estado']) $location[] = $imovel['estado'];
                                echo htmlspecialchars(implode(', ', $location) ?: 'Localização não informada');
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="property-header-right">
                        <div class="property-price-box">
                            <span class="price-label">
                                <?= $imovel['tipo_negocio'] == 'venda' ? 'Valor de Venda' : 'Valor do Aluguel' ?>
                            </span>
                            <div class="property-price">
                                <?= formatPrice($imovel['preco']) ?>
                                <?php if($imovel['tipo_negocio'] == 'aluguel'): ?>
                                <span class="price-period">/mês</span>
                                <?php endif; ?>
                            </div>
                            <?php if($imovel['preco_condominio'] || $imovel['preco_iptu']): ?>
                            <div class="price-extras">
                                <?php if($imovel['preco_condominio']): ?>
                                <span>Condomínio: <?= formatPrice($imovel['preco_condominio']) ?></span>
                                <?php endif; ?>
                                <?php if($imovel['preco_iptu']): ?>
                                <span>IPTU: <?= formatPrice($imovel['preco_iptu']) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="property-actions">
                            <button class="btn-action" title="Favoritar">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" title="Compartilhar">
                                <i class="bi bi-share"></i>
                            </button>
                            <button class="btn-action" title="Imprimir" onclick="window.print()">
                                <i class="bi bi-printer"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Property Gallery -->
        <section class="property-gallery">
            <div class="container">
                <div class="gallery-wrapper">
                    <div class="main-image">
                        <a href="assets/images/properties/<?= $imovel['imagem_principal'] ?: 'placeholder.jpg' ?>" data-fancybox="gallery">
                            <img src="assets/images/properties/<?= $imovel['imagem_principal'] ?: 'placeholder.jpg' ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>">
                        </a>
                        <div class="gallery-nav">
                            <span class="photo-count">
                                <i class="bi bi-images"></i>
                                <?= count($imagens) + 1 ?> fotos
                            </span>
                        </div>
                    </div>
                    
                    <?php if(count($imagens) > 0): ?>
                    <div class="gallery-thumbs">
                        <?php foreach(array_slice($imagens, 0, 4) as $index => $img): ?>
                        <a href="assets/images/properties/<?= $img['imagem'] ?>" data-fancybox="gallery" class="gallery-thumb <?= $index === 3 && count($imagens) > 4 ? 'has-more' : '' ?>">
                            <img src="assets/images/properties/<?= $img['imagem'] ?>" alt="Imagem <?= $index + 2 ?>">
                            <?php if($index === 3 && count($imagens) > 4): ?>
                            <div class="more-overlay">+<?= count($imagens) - 4 ?></div>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                        
                        <?php foreach(array_slice($imagens, 4) as $img): ?>
                        <a href="assets/images/properties/<?= $img['imagem'] ?>" data-fancybox="gallery" style="display: none;"></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
        <!-- Property Content -->
        <section class="property-content-section">
            <div class="container">
                <div class="property-content-wrapper">
                    <!-- Main Content -->
                    <div class="property-main">
                        <!-- Quick Features -->
                        <div class="property-quick-features">
                            <?php if($imovel['quartos'] > 0): ?>
                            <div class="quick-feature">
                                <i class="bi bi-door-open"></i>
                                <div class="feature-info">
                                    <span class="feature-value"><?= $imovel['quartos'] ?></span>
                                    <span class="feature-label">Quartos</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($imovel['suites'] > 0): ?>
                            <div class="quick-feature">
                                <i class="bi bi-shield-check"></i>
                                <div class="feature-info">
                                    <span class="feature-value"><?= $imovel['suites'] ?></span>
                                    <span class="feature-label">Suítes</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($imovel['banheiros'] > 0): ?>
                            <div class="quick-feature">
                                <i class="bi bi-droplet"></i>
                                <div class="feature-info">
                                    <span class="feature-value"><?= $imovel['banheiros'] ?></span>
                                    <span class="feature-label">Banheiros</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($imovel['vagas_garagem'] > 0): ?>
                            <div class="quick-feature">
                                <i class="bi bi-car-front"></i>
                                <div class="feature-info">
                                    <span class="feature-value"><?= $imovel['vagas_garagem'] ?></span>
                                    <span class="feature-label">Vagas</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($imovel['area_construida'] > 0): ?>
                            <div class="quick-feature">
                                <i class="bi bi-arrows-angle-expand"></i>
                                <div class="feature-info">
                                    <span class="feature-value"><?= number_format($imovel['area_construida'], 0, ',', '.') ?></span>
                                    <span class="feature-label">m² Construídos</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($imovel['area_total'] > 0): ?>
                            <div class="quick-feature">
                                <i class="bi bi-bounding-box"></i>
                                <div class="feature-info">
                                    <span class="feature-value"><?= number_format($imovel['area_total'], 0, ',', '.') ?></span>
                                    <span class="feature-label">m² Total</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Description -->
                        <div class="property-section">
                            <h2 class="section-title"><i class="bi bi-file-text"></i> Descrição</h2>
                            <div class="property-description">
                                <?= nl2br(htmlspecialchars($imovel['descricao'] ?? 'Sem descrição disponível.')) ?>
                            </div>
                        </div>
                        
                        <!-- Details -->
                        <div class="property-section">
                            <h2 class="section-title"><i class="bi bi-list-ul"></i> Detalhes do Imóvel</h2>
                            <div class="property-details-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Código</span>
                                    <span class="detail-value">#<?= $imovel['id'] ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Tipo</span>
                                    <span class="detail-value"><?= htmlspecialchars($imovel['categoria_nome'] ?? '-') ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Status</span>
                                    <span class="detail-value">
                                        <?php
                                        $statusLabels = [
                                            'disponivel' => 'Disponível',
                                            'vendido' => 'Vendido',
                                            'alugado' => 'Alugado',
                                            'reservado' => 'Reservado'
                                        ];
                                        echo $statusLabels[$imovel['status']] ?? 'Disponível';
                                        ?>
                                    </span>
                                </div>
                                <?php if($imovel['ano_construcao']): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Ano de Construção</span>
                                    <span class="detail-value"><?= $imovel['ano_construcao'] ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($imovel['quartos'] > 0): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Quartos</span>
                                    <span class="detail-value"><?= $imovel['quartos'] ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($imovel['suites'] > 0): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Suítes</span>
                                    <span class="detail-value"><?= $imovel['suites'] ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($imovel['banheiros'] > 0): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Banheiros</span>
                                    <span class="detail-value"><?= $imovel['banheiros'] ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($imovel['vagas_garagem'] > 0): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Vagas de Garagem</span>
                                    <span class="detail-value"><?= $imovel['vagas_garagem'] ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($imovel['area_construida'] > 0): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Área Construída</span>
                                    <span class="detail-value"><?= number_format($imovel['area_construida'], 0, ',', '.') ?> m²</span>
                                </div>
                                <?php endif; ?>
                                <?php if($imovel['area_total'] > 0): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Área Total</span>
                                    <span class="detail-value"><?= number_format($imovel['area_total'], 0, ',', '.') ?> m²</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Amenities -->
                        <?php if(count($amenidades) > 0): ?>
                        <div class="property-section">
                            <h2 class="section-title"><i class="bi bi-check2-square"></i> Características e Comodidades</h2>
                            <div class="amenities-grid">
                                <?php foreach($amenidades as $amenidade): ?>
                                <div class="amenity-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span><?= htmlspecialchars($amenidade['nome']) ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Location -->
                        <div class="property-section">
                            <h2 class="section-title"><i class="bi bi-geo-alt"></i> Localização</h2>
                            <div class="location-info">
                                <div class="location-address">
                                    <?php if($imovel['endereco']): ?>
                                    <p><strong>Endereço:</strong> <?= htmlspecialchars($imovel['endereco']) ?><?= $imovel['numero'] ? ', ' . htmlspecialchars($imovel['numero']) : '' ?></p>
                                    <?php endif; ?>
                                    <?php if($imovel['complemento']): ?>
                                    <p><strong>Complemento:</strong> <?= htmlspecialchars($imovel['complemento']) ?></p>
                                    <?php endif; ?>
                                    <?php if($imovel['bairro']): ?>
                                    <p><strong>Bairro:</strong> <?= htmlspecialchars($imovel['bairro']) ?></p>
                                    <?php endif; ?>
                                    <?php if($imovel['cidade']): ?>
                                    <p><strong>Cidade:</strong> <?= htmlspecialchars($imovel['cidade']) ?><?= $imovel['estado'] ? ' - ' . htmlspecialchars($imovel['estado']) : '' ?></p>
                                    <?php endif; ?>
                                    <?php if($imovel['cep']): ?>
                                    <p><strong>CEP:</strong> <?= htmlspecialchars($imovel['cep']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($imovel['latitude'] && $imovel['longitude']): ?>
                                <div class="location-map">
                                    <iframe 
                                        src="https://maps.google.com/maps?q=<?= $imovel['latitude'] ?>,<?= $imovel['longitude'] ?>&z=15&output=embed"
                                        width="100%" 
                                        height="300" 
                                        style="border:0; border-radius: 12px;" 
                                        allowfullscreen="" 
                                        loading="lazy">
                                    </iframe>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <aside class="property-sidebar">
                        <!-- Contact Form -->
                        <div class="sidebar-widget contact-widget">
                            <h3 class="widget-title">Tenho Interesse</h3>
                            <form action="api/contato.php" method="POST" id="propertyContactForm">
                                <input type="hidden" name="imovel_id" value="<?= $imovel['id'] ?>">
                                
                                <div class="form-group">
                                    <input type="text" name="nome" class="form-control" placeholder="Seu nome *" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Seu e-mail *" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" name="telefone" class="form-control" placeholder="Seu telefone *" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="mensagem" class="form-control" rows="4" placeholder="Sua mensagem">Olá, tenho interesse no imóvel "<?= htmlspecialchars($imovel['titulo']) ?>" (Código #<?= $imovel['id'] ?>). Gostaria de mais informações.</textarea>
                                </div>
                                
                                <button type="submit" class="tf-btn primary w-100">
                                    <i class="bi bi-send"></i> Enviar Mensagem
                                </button>
                            </form>
                            
                            <div class="contact-options">
                                <span>ou entre em contato:</span>
                                <div class="contact-buttons">
                                    <a href="https://wa.me/5511999999999?text=Olá! Tenho interesse no imóvel: <?= urlencode($imovel['titulo']) ?> (Código #<?= $imovel['id'] ?>)" target="_blank" class="btn-whatsapp">
                                        <i class="bi bi-whatsapp"></i> WhatsApp
                                    </a>
                                    <a href="tel:+5511999999999" class="btn-phone">
                                        <i class="bi bi-telephone"></i> Ligar
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Latest Properties -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Últimos Imóveis</h3>
                            <div class="latest-properties">
                                <?php foreach($ultimosImoveis as $ultimo): ?>
                                <a href="imovel.php?slug=<?= $ultimo['slug'] ?>" class="latest-property-item">
                                    <div class="latest-image">
                                        <img src="assets/images/properties/<?= $ultimo['imagem_principal'] ?: 'placeholder.jpg' ?>" alt="<?= htmlspecialchars($ultimo['titulo']) ?>">
                                    </div>
                                    <div class="latest-content">
                                        <h4 class="latest-title"><?= htmlspecialchars($ultimo['titulo']) ?></h4>
                                        <div class="latest-meta">
                                            <?php if($ultimo['quartos'] > 0): ?>
                                            <span><i class="bi bi-door-open"></i> <?= $ultimo['quartos'] ?></span>
                                            <?php endif; ?>
                                            <?php if($ultimo['banheiros'] > 0): ?>
                                            <span><i class="bi bi-droplet"></i> <?= $ultimo['banheiros'] ?></span>
                                            <?php endif; ?>
                                            <?php if($ultimo['area_construida'] > 0): ?>
                                            <span><i class="bi bi-arrows-angle-expand"></i> <?= number_format($ultimo['area_construida'], 0, ',', '.') ?>m²</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="latest-price"><?= formatPrice($ultimo['preco']) ?></div>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
        
        <!-- Similar Properties -->
        <?php if(count($similares) > 0): ?>
        <section class="flat-section similar-section">
            <div class="container">
                <div class="section-header">
                    <div class="section-title">
                        <span class="subtitle">Você também pode gostar</span>
                        <h2>Imóveis Similares</h2>
                    </div>
                    <a href="busca.php?categoria=<?= $imovel['categoria_slug'] ?>" class="tf-btn btn-outline">
                        Ver Mais <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                
                <div class="properties-grid similar-grid">
                    <?php foreach($similares as $similar): ?>
                    <div class="property-card">
                        <div class="property-image">
                            <a href="imovel.php?slug=<?= $similar['slug'] ?>">
                                <img src="assets/images/properties/<?= $similar['imagem_principal'] ?: 'placeholder.jpg' ?>" alt="<?= htmlspecialchars($similar['titulo']) ?>">
                            </a>
                            <div class="property-badges">
                                <span class="badge badge-<?= $similar['tipo_negocio'] ?>">
                                    <?= $similar['tipo_negocio'] == 'venda' ? 'Venda' : 'Aluguel' ?>
                                </span>
                            </div>
                        </div>
                        <div class="property-content">
                            <div class="property-category"><?= htmlspecialchars($similar['categoria_nome'] ?? 'Imóvel') ?></div>
                            <h3 class="property-title">
                                <a href="imovel.php?slug=<?= $similar['slug'] ?>"><?= htmlspecialchars($similar['titulo']) ?></a>
                            </h3>
                            <div class="property-location">
                                <i class="bi bi-geo-alt"></i>
                                <span><?= htmlspecialchars($similar['bairro'] . ', ' . $similar['cidade']) ?></span>
                            </div>
                            <div class="property-features">
                                <?php if($similar['quartos'] > 0): ?>
                                <div class="feature"><i class="bi bi-door-open"></i> <span><?= $similar['quartos'] ?></span></div>
                                <?php endif; ?>
                                <?php if($similar['banheiros'] > 0): ?>
                                <div class="feature"><i class="bi bi-droplet"></i> <span><?= $similar['banheiros'] ?></span></div>
                                <?php endif; ?>
                                <?php if($similar['area_construida'] > 0): ?>
                                <div class="feature"><i class="bi bi-arrows-angle-expand"></i> <span><?= number_format($similar['area_construida'], 0, ',', '.') ?>m²</span></div>
                                <?php endif; ?>
                            </div>
                            <div class="property-footer">
                                <div class="property-price">
                                    <?= formatPrice($similar['preco']) ?>
                                    <?php if($similar['tipo_negocio'] == 'aluguel'): ?><span class="price-period">/mês</span><?php endif; ?>
                                </div>
                                <a href="imovel.php?slug=<?= $similar['slug'] ?>" class="btn-details">Detalhes <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        
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
        <a href="https://wa.me/5511999999999?text=Olá! Tenho interesse no imóvel: <?= urlencode($imovel['titulo']) ?>" target="_blank" class="whatsapp-float">
            <i class="bi bi-whatsapp"></i>
        </a>
        
        <!-- Back to Top -->
        <button class="back-to-top" id="backToTop">
            <i class="bi bi-arrow-up"></i>
        </button>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Initialize Fancybox
        Fancybox.bind('[data-fancybox="gallery"]', {
            Thumbs: {
                type: "classic"
            },
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [],
                    right: ["slideshow", "fullscreen", "download", "close"]
                }
            }
        });
    </script>
</body>
</html>
