<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Imovel.php';
require_once __DIR__ . '/models/Categoria.php';

$imovelModel = new Imovel();
$categoriaModel = new Categoria();

// Obter ID do imóvel
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (empty($id)) {
    header('Location: index.php');
    exit;
}

// Buscar imóvel
$imovel = $imovelModel->getById($id);

if (!$imovel) {
    header('Location: index.php');
    exit;
}

// Incrementar visualizações
$imovelModel->incrementViews($id);

// Buscar imagens da galeria
$imagens = $imovelModel->getImages($id);

// Buscar amenidades
$amenidades = $imovelModel->getAmenidades($id);

// Buscar imóveis similares
$similares = $imovelModel->getSimilares($id, $imovel['categoria_id'], 5);

// Buscar categorias para o menu
$categorias = $categoriaModel->getAll();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($imovel['titulo']) ?> - FABIOLEAO Imobiliária</title>
  <meta name="description" content="<?= htmlspecialchars(substr($imovel['descricao'], 0, 160)) ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- font -->
  <link rel="stylesheet" href="fonts/fonts.css">
  <!-- Icons -->
  <link rel="stylesheet" href="fonts/font-icons.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/swiper-bundle.min.css">
  <link rel="stylesheet" href="css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="css/animate.css">
  <link rel="stylesheet" type="text/css" href="css/styles.css" />
  <!-- Favicon -->
  <link rel="shortcut icon" href="images/logo/favicon.png">
