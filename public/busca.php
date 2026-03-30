<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Imovel.php';
require_once __DIR__ . '/models/Categoria.php';

$imovelModel = new Imovel();
$categoriaModel = new Categoria();

// Obter parâmetros de busca
$filters = [
    'tipo_negocio' => isset($_GET['tipo_negocio']) ? sanitize($_GET['tipo_negocio']) : '',
    'categoria' => isset($_GET['categoria']) ? sanitize($_GET['categoria']) : '',
    'localizacao' => isset($_GET['localizacao']) ? sanitize($_GET['localizacao']) : '',
    'keyword' => isset($_GET['keyword']) ? sanitize($_GET['keyword']) : '',
    'preco_min' => isset($_GET['preco_min']) && is_numeric($_GET['preco_min']) ? (float)$_GET['preco_min'] : '',
    'preco_max' => isset($_GET['preco_max']) && is_numeric($_GET['preco_max']) ? (float)$_GET['preco_max'] : '',
    'quartos' => isset($_GET['quartos']) && is_numeric($_GET['quartos']) ? (int)$_GET['quartos'] : '',
    'banheiros' => isset($_GET['banheiros']) && is_numeric($_GET['banheiros']) ? (int)$_GET['banheiros'] : '',
    'area_min' => isset($_GET['area_min']) && is_numeric($_GET['area_min']) ? (float)$_GET['area_min'] : '',
    'area_max' => isset($_GET['area_max']) && is_numeric($_GET['area_max']) ? (float)$_GET['area_max'] : '',
];

// Paginação
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Buscar imóveis
$imoveis = $imovelModel->search($filters, $perPage, $offset);
$totalImoveis = $imovelModel->countSearch($filters);
$totalPages = ceil($totalImoveis / $perPage);

// Buscar categorias
$categorias = $categoriaModel->getAll();

// Obter categoria selecionada
$categoriaSelecionada = null;
if ($filters['categoria']) {
    $categoriaSelecionada = $categoriaModel->getBySlug($filters['categoria']);
}

// Construir título da página
$pageTitle = 'Imóveis';
if ($filters['tipo_negocio'] == 'venda') {
    $pageTitle = 'Imóveis à Venda';
} elseif ($filters['tipo_negocio'] == 'aluguel') {
    $pageTitle = 'Imóveis para Alugar';
}
if ($categoriaSelecionada) {
    $pageTitle = $categoriaSelecionada['nome'] . ' - ' . $pageTitle;
}

