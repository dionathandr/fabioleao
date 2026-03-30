<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Imovel.php';
require_once __DIR__ . '/models/Categoria.php';

$db = new Database();
$conn = $db->getConnection();

$imovelModel = new Imovel($conn);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$imovel = $imovelModel->buscarPorId($id);

if (!$imovel) {
    header('Location: index.php');
    exit;
}

$imovelModel->incrementarVisualizacoes($id);
$relacionados = $imovelModel->buscarRelacionados($id, $imovel['categoria_id'] ?? 0, 3);
$imagens = $imovelModel->buscarImagens($id);
$amenidades = $imovelModel->buscarAmenidades($id);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($imovel['titulo']) ?> - FABIOLEAO</title>
    <meta name="description" content="<?= htmlspecialchars(substr($imovel['descricao'] ?? '', 0, 160)) ?>">
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
                    <a href="busca.php?finalidade=venda" class="nav-link">Comprar</a>
                    <a href="busca.php?finalidade=aluguel" class="nav-link">Alugar</a>
                    <a href="busca.php" class="nav-link active">Imóveis</a>
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
            <a href="busca.php" class="nav-link active">Imóveis</a>
            <a href="index.php#contato" class="nav-link">Contato</a>
        </nav>
    </div>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <nav class="breadcrumb">
                <a href="index.php">Início</a>
                <span class="breadcrumb-separator">/</span>
                <a href="busca.php">Imóveis</a>
                <span class="breadcrumb-separator">/</span>
                <span><?= htmlspecialchars($imovel['titulo']) ?></span>
            </nav>
            <div class="property-detail-header">
                <div class="property-detail-info">
                    <div class="property-detail-badges">
                        <span class="property-badge <?= $imovel['finalidade'] == 'venda' ? 'sale' : 'rent' ?>"><?= $imovel['finalidade'] == 'venda' ? 'Venda' : 'Aluguel' ?></span>
                        <span class="property-badge category"><?= htmlspecialchars($imovel['categoria_nome'] ?? 'Imóvel') ?></span>
                    </div>
                    <h1 class="property-detail-title"><?= htmlspecialchars($imovel['titulo']) ?></h1>
                    <div class="property-detail-location">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?= htmlspecialchars(($imovel['endereco'] ?? '') . (!empty($imovel['endereco']) ? ', ' : '') . ($imovel['bairro'] ?? '') . (!empty($imovel['bairro']) ? ' - ' : '') . ($imovel['cidade'] ?? '') . (!empty($imovel['estado']) ? '/' . $imovel['estado'] : '')) ?>
                    </div>
                </div>
                <div class="property-detail-price-box">
                    <div class="property-detail-price">R$ <?= number_format($imovel['preco'], 0, ',', '.') ?><?php if($imovel['finalidade'] == 'aluguel'): ?><span>/mês</span><?php endif; ?></div>
                    <?php if(!empty($imovel['condominio']) && $imovel['condominio'] > 0): ?>
                    <div class="property-detail-condo">Condomínio: R$ <?= number_format($imovel['condominio'], 0, ',', '.') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Gallery -->
    <section class="section-sm">
        <div class="container">
            <div class="property-gallery">
                <div class="gallery-main">
                    <img src="<?= !empty($imovel['imagem_principal']) ? 'uploads/imoveis/' . $imovel['imagem_principal'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200&h=800&fit=crop' ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>" id="mainImage">
                </div>
                <div class="gallery-thumbs">
                    <div class="gallery-thumb active" onclick="changeMainImage(this)">
                        <img src="<?= !empty($imovel['imagem_principal']) ? 'uploads/imoveis/' . $imovel['imagem_principal'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=300&fit=crop' ?>" alt="Imagem 1">
                    </div>
                    <?php if($imagens && $imagens->rowCount() > 0): $count = 0; while($img = $imagens->fetch(PDO::FETCH_ASSOC)): if($count < 3): ?>
                    <div class="gallery-thumb" onclick="changeMainImage(this)">
                        <img src="uploads/imoveis/<?= $img['arquivo'] ?>" alt="Imagem <?= $count + 2 ?>">
                    </div>
                    <?php endif; $count++; endwhile; endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Content -->
    <section class="section-sm">
        <div class="container">
            <div class="property-detail-content">
                <div class="property-detail-main">
                    <!-- Specs -->
                    <div class="property-info-card">
                        <h3 class="property-info-title">Características</h3>
                        <div class="property-specs">
                            <?php if(!empty($imovel['quartos'])): ?>
                            <div class="property-spec">
                                <div class="property-spec-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/></svg></div>
                                <div class="property-spec-value"><?= $imovel['quartos'] ?></div>
                                <div class="property-spec-label">Quartos</div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($imovel['suites'])): ?>
                            <div class="property-spec">
                                <div class="property-spec-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/><circle cx="18" cy="5" r="2"/></svg></div>
                                <div class="property-spec-value"><?= $imovel['suites'] ?></div>
                                <div class="property-spec-label">Suítes</div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($imovel['banheiros'])): ?>
                            <div class="property-spec">
                                <div class="property-spec-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="2" x2="22" y1="12" y2="12"/></svg></div>
                                <div class="property-spec-value"><?= $imovel['banheiros'] ?></div>
                                <div class="property-spec-label">Banheiros</div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($imovel['vagas'])): ?>
                            <div class="property-spec">
                                <div class="property-spec-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg></div>
                                <div class="property-spec-value"><?= $imovel['vagas'] ?></div>
                                <div class="property-spec-label">Vagas</div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($imovel['area'])): ?>
                            <div class="property-spec">
                                <div class="property-spec-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div>
                                <div class="property-spec-value"><?= number_format($imovel['area'], 0, ',', '.') ?></div>
                                <div class="property-spec-label">Área (m²)</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="property-info-card">
                        <h3 class="property-info-title">Descrição</h3>
                        <div class="property-description"><?= nl2br(htmlspecialchars($imovel['descricao'] ?? 'Sem descrição disponível.')) ?></div>
                    </div>

                    <!-- Amenities -->
                    <?php if($amenidades && $amenidades->rowCount() > 0): ?>
                    <div class="property-info-card">
                        <h3 class="property-info-title">Comodidades</h3>
                        <div class="property-amenities">
                            <?php while($amenidade = $amenidades->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="amenity-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20,6 9,17 4,12"/></svg>
                                <?= htmlspecialchars($amenidade['nome']) ?>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="property-detail-sidebar">
                    <div class="contact-sidebar">
                        <div class="agent-card">
                            <div class="agent-info">
                                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100&h=100&fit=crop" alt="Corretor" class="agent-avatar">
                                <div>
                                    <div class="agent-name">Fábio Leão</div>
                                    <div class="agent-role">Corretor de Imóveis</div>
                                    <div class="agent-creci">CRECI: 12345-F</div>
                                </div>
                            </div>
                            <form class="contact-form" action="api/contato.php" method="POST">
                                <input type="hidden" name="imovel_id" value="<?= $imovel['id'] ?>">
                                <input type="hidden" name="imovel_titulo" value="<?= htmlspecialchars($imovel['titulo']) ?>">
                                <div class="form-group"><input type="text" name="nome" class="form-control" placeholder="Seu nome" required></div>
                                <div class="form-group"><input type="email" name="email" class="form-control" placeholder="Seu e-mail" required></div>
                                <div class="form-group"><input type="tel" name="telefone" class="form-control" placeholder="Seu telefone" required></div>
                                <div class="form-group"><textarea name="mensagem" class="form-control" rows="3" placeholder="Mensagem">Olá, tenho interesse no imóvel "<?= htmlspecialchars($imovel['titulo']) ?>".</textarea></div>
                                <button type="submit" class="btn btn-primary btn-block">Enviar mensagem</button>
                            </form>
                            <div class="contact-buttons">
                                <a href="https://wa.me/5511999999999?text=<?= urlencode('Olá! Tenho interesse no imóvel: ' . $imovel['titulo']) ?>" target="_blank" class="btn btn-whatsapp btn-block">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp
                                </a>
                                <a href="tel:+5511999999999" class="btn btn-outline btn-block">Ligar agora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Properties -->
    <?php if($relacionados && $relacionados->rowCount() > 0): ?>
    <section class="section bg-gray-50">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Imóveis Relacionados</h2>
                    <p class="section-subtitle">Outros imóveis que podem te interessar</p>
                </div>
            </div>
            <div class="properties-grid">
                <?php while($rel = $relacionados->fetch(PDO::FETCH_ASSOC)): ?>
                <article class="property-card">
                    <a href="imovel.php?id=<?= $rel['id'] ?>" class="property-image">
                        <img src="<?= !empty($rel['imagem_principal']) ? 'uploads/imoveis/' . $rel['imagem_principal'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop' ?>" alt="<?= htmlspecialchars($rel['titulo']) ?>" loading="lazy">
                        <div class="property-badges">
                            <span class="property-badge <?= $rel['finalidade'] == 'venda' ? 'sale' : 'rent' ?>"><?= $rel['finalidade'] == 'venda' ? 'Venda' : 'Aluguel' ?></span>
                        </div>
                    </a>
                    <div class="property-content">
                        <span class="property-type"><?= htmlspecialchars($rel['categoria_nome'] ?? 'Imóvel') ?></span>
                        <h3 class="property-title"><a href="imovel.php?id=<?= $rel['id'] ?>"><?= htmlspecialchars($rel['titulo']) ?></a></h3>
                        <div class="property-location">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?= htmlspecialchars($rel['bairro'] ?? '') ?>, <?= htmlspecialchars($rel['cidade'] ?? '') ?>
                        </div>
                        <div class="property-footer">
                            <div class="property-price">R$ <?= number_format($rel['preco'], 0, ',', '.') ?><?php if($rel['finalidade'] == 'aluguel'): ?><span>/mês</span><?php endif; ?></div>
                        </div>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; <?= date('Y') ?> FABIOLEAO Imobiliária. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        function changeMainImage(thumb) {
            const mainImage = document.getElementById('mainImage');
            const imgSrc = thumb.querySelector('img').src;
            mainImage.src = imgSrc.replace('w=400&h=300', 'w=1200&h=800');
            document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }
    </script>
</body>
</html>
