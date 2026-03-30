<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Imovel.php';
require_once __DIR__ . '/models/Categoria.php';

$db = new Database();
$conn = $db->getConnection();

$imovelModel = new Imovel($conn);
$categoriaModel = new Categoria($conn);

$imoveisDestaque = $imovelModel->listarDestaques(6);
$categorias = $categoriaModel->listar();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FABIOLEAO Imobiliária - Encontre seu Imóvel dos Sonhos</title>
    <meta name="description" content="Encontre casas, apartamentos e terrenos para comprar ou alugar.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="preloader-inner">
            <div class="loading-spinner"></div>
            <span>FL</span>
        </div>
    </div>

    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <div class="header-inner">
                <a href="index.php" class="logo">
                    <div class="logo-icon">FL</div>
                    <span class="logo-text">FABIO<span>LEAO</span></span>
                </a>
                
                <nav class="nav-menu" id="navMenu">
                    <a href="index.php" class="nav-link active">Início</a>
                    <a href="busca.php?finalidade=venda" class="nav-link">Comprar</a>
                    <a href="busca.php?finalidade=aluguel" class="nav-link">Alugar</a>
                    <a href="busca.php" class="nav-link">Imóveis</a>
                    <a href="#contato" class="nav-link">Contato</a>
                </nav>
                
                <div class="header-actions">
                    <a href="tel:+5511999999999" class="header-phone">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>(11) 99999-9999</span>
                    </a>
                    <a href="admin/login.php" class="btn btn-primary">Área Admin</a>
                </div>
                
                <button class="menu-toggle" id="menuToggle" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-menu-close" id="mobileMenuClose">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <nav class="mobile-nav">
            <a href="index.php" class="nav-link active">Início</a>
            <a href="busca.php?finalidade=venda" class="nav-link">Comprar</a>
            <a href="busca.php?finalidade=aluguel" class="nav-link">Alugar</a>
            <a href="busca.php" class="nav-link">Imóveis</a>
            <a href="#contato" class="nav-link">Contato</a>
            <a href="admin/login.php" class="btn btn-primary" style="margin-top: 20px;">Área Admin</a>
        </nav>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg">
            <video autoplay muted loop playsinline poster="assets/images/hero-poster.jpg">
                <source src="assets/videos/hero-bg.mp4" type="video/mp4">
            </video>
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    A melhor imobiliária da região
                </div>
                <h1 class="hero-title">
                    Encontre o <span>imóvel perfeito</span> para você
                </h1>
                <p class="hero-subtitle">
                    Descubra milhares de opções de casas, apartamentos e terrenos. 
                    Sua próxima casa está a apenas um clique de distância.
                </p>
                
                <!-- Search Box -->
                <div class="search-box">
                    <div class="search-tabs">
                        <button class="search-tab active" data-type="venda">Comprar</button>
                        <button class="search-tab" data-type="aluguel">Alugar</button>
                    </div>
                    <form class="search-form" action="busca.php" method="GET" id="searchForm">
                        <input type="hidden" name="finalidade" id="searchFinalidade" value="venda">
                        
                        <div class="form-group">
                            <label class="form-label">Localização</label>
                            <div class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <input type="text" name="cidade" class="form-control" placeholder="Cidade ou bairro">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Tipo de Imóvel</label>
                            <select name="categoria" class="form-control">
                                <option value="">Todos os tipos</option>
                                <?php if($categorias && $categorias->rowCount() > 0): ?>
                                    <?php while($cat = $categorias->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Preço Máximo</label>
                            <select name="preco_max" class="form-control">
                                <option value="">Sem limite</option>
                                <option value="200000">Até R$ 200.000</option>
                                <option value="400000">Até R$ 400.000</option>
                                <option value="600000">Até R$ 600.000</option>
                                <option value="800000">Até R$ 800.000</option>
                                <option value="1000000">Até R$ 1.000.000</option>
                                <option value="2000000">Até R$ 2.000.000</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Quartos</label>
                            <select name="quartos" class="form-control">
                                <option value="">Qualquer</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-search">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            Buscar
                        </button>
                    </form>
                </div>
                
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-value">500+</div>
                        <div class="hero-stat-label">Imóveis Disponíveis</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">1.200+</div>
                        <div class="hero-stat-label">Clientes Satisfeitos</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">15+</div>
                        <div class="hero-stat-label">Anos de Experiência</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Imóveis em Destaque</h2>
                    <p class="section-subtitle">Confira as melhores oportunidades selecionadas para você</p>
                </div>
                <a href="busca.php" class="btn btn-outline">
                    Ver todos
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <div class="properties-grid">
                <?php if($imoveisDestaque && $imoveisDestaque->rowCount() > 0): ?>
                    <?php while($imovel = $imoveisDestaque->fetch(PDO::FETCH_ASSOC)): ?>
                        <article class="property-card">
                            <a href="imovel.php?id=<?= $imovel['id'] ?>" class="property-image">
                                <img src="<?= !empty($imovel['imagem_principal']) ? 'uploads/imoveis/' . $imovel['imagem_principal'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop' ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>" loading="lazy">
                                <div class="property-badges">
                                    <span class="property-badge <?= $imovel['finalidade'] == 'venda' ? 'sale' : 'rent' ?>">
                                        <?= $imovel['finalidade'] == 'venda' ? 'Venda' : 'Aluguel' ?>
                                    </span>
                                    <?php if(!empty($imovel['destaque']) && $imovel['destaque']): ?>
                                        <span class="property-badge featured">Destaque</span>
                                    <?php endif; ?>
                                </div>
                                <button class="property-favorite" type="button" aria-label="Favoritar">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                </button>
                            </a>
                            <div class="property-content">
                                <span class="property-type"><?= htmlspecialchars($imovel['categoria_nome'] ?? 'Imóvel') ?></span>
                                <h3 class="property-title">
                                    <a href="imovel.php?id=<?= $imovel['id'] ?>"><?= htmlspecialchars($imovel['titulo']) ?></a>
                                </h3>
                                <div class="property-location">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <?= htmlspecialchars($imovel['bairro'] ?? '') ?><?= !empty($imovel['bairro']) && !empty($imovel['cidade']) ? ', ' : '' ?><?= htmlspecialchars($imovel['cidade'] ?? '') ?>
                                </div>
                                <div class="property-features">
                                    <?php if(!empty($imovel['quartos']) && $imovel['quartos'] > 0): ?>
                                    <div class="property-feature">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/>
                                        </svg>
                                        <?= $imovel['quartos'] ?> <?= $imovel['quartos'] == 1 ? 'Quarto' : 'Quartos' ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!empty($imovel['banheiros']) && $imovel['banheiros'] > 0): ?>
                                    <div class="property-feature">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                                            <line x1="10" x2="8" y1="5" y2="7"/>
                                            <line x1="2" x2="22" y1="12" y2="12"/>
                                            <line x1="7" x2="7" y1="19" y2="21"/>
                                            <line x1="17" x2="17" y1="19" y2="21"/>
                                        </svg>
                                        <?= $imovel['banheiros'] ?> <?= $imovel['banheiros'] == 1 ? 'Banheiro' : 'Banheiros' ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!empty($imovel['area']) && $imovel['area'] > 0): ?>
                                    <div class="property-feature">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        </svg>
                                        <?= number_format($imovel['area'], 0, ',', '.') ?> m²
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="property-footer">
                                    <div class="property-price">
                                        R$ <?= number_format($imovel['preco'], 0, ',', '.') ?>
                                        <?php if($imovel['finalidade'] == 'aluguel'): ?>
                                            <span>/mês</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="imovel.php?id=<?= $imovel['id'] ?>" class="btn btn-sm btn-outline">Ver mais</a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9,22 9,12 15,12 15,22"/>
                        </svg>
                        <h3>Nenhum imóvel em destaque</h3>
                        <p>Os imóveis em destaque aparecerão aqui.</p>
                        <a href="busca.php" class="btn btn-primary">Ver todos os imóveis</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section bg-gray-50">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Por que escolher a FABIOLEAO?</h2>
                <p class="section-subtitle">Oferecemos o melhor serviço para encontrar seu imóvel ideal</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <h4 class="feature-title">Localizações Premium</h4>
                    <p class="feature-description">Imóveis nas melhores localizações da cidade, com fácil acesso a comércios e serviços.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h4 class="feature-title">Transações Seguras</h4>
                    <p class="feature-description">Total segurança jurídica em todas as etapas da compra, venda ou aluguel do seu imóvel.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                    </div>
                    <h4 class="feature-title">Atendimento 24h</h4>
                    <p class="feature-description">Equipe disponível para atender você a qualquer momento, inclusive finais de semana.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2"/>
                        </svg>
                    </div>
                    <h4 class="feature-title">Avaliação Gratuita</h4>
                    <p class="feature-description">Oferecemos avaliação gratuita do seu imóvel com profissionais experientes do mercado.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Quer vender ou alugar seu imóvel?</h2>
                <p class="cta-subtitle">Anuncie conosco e alcance milhares de interessados. Nossa equipe cuida de tudo para você.</p>
                <div class="cta-buttons">
                    <a href="tel:+5511999999999" class="btn btn-white btn-lg">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        Fale com um corretor
                    </a>
                    <a href="https://wa.me/5511999999999" target="_blank" class="btn btn-whatsapp btn-lg">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contato">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="index.php" class="logo">
                        <div class="logo-icon">FL</div>
                        <span class="logo-text">FABIO<span>LEAO</span></span>
                    </a>
                    <p class="footer-description">
                        Há mais de 15 anos ajudando famílias a encontrarem o lar dos seus sonhos. 
                        Conte com nossa experiência e dedicação.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h5 class="footer-title">Links Rápidos</h5>
                    <ul class="footer-links">
                        <li><a href="index.php" class="footer-link">Início</a></li>
                        <li><a href="busca.php" class="footer-link">Imóveis</a></li>
                        <li><a href="busca.php?finalidade=venda" class="footer-link">Comprar</a></li>
                        <li><a href="busca.php?finalidade=aluguel" class="footer-link">Alugar</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h5 class="footer-title">Tipos de Imóveis</h5>
                    <ul class="footer-links">
                        <li><a href="busca.php?categoria=1" class="footer-link">Casas</a></li>
                        <li><a href="busca.php?categoria=2" class="footer-link">Apartamentos</a></li>
                        <li><a href="busca.php?categoria=3" class="footer-link">Terrenos</a></li>
                        <li><a href="busca.php?categoria=4" class="footer-link">Comerciais</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h5 class="footer-title">Contato</h5>
                    <div class="footer-contact">
                        <div class="footer-contact-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span>Av. Principal, 1234<br>Centro - São Paulo/SP</span>
                        </div>
                        <div class="footer-contact-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span>(11) 99999-9999</span>
                        </div>
                        <div class="footer-contact-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <span>contato@fabioleao.com.br</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; <?= date('Y') ?> FABIOLEAO Imobiliária. Todos os direitos reservados.</p>
                <div class="footer-legal">
                    <a href="#">Política de Privacidade</a>
                    <a href="#">Termos de Uso</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
