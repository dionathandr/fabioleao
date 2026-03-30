<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Imovel.php';
require_once __DIR__ . '/models/Categoria.php';

$imovelModel = new Imovel();
$categoriaModel = new Categoria();

// Buscar dados
$imoveisDestaque = $imovelModel->getDestaques(6);
$imoveisRecentes = $imovelModel->getAll(8);
$categorias = $categoriaModel->getAll();

// Estatísticas
$stats = $imovelModel->getStats();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>FABIOLEAO Imóveis - Encontre seu Imóvel dos Sonhos</title>
    <meta name="keywords" content="imóveis, casas, apartamentos, aluguel, venda, imobiliária">
    <meta name="description" content="FABIOLEAO Imóveis - A melhor imobiliária para encontrar o imóvel dos seus sonhos. Casas, apartamentos, terrenos e muito mais.">
    <meta name="author" content="FABIOLEAO">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
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
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    
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
                                    <li class="current"><a href="index.php">Início</a></li>
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
                                    <li><a href="#contato">Contato</a></li>
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
                        <li><a href="#contato">Contato</a></li>
                    </ul>
                    <div class="contact-info-mobile">
                        <a href="tel:+5511999999999"><i class="bi bi-telephone"></i> (11) 99999-9999</a>
                        <a href="https://wa.me/5511999999999" target="_blank"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                    </div>
                </nav>
            </div>
        </header>
        
        <!-- Banner/Slider Section -->
        <section class="flat-slider">
            <div class="slider-content">
                <div class="container">
                    <div class="text-center">
                        <h1 class="title-large text-white">
                            Encontre o <span class="highlight">Imóvel Perfeito</span><br>
                            Para Você e Sua Família
                        </h1>
                        <p class="subtitle text-white">
                            Somos especialistas em ajudar você a encontrar o imóvel ideal.<br>
                            Casas, apartamentos, terrenos e muito mais.
                        </p>
                    </div>
                    
                    <!-- Search Form -->
                    <div class="search-form-wrapper">
                        <div class="search-tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabAluguel" type="button">
                                        <i class="bi bi-key"></i> Alugar
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabVenda" type="button">
                                        <i class="bi bi-house-check"></i> Comprar
                                    </button>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tabAluguel">
                                <form action="busca.php" method="GET" class="search-form">
                                    <input type="hidden" name="tipo_negocio" value="aluguel">
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Tipo de Imóvel</label>
                                            <select name="categoria" class="form-select">
                                                <option value="">Todos os tipos</option>
                                                <?php foreach($categorias as $cat): ?>
                                                <option value="<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Localização</label>
                                            <div class="input-icon">
                                                <input type="text" name="localizacao" class="form-control" placeholder="Cidade, bairro...">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Palavra-chave</label>
                                            <input type="text" name="keyword" class="form-control" placeholder="Ex: piscina, churrasqueira...">
                                        </div>
                                        
                                        <div class="form-group form-group-btn">
                                            <button type="submit" class="tf-btn primary btn-search">
                                                <i class="bi bi-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="advanced-search-toggle">
                                        <button type="button" class="btn-advanced" data-bs-toggle="collapse" data-bs-target="#advancedAluguel">
                                            <i class="bi bi-sliders"></i> Busca Avançada
                                        </button>
                                    </div>
                                    
                                    <div class="collapse advanced-search" id="advancedAluguel">
                                        <div class="advanced-row">
                                            <div class="form-group">
                                                <label>Preço Mínimo</label>
                                                <input type="number" name="preco_min" class="form-control" placeholder="R$ 0">
                                            </div>
                                            <div class="form-group">
                                                <label>Preço Máximo</label>
                                                <input type="number" name="preco_max" class="form-control" placeholder="R$ 10.000">
                                            </div>
                                            <div class="form-group">
                                                <label>Quartos</label>
                                                <select name="quartos" class="form-select">
                                                    <option value="">Qualquer</option>
                                                    <option value="1">1+</option>
                                                    <option value="2">2+</option>
                                                    <option value="3">3+</option>
                                                    <option value="4">4+</option>
                                                    <option value="5">5+</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Banheiros</label>
                                                <select name="banheiros" class="form-select">
                                                    <option value="">Qualquer</option>
                                                    <option value="1">1+</option>
                                                    <option value="2">2+</option>
                                                    <option value="3">3+</option>
                                                    <option value="4">4+</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Área Mínima (m²)</label>
                                                <input type="number" name="area_min" class="form-control" placeholder="0">
                                            </div>
                                            <div class="form-group">
                                                <label>Área Máxima (m²)</label>
                                                <input type="number" name="area_max" class="form-control" placeholder="500">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="tab-pane fade" id="tabVenda">
                                <form action="busca.php" method="GET" class="search-form">
                                    <input type="hidden" name="tipo_negocio" value="venda">
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Tipo de Imóvel</label>
                                            <select name="categoria" class="form-select">
                                                <option value="">Todos os tipos</option>
                                                <?php foreach($categorias as $cat): ?>
                                                <option value="<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Localização</label>
                                            <div class="input-icon">
                                                <input type="text" name="localizacao" class="form-control" placeholder="Cidade, bairro...">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Palavra-chave</label>
                                            <input type="text" name="keyword" class="form-control" placeholder="Ex: piscina, churrasqueira...">
                                        </div>
                                        
                                        <div class="form-group form-group-btn">
                                            <button type="submit" class="tf-btn primary btn-search">
                                                <i class="bi bi-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="advanced-search-toggle">
                                        <button type="button" class="btn-advanced" data-bs-toggle="collapse" data-bs-target="#advancedVenda">
                                            <i class="bi bi-sliders"></i> Busca Avançada
                                        </button>
                                    </div>
                                    
                                    <div class="collapse advanced-search" id="advancedVenda">
                                        <div class="advanced-row">
                                            <div class="form-group">
                                                <label>Preço Mínimo</label>
                                                <input type="number" name="preco_min" class="form-control" placeholder="R$ 0">
                                            </div>
                                            <div class="form-group">
                                                <label>Preço Máximo</label>
                                                <input type="number" name="preco_max" class="form-control" placeholder="R$ 5.000.000">
                                            </div>
                                            <div class="form-group">
                                                <label>Quartos</label>
                                                <select name="quartos" class="form-select">
                                                    <option value="">Qualquer</option>
                                                    <option value="1">1+</option>
                                                    <option value="2">2+</option>
                                                    <option value="3">3+</option>
                                                    <option value="4">4+</option>
                                                    <option value="5">5+</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Banheiros</label>
                                                <select name="banheiros" class="form-select">
                                                    <option value="">Qualquer</option>
                                                    <option value="1">1+</option>
                                                    <option value="2">2+</option>
                                                    <option value="3">3+</option>
                                                    <option value="4">4+</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Área Mínima (m²)</label>
                                                <input type="number" name="area_min" class="form-control" placeholder="0">
                                            </div>
                                            <div class="form-group">
                                                <label>Área Máxima (m²)</label>
                                                <input type="number" name="area_max" class="form-control" placeholder="1000">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?= number_format($stats['total'], 0, ',', '.') ?>+</div>
                        <div class="stat-label">Imóveis Cadastrados</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= number_format($stats['venda'], 0, ',', '.') ?></div>
                        <div class="stat-label">À Venda</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= number_format($stats['aluguel'], 0, ',', '.') ?></div>
                        <div class="stat-label">Para Alugar</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Clientes Satisfeitos</div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Featured Properties -->
        <section class="flat-section properties-section">
            <div class="container">
                <div class="section-header">
                    <div class="section-title">
                        <span class="subtitle">Destaques</span>
                        <h2>Imóveis em Destaque</h2>
                    </div>
                    <a href="busca.php" class="tf-btn btn-outline">
                        Ver Todos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                
                <div class="properties-grid">
                    <?php foreach($imoveisDestaque as $imovel): ?>
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
                                <?php if($imovel['vagas_garagem'] > 0): ?>
                                <div class="feature">
                                    <i class="bi bi-car-front"></i>
                                    <span><?= $imovel['vagas_garagem'] ?> Vagas</span>
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
                                    Ver Detalhes <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <!-- Categories Section -->
        <section class="flat-section categories-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="subtitle">Categorias</span>
                    <h2>Explore por Tipo de Imóvel</h2>
                </div>
                
                <div class="categories-grid">
                    <?php 
                    $icons = [
                        'casa' => 'bi-house',
                        'apartamento' => 'bi-building',
                        'terreno' => 'bi-map',
                        'sala-comercial' => 'bi-briefcase',
                        'galpao' => 'bi-box-seam',
                        'chacara' => 'bi-tree',
                        'cobertura' => 'bi-sun',
                        'studio' => 'bi-grid-1x2'
                    ];
                    foreach($categorias as $cat): 
                    $icon = $icons[$cat['slug']] ?? 'bi-house';
                    ?>
                    <a href="busca.php?categoria=<?= $cat['slug'] ?>" class="category-card">
                        <div class="category-icon">
                            <i class="bi <?= $icon ?>"></i>
                        </div>
                        <h4 class="category-name"><?= htmlspecialchars($cat['nome']) ?></h4>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <!-- Recent Properties -->
        <section class="flat-section properties-section bg-light">
            <div class="container">
                <div class="section-header">
                    <div class="section-title">
                        <span class="subtitle">Novidades</span>
                        <h2>Imóveis Recentes</h2>
                    </div>
                    <a href="busca.php" class="tf-btn btn-outline">
                        Ver Todos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                
                <div class="properties-grid">
                    <?php foreach($imoveisRecentes as $imovel): ?>
                    <div class="property-card">
                        <div class="property-image">
                            <a href="imovel.php?slug=<?= $imovel['slug'] ?>">
                                <img src="assets/images/properties/<?= $imovel['imagem_principal'] ?: 'placeholder.jpg' ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>">
                            </a>
                            <div class="property-badges">
                                <span class="badge badge-<?= $imovel['tipo_negocio'] ?>">
                                    <?= $imovel['tipo_negocio'] == 'venda' ? 'Venda' : 'Aluguel' ?>
                                </span>
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
                                    <span><?= $imovel['quartos'] ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($imovel['banheiros'] > 0): ?>
                                <div class="feature">
                                    <i class="bi bi-droplet"></i>
                                    <span><?= $imovel['banheiros'] ?></span>
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
            </div>
        </section>
        
        <!-- Why Choose Us -->
        <section class="flat-section why-us-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="subtitle">Por que nos escolher</span>
                    <h2>Sua Satisfação é Nossa Prioridade</h2>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Segurança</h4>
                        <p>Todos os imóveis são verificados e documentados para sua tranquilidade.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4>Atendimento Personalizado</h4>
                        <p>Equipe especializada pronta para atender suas necessidades.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h4>Ampla Cobertura</h4>
                        <p>Imóveis em diversas regiões para você escolher o melhor.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h4>Agilidade</h4>
                        <p>Processo rápido e desburocratizado do início ao fim.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Contact Section -->
        <section class="flat-section contact-section" id="contato">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="contact-info">
                        <span class="subtitle">Contato</span>
                        <h2>Entre em Contato Conosco</h2>
                        <p>Estamos prontos para ajudá-lo a encontrar o imóvel perfeito. Entre em contato conosco!</p>
                        
                        <div class="info-list">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="info-content">
                                    <h5>Endereço</h5>
                                    <p>Av. Principal, 1000 - Centro<br>São Paulo - SP</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="info-content">
                                    <h5>Telefone</h5>
                                    <p>(11) 99999-9999</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="info-content">
                                    <h5>E-mail</h5>
                                    <p>contato@fabioleao.com.br</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="contact-form-wrapper">
                        <form action="api/contato.php" method="POST" class="contact-form" id="contactForm">
                            <div class="form-group">
                                <label>Nome Completo</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="tel" name="telefone" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Mensagem</label>
                                <textarea name="mensagem" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="tf-btn primary w-100">
                                <i class="bi bi-send"></i> Enviar Mensagem
                            </button>
                        </form>
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
                                <li><a href="#contato">Contato</a></li>
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
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