// Construir query string para paginação
function buildQueryString($params, $exclude = []) {
    $query = [];
    foreach ($params as $key => $value) {
        if (!in_array($key, $exclude) && $value !== '' && $value !== null) {
            $query[$key] = $value;
        }
    }
    return http_build_query($query);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($pageTitle) ?> - FABIOLEAO Imobiliária</title>
  <meta name="description" content="<?= htmlspecialchars($pageTitle) ?>. Encontre o imóvel ideal para você.">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- font -->
  <link rel="stylesheet" href="fonts/fonts.css">
  <!-- Icons -->
  <link rel="stylesheet" href="fonts/font-icons.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/swiper-bundle.min.css">
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
                          <li class="dropdown2 current">
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
                    <a class="tf-btn primary" href="https://wa.me/5500000000000" target="_blank">
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

      <!-- Page Title -->
      <section class="flat-title-page">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="page-title">
                <h2><?= htmlspecialchars($pageTitle) ?></h2>
              </div>
              <ul class="breadcrumb">
                <li><a href="index.php">Início</a></li>
                <li>/</li>
                <li><?= htmlspecialchars($pageTitle) ?></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- Property Listing Section -->
      <section class="flat-section flat-properties-list">
        <div class="container">
          <div class="row">
            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5 sidebar-left">
              <div class="widget-sidebar fixed-sidebar">
                <div class="flat-tab flat-tab-form widget-filter">
                  <div class="widget-box single-property-contact">
                    <h6 class="title fw-6">Filtrar Imóveis</h6>
                    <form method="get" action="busca.php">
                      <div class="ip-group">
                        <label>Tipo de Negócio:</label>
                        <select name="tipo_negocio" class="nice-select form-control">
                          <option value="">Todos</option>
                          <option value="venda" <?= $filters['tipo_negocio'] == 'venda' ? 'selected' : '' ?>>Venda</option>
                          <option value="aluguel" <?= $filters['tipo_negocio'] == 'aluguel' ? 'selected' : '' ?>>Aluguel</option>
                        </select>
                      </div>
                      <div class="ip-group">
                        <label>Categoria:</label>
                        <select name="categoria" class="nice-select form-control">
                          <option value="">Todas</option>
                          <?php foreach($categorias as $cat): ?>
                          <option value="<?= $cat['slug'] ?>" <?= $filters['categoria'] == $cat['slug'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nome']) ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="ip-group">
                        <label>Localização:</label>
                        <input type="text" name="localizacao" class="form-control" placeholder="Cidade, bairro..." value="<?= htmlspecialchars($filters['localizacao']) ?>">
                      </div>
                      <div class="ip-group">
                        <label>Palavra-chave:</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Buscar..." value="<?= htmlspecialchars($filters['keyword']) ?>">
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="ip-group">
                            <label>Preço Mín:</label>
                            <input type="number" name="preco_min" class="form-control" placeholder="R$" value="<?= $filters['preco_min'] ?>">
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="ip-group">
                            <label>Preço Máx:</label>
                            <input type="number" name="preco_max" class="form-control" placeholder="R$" value="<?= $filters['preco_max'] ?>">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="ip-group">
                            <label>Quartos:</label>
                            <select name="quartos" class="nice-select form-control">
                              <option value="">Todos</option>
                              <option value="1" <?= $filters['quartos'] == 1 ? 'selected' : '' ?>>1+</option>
                              <option value="2" <?= $filters['quartos'] == 2 ? 'selected' : '' ?>>2+</option>
                              <option value="3" <?= $filters['quartos'] == 3 ? 'selected' : '' ?>>3+</option>
                              <option value="4" <?= $filters['quartos'] == 4 ? 'selected' : '' ?>>4+</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="ip-group">
                            <label>Banheiros:</label>
                            <select name="banheiros" class="nice-select form-control">
                              <option value="">Todos</option>
                              <option value="1" <?= $filters['banheiros'] == 1 ? 'selected' : '' ?>>1+</option>
                              <option value="2" <?= $filters['banheiros'] == 2 ? 'selected' : '' ?>>2+</option>
                              <option value="3" <?= $filters['banheiros'] == 3 ? 'selected' : '' ?>>3+</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <button type="submit" class="tf-btn primary w-100">
                        <i class="icon icon-search"></i> Buscar
                      </button>
                      <?php if(!empty($filters['tipo_negocio']) || !empty($filters['categoria']) || !empty($filters['localizacao']) || !empty($filters['keyword'])): ?>
                      <a href="busca.php" class="tf-btn btn-line w-100 mt-10">Limpar Filtros</a>
                      <?php endif; ?>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Property Grid -->
            <div class="col-xl-8 col-lg-7">
              <div class="box-title-listing">
                <div class="text-variant-1"><?= $totalImoveis ?> imóvel(is) encontrado(s)</div>
              </div>

              <?php if(empty($imoveis)): ?>
              <div class="alert alert-info text-center mt-20">
                <h5>Nenhum imóvel encontrado</h5>
                <p class="text-variant-1">Tente ajustar os filtros de busca.</p>
              </div>
              <?php else: ?>
              <div class="row">
                <?php foreach($imoveis as $imovel): ?>
                <div class="col-xl-6 col-md-6">
                  <div class="homelengo-box">
                    <div class="archive-top">
                      <a href="imovel.php?id=<?= $imovel['id'] ?>" class="images-group">
                        <div class="images-style">
                          <img class="lazyload" data-src="<?= !empty($imovel['imagem_principal']) ? 'uploads/imoveis/'.$imovel['imagem_principal'] : 'images/home/house-1.jpg' ?>" src="<?= !empty($imovel['imagem_principal']) ? 'uploads/imoveis/'.$imovel['imagem_principal'] : 'images/home/house-1.jpg' ?>" alt="<?= htmlspecialchars($imovel['titulo']) ?>">
                        </div>
                        <div class="top">
                          <ul class="d-flex gap-6">
                            <?php if(!empty($imovel['destaque']) && $imovel['destaque']): ?>
                            <li class="flag-tag primary">Destaque</li>
                            <?php endif; ?>
                            <li class="flag-tag style-1"><?= $imovel['tipo_negocio'] == 'venda' ? 'Venda' : 'Aluguel' ?></li>
                          </ul>
                        </div>
                        <div class="bottom">
                          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 7C10 7.53043 9.78929 8.03914 9.41421 8.41421C9.03914 8.78929 8.53043 9 8 9C7.46957 9 6.96086 8.78929 6.58579 8.41421C6.21071 8.03914 6 7.53043 6 7C6 6.46957 6.21071 5.96086 6.58579 5.58579C6.96086 5.21071 7.46957 5 8 5C8.53043 5 9.03914 5.21071 9.41421 5.58579C9.78929 5.96086 10 6.46957 10 7Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M13 7C13 11.7613 8 14.5 8 14.5C8 14.5 3 11.7613 3 7C3 5.67392 3.52678 4.40215 4.46447 3.46447C5.40215 2.52678 6.67392 2 8 2C9.32608 2 10.5979 2.52678 11.5355 3.46447C12.4732 4.40215 13 5.67392 13 7Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          </svg> <?= htmlspecialchars($imovel['bairro'] . ', ' . $imovel['cidade']) ?>
                        </div>
                      </a>
                    </div>
                    <div class="archive-bottom">
                      <div class="content-top">
                        <h6 class="text-capitalize">
                          <a href="imovel.php?id=<?= $imovel['id'] ?>" class="link"><?= htmlspecialchars($imovel['titulo']) ?></a>
                        </h6>
                        <ul class="meta-list">
                          <li class="item">
                            <i class="icon icon-bed"></i>
                            <span class="text-variant-1">Quartos:</span>
                            <span class="fw-6"><?= $imovel['quartos'] ?></span>
                          </li>
                          <li class="item">
                            <i class="icon icon-bath"></i>
                            <span class="text-variant-1">Banheiros:</span>
                            <span class="fw-6"><?= $imovel['banheiros'] ?></span>
                          </li>
                          <li class="item">
                            <i class="icon icon-sqft"></i>
                            <span class="text-variant-1">Área:</span>
                            <span class="fw-6"><?= $imovel['area_total'] ?>m²</span>
                          </li>
                        </ul>
                      </div>
                      <div class="content-bottom">
                        <div class="d-flex gap-8 align-items-center">
                          <span class="text-variant-1"><?= htmlspecialchars($imovel['categoria_nome'] ?? 'Imóvel') ?></span>
                        </div>
                        <h6 class="price">R$ <?= number_format($imovel['preco'], 2, ',', '.') ?><?= $imovel['tipo_negocio'] == 'aluguel' ? '/mês' : '' ?></h6>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>

              <!-- Pagination -->
              <?php if($totalPages > 1): ?>
              <ul class="flat-pagination">
                <?php if($page > 1): ?>
                <li>
                  <a href="?<?= buildQueryString(array_merge($filters, ['page' => $page - 1])) ?>" class="link"><i class="icon icon-arrow-left-add"></i></a>
                </li>
                <?php endif; ?>
                
                <?php 
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                
                for($i = $startPage; $i <= $endPage; $i++): ?>
                <li>
                  <a href="?<?= buildQueryString(array_merge($filters, ['page' => $i])) ?>" class="link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                
                <?php if($page < $totalPages): ?>
                <li>
                  <a href="?<?= buildQueryString(array_merge($filters, ['page' => $page + 1])) ?>" class="link"><i class="icon icon-arrow-right-add"></i></a>
                </li>
                <?php endif; ?>
              </ul>
              <?php endif; ?>
              <?php endif; ?>
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
  <script src="js/jquery-ui.min.js"></script>
  <script src="js/wow.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