</head>
<body class="body">
  <!-- preload -->
  <div class="preload preload-container">
    <div class="preload-logo">
      <div class="spinner"></div>
      <span class="icon icon-villa-fill"></span>
    </div>
  </div>
  <!-- /preload -->
  <div id="wrapper">
    <div id="pagee" class="clearfix">
      <!-- Main Header -->
      <header class="main-header fixed-header">
        <!-- Header Lower -->
        <div class="header-lower">
          <div class="row">
            <div class="col-lg-12">
              <div class="inner-header">
                <div class="inner-header-left">
                  <div class="logo-box flex">
                    <div class="logo">
                      <a href="index.php">
                        <img src="images/logo/logo@2x.png" alt="logo" width="166" height="48">
                      </a>
                    </div>
                  </div>
                  <div class="nav-outer flex align-center">
                    <nav class="main-menu show navbar-expand-md">
                      <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                        <ul class="navigation clearfix">
                          <li class="dropdown2 home">
                            <a href="index.php">Início</a>
                          </li>
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
                          <li><a href="contato.php">Contato</a></li>
                        </ul>
                      </div>
                    </nav>
                  </div>
                </div>
                <div class="inner-header-right header-account">
                  <a href="admin/login.php" class="tf-btn btn-line btn-login">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M13.1251 5C13.1251 5.8288 12.7959 6.62366 12.2099 7.20971C11.6238 7.79576 10.8289 8.125 10.0001 8.125C9.17134 8.125 8.37649 7.79576 7.79043 7.20971C7.20438 6.62366 6.87514 5.8288 6.87514 5C6.87514 4.1712 7.20438 3.37634 7.79043 2.79029C8.37649 2.20424 9.17134 1.875 10.0001 1.875C10.8289 1.875 11.6238 2.20424 12.2099 2.79029C12.7959 3.37634 13.1251 4.1712 13.1251 5ZM3.75098 16.765C3.77776 15.1253 4.44792 13.5618 5.61696 12.4117C6.78599 11.2616 8.36022 10.6171 10.0001 10.6171C11.6401 10.6171 13.2143 11.2616 14.3833 12.4117C15.5524 13.5618 16.2225 15.1253 16.2493 16.765C14.2888 17.664 12.1569 18.1279 10.0001 18.125C7.77014 18.125 5.65348 17.6383 3.75098 16.765Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Admin
                  </a>
                  <div class="flat-bt-top">
                    <a class="tf-btn primary" href="https://wa.me/5500000000000?text=Olá! Tenho interesse no imóvel: <?= urlencode($imovel['titulo']) ?>" target="_blank">
                      <svg width="21" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" fill="white"/>
                      </svg> WhatsApp
                    </a>
                  </div>
                </div>
                <div class="mobile-nav-toggler mobile-button">
                  <span></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Mobile Menu -->
        <div class="close-btn">
          <span class="icon flaticon-cancel-1"></span>
        </div>
        <div class="mobile-menu">
          <div class="menu-backdrop"></div>
          <nav class="menu-box">
            <div class="nav-logo">
              <a href="index.php">
                <img src="images/logo/logo@2x.png" alt="nav-logo" width="174" height="44">
              </a>
            </div>
            <div class="bottom-canvas">
              <div class="menu-outer"></div>
              <div class="button-mobi-sell">
                <a class="tf-btn primary" href="https://wa.me/5500000000000" target="_blank">WhatsApp</a>
              </div>
            </div>
          </nav>
        </div>
      </header>
      <!-- End Main Header -->

      <div class="flat-section-v4">
        <div class="container">
          <div class="header-property-detail">
            <div class="content-top d-flex justify-content-between align-items-center">
              <h3 class="title link fw-8"><?= htmlspecialchars($imovel['titulo']) ?></h3>
              <div class="box-price d-flex align-items-end">
                <h3 class="fw-8">R$ <?= number_format($imovel['preco'], 2, ',', '.') ?></h3>
                <?php if($imovel['tipo_negocio'] == 'aluguel'): ?>
                <span class="body-1 text-variant-1">/mês</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="content-bottom">
              <div class="box-left">
                <div class="info-box">
                  <div class="label">Características</div>
                  <ul class="meta">
                    <li class="meta-item">
                      <i class="icon icon-bed"></i>
                      <span class="text-variant-1">Quartos:</span>
                      <span class="fw-6"><?= $imovel['quartos'] ?></span>
                    </li>
                    <li class="meta-item">
                      <i class="icon icon-bath"></i>
                      <span class="text-variant-1">Banheiros:</span>
                      <span class="fw-6"><?= $imovel['banheiros'] ?></span>
                    </li>
                    <li class="meta-item">
                      <i class="icon icon-sqft"></i>
                      <span class="text-variant-1">Área:</span>
                      <span class="fw-6"><?= $imovel['area_total'] ?>m²</span>
                    </li>
                  </ul>
                </div>
                <div class="info-box">
                  <div class="label">Localização</div>
                  <p class="meta-item">
                    <span class="icon icon-mapPin"></span>
                    <span class="text-variant-1"><?= htmlspecialchars($imovel['endereco'] . ', ' . $imovel['bairro'] . ', ' . $imovel['cidade'] . ' - ' . $imovel['estado']) ?></span>
                  </p>
                </div>
              </div>
              <ul class="icon-box">
                <li>
                  <a href="#" class="item">
                    <svg class="icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M15.75 6.1875C15.75 4.32375 14.1758 2.8125 12.234 2.8125C10.7828 2.8125 9.53625 3.657 9 4.86225C8.46375 3.657 7.21725 2.8125 5.76525 2.8125C3.825 2.8125 2.25 4.32375 2.25 6.1875C2.25 11.6025 9 15.1875 9 15.1875C9 15.1875 15.75 11.6025 15.75 6.1875Z" stroke="#A3ABB0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </a>
                </li>
                <li>
                  <a href="https://wa.me/5500000000000?text=<?= urlencode('Olá! Tenho interesse no imóvel: ' . $imovel['titulo']) ?>" target="_blank" class="item">
                    <svg class="icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M5.625 15.75L2.25 12.375M2.25 12.375L5.625 9M2.25 12.375H12.375M12.375 2.25L15.75 5.625M15.75 5.625L12.375 9M15.75 5.625H5.625" stroke="#A3ABB0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Slider de Imagens -->
      <section class="flat-slider-detail-v1 px-10">
        <div dir="ltr" class="swiper tf-sw-location" data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space-lg="10" data-space-md="10" data-space="10" data-pagination="1" data-pagination-sm="2" data-pagination-md="2" data-pagination-lg="3">
          <div class="swiper-wrapper">
            <?php if(!empty($imovel['imagem_principal'])): ?>
            <div class="swiper-slide">
              <a href="uploads/imoveis/<?= $imovel['imagem_principal'] ?>" data-fancybox="gallery" class="box-img-detail d-block">
                <img src="uploads/imoveis/<?= $imovel['imagem_principal'] ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>">
              </a>
            </div>
            <?php endif; ?>
            <?php foreach($imagens as $img): ?>
            <div class="swiper-slide">
              <a href="uploads/imoveis/<?= $img['imagem'] ?>" data-fancybox="gallery" class="box-img-detail d-block">
                <img src="uploads/imoveis/<?= $img['imagem'] ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>">
              </a>
            </div>
            <?php endforeach; ?>
            <?php if(empty($imagens) && empty($imovel['imagem_principal'])): ?>
            <div class="swiper-slide">
              <a href="images/home/house-1.jpg" data-fancybox="gallery" class="box-img-detail d-block">
                <img src="images/home/house-1.jpg" alt="Imagem não disponível">
              </a>
            </div>
            <?php endif; ?>
          </div>
          <div class="sw-pagination sw-pagination-location text-center"></div>
        </div>
      </section>

      <section class="flat-section-v3 flat-property-detail">
        <div class="container">
          <div class="row">
            <div class="col-xl-8 col-lg-7">
              <!-- Descrição -->
              <div class="single-property-element single-property-desc">
                <h5 class="fw-6 title">Descrição</h5>
                <p class="text-variant-1"><?= nl2br(htmlspecialchars($imovel['descricao'])) ?></p>
              </div>

              <!-- Overview -->
              <div class="single-property-element single-property-overview">
                <h6 class="title fw-6">Visão Geral</h6>
                <ul class="info-box">
                  <li class="item">
                    <a href="#" class="box-icon w-52">
                      <i class="icon icon-house-line"></i>
                    </a>
                    <div class="content">
                      <span class="label">Código:</span>
                      <span><?= $imovel['id'] ?></span>
                    </div>
                  </li>
                  <li class="item">
                    <a href="#" class="box-icon w-52">
                      <i class="icon icon-sliders-horizontal"></i>
                    </a>
                    <div class="content">
                      <span class="label">Tipo:</span>
                      <span><?= htmlspecialchars($imovel['categoria_nome'] ?? 'Não informado') ?></span>
                    </div>
                  </li>
                  <li class="item">
                    <a href="#" class="box-icon w-52">
                      <i class="icon icon-garage"></i>
                    </a>
                    <div class="content">
                      <span class="label">Vagas:</span>
                      <span><?= $imovel['vagas'] ?? 0 ?></span>
                    </div>
                  </li>
                  <li class="item">
                    <a href="#" class="box-icon w-52">
                      <i class="icon icon-bed1"></i>
                    </a>
                    <div class="content">
                      <span class="label">Quartos:</span>
                      <span><?= $imovel['quartos'] ?></span>
                    </div>
                  </li>
                  <li class="item">
                    <a href="#" class="box-icon w-52">
                      <i class="icon icon-bathtub"></i>
                    </a>
                    <div class="content">
                      <span class="label">Banheiros:</span>
                      <span><?= $imovel['banheiros'] ?></span>
                    </div>
                  </li>
                  <li class="item">
                    <a href="#" class="box-icon w-52">
                      <i class="icon icon-ruler"></i>
                    </a>
                    <div class="content">
                      <span class="label">Área Total:</span>
                      <span><?= $imovel['area_total'] ?>m²</span>
                    </div>
                  </li>
                  <?php if(!empty($imovel['suites'])): ?>
                  <li class="item">
                    <a href="#" class="box-icon w-52">
                      <i class="icon icon-bed"></i>
                    </a>
                    <div class="content">
                      <span class="label">Suítes:</span>
                      <span><?= $imovel['suites'] ?></span>
                    </div>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>

              <!-- Amenidades -->
              <?php if(!empty($amenidades)): ?>
              <div class="single-property-element single-property-amenities">
                <h6 class="title fw-6">Amenidades</h6>
                <div class="wrap-amenities">
                  <?php foreach($amenidades as $amenidade): ?>
                  <div class="box-amenities">
                    <i class="icon icon-check"></i>
                    <span><?= htmlspecialchars($amenidade['nome']) ?></span>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>

              <!-- Localização -->
              <div class="single-property-element single-property-map">
                <h6 class="title fw-6">Localização</h6>
                <p class="text-variant-1">
                  <strong>Endereço:</strong> <?= htmlspecialchars($imovel['endereco']) ?><br>
                  <strong>Bairro:</strong> <?= htmlspecialchars($imovel['bairro']) ?><br>
                  <strong>Cidade:</strong> <?= htmlspecialchars($imovel['cidade']) ?> - <?= htmlspecialchars($imovel['estado']) ?>
                  <?php if(!empty($imovel['cep'])): ?><br><strong>CEP:</strong> <?= htmlspecialchars($imovel['cep']) ?><?php endif; ?>
                </p>
              </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
              <div class="widget-sidebar fixed-sidebar">
                <!-- Contato -->
                <div class="widget-box single-property-contact">
                  <h6 class="title fw-6">Entre em Contato</h6>
                  <form action="contato-enviar.php" method="POST">
                    <input type="hidden" name="imovel_id" value="<?= $imovel['id'] ?>">
                    <input type="hidden" name="imovel_titulo" value="<?= htmlspecialchars($imovel['titulo']) ?>">
                    <div class="ip-group">
                      <label>Nome Completo:</label>
                      <input type="text" name="nome" class="form-control" placeholder="Seu nome" required>
                    </div>
                    <div class="ip-group">
                      <label>E-mail:</label>
                      <input type="email" name="email" class="form-control" placeholder="Seu e-mail" required>
                    </div>
                    <div class="ip-group">
                      <label>Telefone:</label>
                      <input type="tel" name="telefone" class="form-control" placeholder="Seu telefone" required>
                    </div>
                    <div class="ip-group">
                      <label>Mensagem:</label>
                      <textarea name="mensagem" class="form-control" rows="3" placeholder="Sua mensagem">Olá! Tenho interesse no imóvel: <?= htmlspecialchars($imovel['titulo']) ?></textarea>
                    </div>
                    <button type="submit" class="tf-btn primary w-100">Enviar Mensagem</button>
                  </form>
                  <a href="https://wa.me/5500000000000?text=<?= urlencode('Olá! Tenho interesse no imóvel: ' . $imovel['titulo']) ?>" target="_blank" class="tf-btn btn-line w-100 mt-10">
                    <svg width="21" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884" fill="currentColor"/>
                    </svg>
                    Falar pelo WhatsApp
                  </a>
                </div>

                <!-- Imóveis Similares -->
                <?php if(!empty($similares)): ?>
                <div class="widget-box single-property-similar">
                  <h6 class="title fw-6">Imóveis Similares</h6>
                  <ul class="recent-post-wrap">
                    <?php foreach($similares as $similar): ?>
                    <li class="latest-property-item">
                      <a href="imovel.php?id=<?= $similar['id'] ?>" class="images-style">
                        <img src="<?= !empty($similar['imagem_principal']) ? 'uploads/imoveis/'.$similar['imagem_principal'] : 'images/home/house-1.jpg' ?>" alt="<?= htmlspecialchars($similar['titulo']) ?>">
                      </a>
                      <div class="content">
                        <div class="text-capitalize text-btn">
                          <a href="imovel.php?id=<?= $similar['id'] ?>" class="link"><?= htmlspecialchars($similar['titulo']) ?></a>
                        </div>
                        <ul class="meta-list mt-6">
                          <li class="item">
                            <i class="icon icon-bed"></i>
                            <span class="text-variant-1">Quartos:</span>
                            <span class="fw-6"><?= $similar['quartos'] ?></span>
                          </li>
                          <li class="item">
                            <i class="icon icon-bath"></i>
                            <span class="text-variant-1">Banheiros:</span>
                            <span class="fw-6"><?= $similar['banheiros'] ?></span>
                          </li>
                        </ul>
                        <div class="mt-10 text-btn">R$ <?= number_format($similar['preco'], 2, ',', '.') ?></div>
                      </div>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- footer -->
      <footer class="footer">
        <div class="top-footer">
          <div class="container">
            <div class="content-footer-top">
              <div class="footer-logo">
                <a href="index.php">
                  <img src="images/logo/logo-footer@2x.png" alt="logo" width="166" height="48">
                </a>
              </div>
              <div class="wd-social">
                <span>Siga-nos:</span>
                <ul class="list-social d-flex align-items-center">
                  <li>
                    <a href="#" class="box-icon w-40 social">
                      <svg class="icon" width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.60547 9L8.00541 6.10437H5.50481V4.22531C5.50481 3.43313 5.85413 2.66094 6.97406 2.66094H8.11087V0.195625C8.11087 0.195625 7.07925 0 6.09291 0C4.03359 0 2.68753 1.38688 2.68753 3.8975V6.10437H0.398438V9H2.68753V16H5.50481V9H7.60547Z" fill="white" />
                      </svg>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="box-icon w-40 social">
                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.99812 4.66567C5.71277 4.66567 4.66383 5.71463 4.66383 7C4.66383 8.28537 5.71277 9.33433 6.99812 9.33433C8.28346 9.33433 9.3324 8.28537 9.3324 7C9.3324 5.71463 8.28346 4.66567 6.99812 4.66567ZM13.9992 7C13.9992 6.03335 14.008 5.07545 13.9537 4.11055C13.8994 2.98979 13.6437 1.99512 12.8242 1.17556C12.0029 0.35426 11.01 0.100338 9.88927 0.0460516C8.92263 -0.00823506 7.96475 0.000520879 6.99987 0.000520879C6.03323 0.000520879 5.07536 -0.00823506 4.11047 0.0460516C2.98973 0.100338 1.99508 0.356011 1.17554 1.17556C0.354253 1.99687 0.100336 2.98979 0.0460508 4.11055C-0.00823491 5.0772 0.00052087 6.0351 0.00052087 7C0.00052087 7.9649 -0.00823491 8.92455 0.0460508 9.88945C0.100336 11.0102 0.356004 12.0049 1.17554 12.8244C1.99683 13.6457 2.98973 13.8997 4.11047 13.9539C5.07711 14.0082 6.03499 13.9995 6.99987 13.9995C7.9665 13.9995 8.92438 14.0082 9.88927 13.9539C11.01 13.8997 12.0047 13.644 12.8242 12.8244C13.6455 12.0031 13.8994 11.0102 13.9537 9.88945C14.0097 8.92455 13.9992 7.96665 13.9992 7ZM6.99812 10.5917C5.01056 10.5917 3.40651 8.98759 3.40651 7C3.40651 5.01241 5.01056 3.40832 6.99812 3.40832C8.98567 3.40832 10.5897 5.01241 10.5897 7C10.5897 8.98759 8.98567 10.5917 6.99812 10.5917ZM10.7368 4.10004C10.2728 4.10004 9.89802 3.72529 9.89802 3.26122C9.89802 2.79716 10.2728 2.42241 10.7368 2.42241C11.2009 2.42241 11.5756 2.79716 11.5756 3.26122C11.5758 3.37142 11.5542 3.48056 11.5121 3.58239C11.47 3.68422 11.4082 3.77675 11.3303 3.85467C11.2523 3.93258 11.1598 3.99437 11.058 4.03647C10.9562 4.07858 10.847 4.10018 10.7368 4.10004Z" fill="white" />
                      </svg>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="inner-footer">
          <div class="container">
            <div class="row">
              <div class="col-lg-4 col-md-6">
                <div class="footer-cl-1">
                  <p class="text-variant-2">Especializada em oferecer imóveis de alta qualidade para quem busca o melhor. Entre em contato!</p>
                  <ul class="mt-12">
                    <li class="mt-12 d-flex align-items-center gap-8">
                      <i class="icon icon-mapPinLine fs-20 text-variant-2"></i>
                      <p class="text-white">Seu endereço aqui, Cidade - Estado</p>
                    </li>
                    <li class="mt-12 d-flex align-items-center gap-8">
                      <i class="icon icon-phone2 fs-20 text-variant-2"></i>
                      <a href="tel:0000000000" class="text-white caption-1">(00) 0000-0000</a>
                    </li>
                    <li class="mt-12 d-flex align-items-center gap-8">
                      <i class="icon icon-mail fs-20 text-variant-2"></i>
                      <p class="text-white">contato@fabioleao.com.br</p>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <div class="footer-cl-2 footer-col-block">
                  <div class="fw-7 text-white footer-heading-mobile">Links</div>
                  <div class="tf-collapse-content">
                    <ul class="mt-10 navigation-menu-footer">
                      <li><a href="index.php" class="caption-1 text-variant-2">Início</a></li>
                      <li><a href="busca.php?tipo_negocio=venda" class="caption-1 text-variant-2">Comprar</a></li>
                      <li><a href="busca.php?tipo_negocio=aluguel" class="caption-1 text-variant-2">Alugar</a></li>
                      <li><a href="contato.php" class="caption-1 text-variant-2">Contato</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <div class="footer-cl-3 footer-col-block">
                  <div class="fw-7 text-white footer-heading-mobile">Categorias</div>
                  <div class="tf-collapse-content">
                    <ul class="mt-10 navigation-menu-footer">
                      <?php foreach($categorias as $cat): ?>
                      <li><a href="busca.php?categoria=<?= $cat['slug'] ?>" class="caption-1 text-variant-2"><?= htmlspecialchars($cat['nome']) ?></a></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="footer-cl-4 footer-col-block">
                  <div class="fw-7 text-white footer-heading-mobile">Newsletter</div>
                  <div class="tf-collapse-content">
                    <p class="mt-12 text-variant-2">Receba as melhores ofertas de imóveis diretamente no seu e-mail.</p>
                    <form class="mt-12" id="subscribe-form" action="#" method="post">
                      <div id="subscribe-content">
                        <input type="email" name="email-form" id="subscribe-email" placeholder="Seu e-mail" />
                        <button type="button" id="subscribe-button" class="button-subscribe">
                          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.00044 9.99935L2.72461 2.60352C8.16867 4.18685 13.3024 6.68806 17.9046 9.99935C13.3027 13.3106 8.16921 15.8118 2.72544 17.3952L5.00044 9.99935ZM5.00044 9.99935H11.2504" stroke="#1563DF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bottom-footer">
          <div class="container">
            <div class="content-footer-bottom">
              <div class="copyright">&copy;<?= date('Y') ?> FABIOLEAO Imobiliária. Todos os direitos reservados.</div>
              <ul class="menu-bottom">
                <li><a href="#">Termos de Uso</a></li>
                <li><a href="#">Política de Privacidade</a></li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
      <!-- end footer -->
    </div>
  </div>
  <!-- go top -->
  <div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
  </div>

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/swiper-bundle.min.js"></script>
  <script src="js/carousel.js"></script>
  <script src="js/lazysize.min.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/wow.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
