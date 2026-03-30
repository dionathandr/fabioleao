<?php
/**
 * FABIO LEÃO IMOBILIÁRIA
 * Página de Detalhes do Imóvel
 */

require_once 'config/config.php';

$currentPage = 'imoveis';

// Obter ID do imóvel
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    redirect(BASE_URL . 'imoveis.php');
}

// Buscar imóvel
$imovel = getImovelById($id);

if (!$imovel) {
    redirect(BASE_URL . 'imoveis.php');
}

$pageTitle = $imovel['titulo'];

// Buscar imóveis similares
$similares = getImoveisSimilares($imovel, 4);

// WhatsApp message
$whatsappMsg = urlencode("Olá! Tenho interesse no imóvel: " . $imovel['titulo'] . " - " . BASE_URL . "imovel.php?id=" . $id);

require_once INCLUDES_PATH . 'header.php';
?>

<section class="property-detail">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="font-size: 0.875rem; color: var(--text-light); margin-bottom: var(--spacing-lg);">
            <a href="<?php echo BASE_URL; ?>">Início</a> / 
            <a href="<?php echo BASE_URL; ?>imoveis.php">Imóveis</a> / 
            <span><?php echo htmlspecialchars($imovel['titulo']); ?></span>
        </nav>

        <!-- Gallery -->
        <div class="property-gallery">
            <?php if (!empty($imovel['imagens'])): ?>
                <div class="gallery-main">
                    <?php 
                    $mainImage = $imovel['imagens'][0];
                    $mainSrc = UPLOADS_URL . $mainImage['caminho'];
                    ?>
                    <img src="<?php echo $mainSrc; ?>" alt="<?php echo htmlspecialchars($imovel['titulo']); ?>" id="mainImage">
                </div>
                <?php if (count($imovel['imagens']) > 1): ?>
                <div class="gallery-thumbs">
                    <?php foreach ($imovel['imagens'] as $index => $imagem): ?>
                        <div class="gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo UPLOADS_URL . dirname($imagem['caminho']) . '/thumb_' . basename($imagem['caminho']); ?>" 
                                 alt="<?php echo htmlspecialchars($imagem['titulo'] ?: 'Imagem ' . ($index + 1)); ?>"
                                 data-full="<?php echo UPLOADS_URL . $imagem['caminho']; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="gallery-main">
                    <img src="<?php echo ASSETS_URL; ?>images/no-image.jpg" alt="Sem imagem">
                </div>
            <?php endif; ?>
        </div>

        <!-- Property Info -->
        <div class="property-info">
            <!-- Main Content -->
            <div class="property-main">
                <!-- Header -->
                <div class="property-header">
                    <div class="property-header-top">
                        <div>
                            <span class="property-badge <?php echo $imovel['finalidade'] == 'aluguel' ? 'rent' : 'sale'; ?>">
                                <?php 
                                if ($imovel['finalidade'] == 'ambos') {
                                    echo 'Venda / Aluguel';
                                } elseif ($imovel['finalidade'] == 'aluguel') {
                                    echo 'Aluguel';
                                } else {
                                    echo 'Venda';
                                }
                                ?>
                            </span>
                            <span class="property-type" style="margin-left: 8px;"><?php echo htmlspecialchars($imovel['tipo_nome']); ?></span>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button onclick="copyToClipboard('<?php echo BASE_URL . 'imovel.php?id=' . $id; ?>')" class="btn btn-outline btn-sm" title="Copiar link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                            </button>
                            <button onclick="printProperty()" class="btn btn-outline btn-sm" title="Imprimir">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            </button>
                        </div>
                    </div>
                    <h1 class="property-title"><?php echo htmlspecialchars($imovel['titulo']); ?></h1>
                    <div class="property-location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span>
                            <?php 
                            $location = [];
                            if ($imovel['bairro']) $location[] = $imovel['bairro'];
                            if ($imovel['cidade_nome']) $location[] = $imovel['cidade_nome'];
                            if ($imovel['estado_nome']) $location[] = $imovel['estado_nome'];
                            if ($imovel['pais_nome']) $location[] = $imovel['pais_nome'];
                            echo htmlspecialchars(implode(', ', $location));
                            ?>
                        </span>
                    </div>
                    <div class="property-price" style="margin-top: var(--spacing-md);">
                        <?php 
                        if ($imovel['finalidade'] == 'aluguel' || $imovel['finalidade'] == 'ambos') {
                            if ($imovel['preco_aluguel']) {
                                echo formatPrice($imovel['preco_aluguel']) . '<small>/mês</small>';
                            }
                        }
                        if ($imovel['finalidade'] == 'venda' || $imovel['finalidade'] == 'ambos') {
                            if ($imovel['preco_venda']) {
                                if ($imovel['finalidade'] == 'ambos' && $imovel['preco_aluguel']) {
                                    echo ' <span style="font-size: 1rem; color: var(--text-light); margin: 0 0.5rem;">ou</span> ';
                                }
                                echo formatPrice($imovel['preco_venda']);
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Highlights -->
                <div class="property-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"></path><path d="M2 8h18a2 2 0 0 1 2 2v10"></path><path d="M2 17h20"></path><path d="M6 8v9"></path></svg>
                        </div>
                        <span class="highlight-value"><?php echo $imovel['quartos']; ?></span>
                        <span class="highlight-label">Quartos</span>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"></path><line x1="10" y1="5" x2="8" y2="7"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg>
                        </div>
                        <span class="highlight-value"><?php echo $imovel['banheiros']; ?></span>
                        <span class="highlight-label">Banheiros</span>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"></rect><path d="M10 8V5c0-.6-.4-1-1-1H6a1 1 0 0 0-1 1v3"></path><path d="M19 8V5c0-.6-.4-1-1-1h-3a1 1 0 0 0-1 1v3"></path><path d="M5 14v4c0 1.1.9 2 2 2h10a2 2 0 0 0 2-2v-4"></path></svg>
                        </div>
                        <span class="highlight-value"><?php echo $imovel['suites']; ?></span>
                        <span class="highlight-label">Suítes</span>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg>
                        </div>
                        <span class="highlight-value"><?php echo $imovel['vagas_garagem']; ?></span>
                        <span class="highlight-label">Vagas</span>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
                        </div>
                        <span class="highlight-value"><?php echo formatArea($imovel['area_construida']); ?></span>
                        <span class="highlight-label">Área Construída</span>
                    </div>
                    <?php if ($imovel['area_total']): ?>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        </div>
                        <span class="highlight-value"><?php echo formatArea($imovel['area_total']); ?></span>
                        <span class="highlight-label">Área Total</span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div class="property-description">
                    <h3>Descrição</h3>
                    <p><?php echo nl2br(htmlspecialchars($imovel['descricao'])); ?></p>
                </div>

                <!-- Amenities -->
                <?php if (!empty($imovel['caracteristicas'])): ?>
                <div class="property-amenities">
                    <h3>Características</h3>
                    <div class="amenities-grid">
                        <?php foreach ($imovel['caracteristicas'] as $caracteristica): ?>
                        <div class="amenity-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <?php echo htmlspecialchars($caracteristica['nome']); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Location Map Placeholder -->
                <?php if ($imovel['latitude'] && $imovel['longitude']): ?>
                <div class="property-map" style="margin-bottom: var(--spacing-xl);">
                    <h3 style="margin-bottom: var(--spacing-md);">Localização</h3>
                    <div style="height: 400px; background: var(--bg-light); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                        <p style="color: var(--text-light);">
                            <a href="https://www.google.com/maps?q=<?php echo $imovel['latitude']; ?>,<?php echo $imovel['longitude']; ?>" target="_blank" class="btn btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Ver no Google Maps
                            </a>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="property-sidebar">
                <!-- Contact Card -->
                <div class="sidebar-card">
                    <h4>Interessado neste imóvel?</h4>
                    <p style="font-size: 0.875rem; color: var(--text-light); margin-bottom: var(--spacing-md);">
                        Entre em contato conosco e agende uma visita.
                    </p>
                    
                    <a href="https://wa.me/<?php echo getSiteConfig('site_whatsapp'); ?>?text=<?php echo $whatsappMsg; ?>" 
                       target="_blank" 
                       class="whatsapp-btn" 
                       style="margin-bottom: var(--spacing-md);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Falar no WhatsApp
                    </a>

                    <form id="contactForm" class="contact-form">
                        <input type="hidden" name="imovel_id" value="<?php echo $imovel['id']; ?>">
                        
                        <div class="form-group">
                            <input type="text" name="nome" class="form-control" placeholder="Seu nome" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Seu e-mail" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="telefone" class="form-control" placeholder="Seu telefone">
                        </div>
                        <div class="form-group">
                            <textarea name="mensagem" class="form-control" rows="3" placeholder="Sua mensagem">Olá, tenho interesse no imóvel "<?php echo htmlspecialchars($imovel['titulo']); ?>". Gostaria de mais informações.</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            Enviar Mensagem
                        </button>
                    </form>
                </div>

                <!-- Property Stats -->
                <div class="sidebar-card">
                    <h4>Informações</h4>
                    <ul style="list-style: none; font-size: 0.9375rem;">
                        <li style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                            <span style="color: var(--text-light);">Código</span>
                            <span style="font-weight: 500;">#<?php echo str_pad($imovel['id'], 5, '0', STR_PAD_LEFT); ?></span>
                        </li>
                        <li style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                            <span style="color: var(--text-light);">Tipo</span>
                            <span style="font-weight: 500;"><?php echo htmlspecialchars($imovel['tipo_nome']); ?></span>
                        </li>
                        <li style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                            <span style="color: var(--text-light);">Finalidade</span>
                            <span style="font-weight: 500;">
                                <?php 
                                if ($imovel['finalidade'] == 'ambos') echo 'Venda / Aluguel';
                                elseif ($imovel['finalidade'] == 'aluguel') echo 'Aluguel';
                                else echo 'Venda';
                                ?>
                            </span>
                        </li>
                        <li style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                            <span style="color: var(--text-light);">Visualizações</span>
                            <span style="font-weight: 500;"><?php echo number_format($imovel['visualizacoes'], 0, ',', '.'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Similar Properties -->
        <?php if (!empty($similares)): ?>
        <section style="margin-top: var(--spacing-3xl);">
            <h2 style="font-family: var(--font-display); font-size: 1.75rem; margin-bottom: var(--spacing-lg);">Imóveis Similares</h2>
            <div class="properties-grid">
                <?php foreach ($similares as $similar): ?>
                <article class="property-card">
                    <div class="property-image">
                        <?php 
                        $imgSrc = $similar['imagem_principal'] 
                            ? UPLOADS_URL . $similar['imagem_principal']
                            : ASSETS_URL . 'images/no-image.jpg';
                        ?>
                        <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($similar['titulo']); ?>">
                        <span class="property-badge <?php echo $similar['finalidade'] == 'aluguel' ? 'rent' : 'sale'; ?>">
                            <?php echo $similar['finalidade'] == 'aluguel' ? 'Aluguel' : 'Venda'; ?>
                        </span>
                    </div>
                    <div class="property-content">
                        <div class="property-location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span><?php echo htmlspecialchars($similar['cidade_nome']); ?></span>
                        </div>
                        <h3 class="property-title">
                            <a href="imovel.php?id=<?php echo $similar['id']; ?>"><?php echo htmlspecialchars($similar['titulo']); ?></a>
                        </h3>
                        <div class="property-footer">
                            <span class="property-price">
                                <?php 
                                if ($similar['finalidade'] == 'aluguel') {
                                    echo formatPrice($similar['preco_aluguel']) . '<small>/mês</small>';
                                } else {
                                    echo formatPrice($similar['preco_venda']);
                                }
                                ?>
                            </span>
                            <span class="property-type"><?php echo htmlspecialchars($similar['tipo_nome']); ?></span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</section>

<script>
// Gallery functionality
document.querySelectorAll('.gallery-thumb').forEach(thumb => {
    thumb.addEventListener('click', function() {
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('mainImage').src = this.querySelector('img').dataset.full;
    });
});
</script>

<?php require_once INCLUDES_PATH . 'footer.php'; ?>
