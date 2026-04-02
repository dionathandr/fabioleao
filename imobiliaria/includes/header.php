<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo getSiteConfig('site_descricao'); ?>">
    <meta name="author" content="<?php echo getSiteConfig('site_nome'); ?>">
    
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?><?php echo getSiteConfig('site_nome'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo ASSETS_URL; ?>images/favicon.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    
    <!-- Base URL for JavaScript -->
    <script>window.BASE_URL = '<?php echo BASE_URL; ?>';</script>
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo getSiteConfig('site_nome'); ?>">
    <meta property="og:description" content="<?php echo getSiteConfig('site_descricao'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo BASE_URL; ?>">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <a href="<?php echo BASE_URL; ?>" class="logo">
                    <span class="logo-text">Fabio <span>Leão</span></span>
                </a>

                <!-- Navigation -->
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="<?php echo BASE_URL; ?>" class="nav-link <?php echo $currentPage == 'home' ? 'active' : ''; ?>">Início</a></li>
                        <li><a href="<?php echo BASE_URL; ?>imoveis.php" class="nav-link <?php echo $currentPage == 'imoveis' ? 'active' : ''; ?>">Imóveis</a></li>
                        <li><a href="<?php echo BASE_URL; ?>imoveis.php?finalidade=venda" class="nav-link">Comprar</a></li>
                        <li><a href="<?php echo BASE_URL; ?>imoveis.php?finalidade=aluguel" class="nav-link">Alugar</a></li>
                        <li><a href="<?php echo BASE_URL; ?>sobre.php" class="nav-link <?php echo $currentPage == 'sobre' ? 'active' : ''; ?>">Sobre</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contato.php" class="nav-link <?php echo $currentPage == 'contato' ? 'active' : ''; ?>">Contato</a></li>
                    </ul>
                </nav>

                <!-- Actions -->
                <div class="nav-actions">
                    <a href="<?php echo BASE_URL; ?>favoritos.php" class="btn btn-outline btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        <span>Favoritos</span>
                    </a>
                    <a href="https://wa.me/<?php echo getSiteConfig('site_whatsapp'); ?>" target="_blank" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        <span>WhatsApp</span>
                    </a>
                </div>

                <!-- Mobile Toggle -->
                <button class="mobile-toggle" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-overlay"></div>
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <span class="logo-text">Fabio <span>Leão</span></span>
            <button class="mobile-menu-close" aria-label="Fechar menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <ul class="mobile-nav-list">
            <li><a href="<?php echo BASE_URL; ?>">Início</a></li>
            <li><a href="<?php echo BASE_URL; ?>imoveis.php">Todos os Imóveis</a></li>
            <li><a href="<?php echo BASE_URL; ?>imoveis.php?finalidade=venda">Comprar</a></li>
            <li><a href="<?php echo BASE_URL; ?>imoveis.php?finalidade=aluguel">Alugar</a></li>
            <li><a href="<?php echo BASE_URL; ?>sobre.php">Sobre Nós</a></li>
            <li><a href="<?php echo BASE_URL; ?>contato.php">Contato</a></li>
            <li><a href="<?php echo BASE_URL; ?>favoritos.php">Meus Favoritos</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main>
