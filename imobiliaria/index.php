<?php
/**
 * FABIO LEÃO IMOBILIÁRIA
 * Página Inicial
 */

require_once 'config/config.php';

$currentPage = 'home';
$pageTitle = 'Encontre o Imóvel dos Seus Sonhos';

// Buscar dados para a página
$tiposImoveis = getTiposImoveis();
$paises = getPaises();
$imoveisDestaque = getImoveisDestaque(6);

require_once INCLUDES_PATH . 'header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg" style="background-image: url('<?php echo ASSETS_URL; ?>images/hero-bg.jpg');"></div>
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                Imobiliária Premium
            </span>
            <h1 class="hero-title">Encontre o imóvel perfeito para você</h1>
            <p class="hero-description">Descubra casas, apartamentos e propriedades exclusivas para venda e aluguel. Oferecemos imóveis nacionais e internacionais com a melhor experiência do mercado.</p>
            <div class="hero-actions">
                <a href="imoveis.php?finalidade=venda" class="btn btn-primary btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Comprar Imóvel
                </a>
                <a href="imoveis.php?finalidade=aluguel" class="btn btn-outline btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Alugar Imóvel
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="hero-stat-value">500+</span>
                    <span class="hero-stat-label">Imóveis Disponíveis</span>
                </div>
                <div class="hero-stat">
                    <span class="hero-stat-value">10+</span>
                    <span class="hero-stat-label">Países</span>
                </div>
                <div class="hero-stat">
                    <span class="hero-stat-value">1000+</span>
                    <span class="hero-stat-label">Clientes Satisfeitos</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="search-box">
            <div class="search-tabs">
                <button type="button" class="search-tab active" data-finalidade="venda">Comprar</button>
                <button type="button" class="search-tab" data-finalidade="aluguel">Alugar</button>
            </div>
            <form action="imoveis.php" method="GET" class="search-form">
                <input type="hidden" name="finalidade" id="finalidade" value="venda">
                
                <div class="form-group">
                    <label class="form-label">País</label>
                    <select name="pais_id" id="pais_id" class="form-control">
                        <option value="">Todos os Países</option>
                        <?php foreach ($paises as $pais): ?>
                            <option value="<?php echo $pais['id']; ?>"><?php echo htmlspecialchars($pais['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="estado_id" id="estado_id" class="form-control">
                        <option value="">Selecione o Estado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Cidade</label>
                    <select name="cidade_id" id="cidade_id" class="form-control">
                        <option value="">Selecione a Cidade</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipo de Imóvel</label>
                    <select name="tipo_id" class="form-control">
                        <option value="">Todos os Tipos</option>
                        <?php foreach ($tiposImoveis as $tipo): ?>
                            <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nome']); ?></option>
                        <?php endforeach; ?>
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
                        <option value="5">5+</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Preço Máximo</label>
                    <select name="preco_max" class="form-control">
                        <option value="">Sem Limite</option>
                        <option value="200000">Até R$ 200.000</option>
                        <option value="500000">Até R$ 500.000</option>
                        <option value="1000000">Até R$ 1.000.000</option>
                        <option value="2000000">Até R$ 2.000.000</option>
                        <option value="5000000">Até R$ 5.000.000</option>
                    </select>
                </div>

                <div class="search-submit">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        Buscar Imóveis
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Featured Properties -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Imóveis em Destaque</h2>
            <p class="section-subtitle">Conheça nossas melhores oportunidades selecionadas especialmente para você</p>
        </div>

        <div class="properties-grid">
            <?php if (empty($imoveisDestaque)): ?>
                <!-- Demo Properties when no data -->
                <?php for ($i = 1; $i <= 6; $i++): ?>
                <article class="property-card">
                    <div class="property-image">
                        <img src="<?php echo ASSETS_URL; ?>images/property-<?php echo $i; ?>.jpg" alt="Imóvel <?php echo $i; ?>">
                        <span class="property-badge <?php echo $i % 2 == 0 ? 'rent' : 'sale'; ?>">
                            <?php echo $i % 2 == 0 ? 'Aluguel' : 'Venda'; ?>
                        </span>
                        <button class="property-favorite" data-id="<?php echo $i; ?>" aria-label="Adicionar aos favoritos">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </button>
                    </div>
                    <div class="property-content">
                        <div class="property-location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span>São Paulo, Brasil</span>
                        </div>
                        <h3 class="property-title">
                            <a href="imovel.php?id=<?php echo $i; ?>">Apartamento Moderno com Vista Panorâmica</a>
                        </h3>
                        <div class="property-features">
                            <span class="property-feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"></path><path d="M2 8h18a2 2 0 0 1 2 2v10"></path><path d="M2 17h20"></path><path d="M6 8v9"></path></svg>
                                3 Quartos
                            </span>
                            <span class="property-feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"></path><line x1="10" y1="5" x2="8" y2="7"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="7" y1="19" x2="7" y2="21"></line><line x1="17" y1="19" x2="17" y2="21"></line></svg>
                                2 Banheiros
                            </span>
                            <span class="property-feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
                                120 m²
                            </span>
                        </div>
                        <div class="property-footer">
                            <span class="property-price">
                                <?php echo $i % 2 == 0 ? 'R$ 3.500' : 'R$ 850.000'; ?>
                                <?php if ($i % 2 == 0): ?><small>/mês</small><?php endif; ?>
                            </span>
                            <span class="property-type">Apartamento</span>
                        </div>
                    </div>
                </article>
                <?php endfor; ?>
            <?php else: ?>
                <?php foreach ($imoveisDestaque as $imovel): ?>
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
                        <?php if ($imovel['destaque']): ?>
                        <span class="property-badge featured" style="left: auto; right: 50px;">Destaque</span>
                        <?php endif; ?>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"></path><line x1="10" y1="5" x2="8" y2="7"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="7" y1="19" x2="7" y2="21"></line><line x1="17" y1="19" x2="17" y2="21"></line></svg>
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
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: var(--spacing-xl);">
            <a href="imoveis.php" class="btn btn-outline btn-lg">Ver Todos os Imóveis</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section features">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Por que escolher a Fabio Leão?</h2>
            <p class="section-subtitle">Oferecemos a melhor experiência para você encontrar o imóvel ideal</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <h4>Atendimento 24/7</h4>
                <p>Nossa equipe está disponível a qualquer momento para ajudar você a encontrar o imóvel perfeito.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
                </div>
                <h4>Segurança Total</h4>
                <p>Todas as transações são seguras e acompanhadas por profissionais especializados.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                </div>
                <h4>Imóveis Internacionais</h4>
                <p>Acesso a propriedades exclusivas em mais de 10 países ao redor do mundo.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h4>Equipe Especializada</h4>
                <p>Corretores experientes prontos para oferecer o melhor atendimento personalizado.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section cta">
    <div class="container">
        <div class="cta-content">
            <h2>Pronto para encontrar seu novo lar?</h2>
            <p>Entre em contato conosco e deixe nossa equipe ajudar você a realizar o sonho da casa própria.</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="https://wa.me/<?php echo getSiteConfig('site_whatsapp'); ?>" target="_blank" class="btn btn-primary btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Falar no WhatsApp
                </a>
                <a href="contato.php" class="btn btn-white btn-lg">Enviar Mensagem</a>
            </div>
        </div>
    </div>
</section>

<?php require_once INCLUDES_PATH . 'footer.php'; ?>
