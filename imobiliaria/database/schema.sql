-- =====================================================
-- FABIO LEAO IMOBILIARIA - SCHEMA DO BANCO DE DADOS
-- =====================================================

CREATE DATABASE IF NOT EXISTS fabio_leao_imobiliaria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fabio_leao_imobiliaria;

-- =====================================================
-- TABELA DE PAÍSES
-- =====================================================
CREATE TABLE IF NOT EXISTS paises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE ESTADOS
-- =====================================================
CREATE TABLE IF NOT EXISTS estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pais_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    sigla VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pais_id) REFERENCES paises(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE CIDADES
-- =====================================================
CREATE TABLE IF NOT EXISTS cidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE TIPOS DE IMÓVEIS
-- =====================================================
CREATE TABLE IF NOT EXISTS tipos_imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    icone VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE CARACTERÍSTICAS
-- =====================================================
CREATE TABLE IF NOT EXISTS caracteristicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    icone VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE IMÓVEIS
-- =====================================================
CREATE TABLE IF NOT EXISTS imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    tipo_id INT NOT NULL,
    finalidade ENUM('venda', 'aluguel', 'ambos') NOT NULL DEFAULT 'venda',
    preco_venda DECIMAL(15,2) DEFAULT NULL,
    preco_aluguel DECIMAL(15,2) DEFAULT NULL,
    area_total DECIMAL(10,2) DEFAULT NULL,
    area_construida DECIMAL(10,2) DEFAULT NULL,
    quartos INT DEFAULT 0,
    suites INT DEFAULT 0,
    banheiros INT DEFAULT 0,
    vagas_garagem INT DEFAULT 0,
    pais_id INT NOT NULL,
    estado_id INT NOT NULL,
    cidade_id INT NOT NULL,
    bairro VARCHAR(150) DEFAULT NULL,
    endereco VARCHAR(255) DEFAULT NULL,
    numero VARCHAR(20) DEFAULT NULL,
    cep VARCHAR(20) DEFAULT NULL,
    latitude DECIMAL(10, 8) DEFAULT NULL,
    longitude DECIMAL(11, 8) DEFAULT NULL,
    destaque TINYINT(1) DEFAULT 0,
    ativo TINYINT(1) DEFAULT 1,
    visualizacoes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_id) REFERENCES tipos_imoveis(id),
    FOREIGN KEY (pais_id) REFERENCES paises(id),
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    FOREIGN KEY (cidade_id) REFERENCES cidades(id)
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE IMAGENS DOS IMÓVEIS
-- =====================================================
CREATE TABLE IF NOT EXISTS imoveis_imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    titulo VARCHAR(150) DEFAULT NULL,
    ordem INT DEFAULT 0,
    principal TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE CARACTERÍSTICAS DO IMÓVEL (RELAÇÃO N:N)
-- =====================================================
CREATE TABLE IF NOT EXISTS imoveis_caracteristicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    caracteristica_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE,
    FOREIGN KEY (caracteristica_id) REFERENCES caracteristicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_imovel_caracteristica (imovel_id, caracteristica_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE USUÁRIOS (ADMIN)
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    nivel ENUM('admin', 'corretor', 'editor') DEFAULT 'corretor',
    ativo TINYINT(1) DEFAULT 1,
    ultimo_acesso TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE CONTATOS/LEADS
-- =====================================================
CREATE TABLE IF NOT EXISTS contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT DEFAULT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(30) DEFAULT NULL,
    mensagem TEXT,
    lido TINYINT(1) DEFAULT 0,
    respondido TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE FAVORITOS
-- =====================================================
CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    imovel_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE,
    UNIQUE KEY unique_session_imovel (session_id, imovel_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABELA DE CONFIGURAÇÕES DO SITE
-- =====================================================
CREATE TABLE IF NOT EXISTS configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- DADOS INICIAIS
-- =====================================================

-- Inserir usuário admin padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, nivel) VALUES 
('Administrador', 'admin@fabioleao.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Inserir tipos de imóveis
INSERT INTO tipos_imoveis (nome, icone) VALUES 
('Casa', 'home'),
('Apartamento', 'building'),
('Cobertura', 'crown'),
('Terreno', 'map'),
('Sala Comercial', 'briefcase'),
('Galpão', 'warehouse'),
('Chácara', 'tree'),
('Fazenda', 'tractor'),
('Flat', 'bed'),
('Studio', 'coffee');

-- Inserir características
INSERT INTO caracteristicas (nome, icone) VALUES 
('Piscina', 'pool'),
('Churrasqueira', 'fire'),
('Academia', 'dumbbell'),
('Playground', 'gamepad'),
('Salão de Festas', 'party'),
('Portaria 24h', 'shield'),
('Elevador', 'arrow-up'),
('Ar Condicionado', 'snowflake'),
('Aquecimento', 'sun'),
('Varanda', 'door-open'),
('Jardim', 'leaf'),
('Lareira', 'fireplace'),
('Closet', 'wardrobe'),
('Despensa', 'box'),
('Escritório', 'desktop'),
('Lavabo', 'sink'),
('Área de Serviço', 'washing-machine'),
('Quintal', 'fence'),
('Vista para o Mar', 'water'),
('Mobiliado', 'couch');

-- Inserir países
INSERT INTO paises (nome, codigo) VALUES 
('Brasil', 'BR'),
('Portugal', 'PT'),
('Estados Unidos', 'US'),
('Espanha', 'ES'),
('Itália', 'IT'),
('França', 'FR'),
('Argentina', 'AR'),
('Uruguai', 'UY'),
('Chile', 'CL'),
('México', 'MX');

-- Inserir estados do Brasil
INSERT INTO estados (pais_id, nome, sigla) VALUES 
(1, 'São Paulo', 'SP'),
(1, 'Rio de Janeiro', 'RJ'),
(1, 'Minas Gerais', 'MG'),
(1, 'Bahia', 'BA'),
(1, 'Paraná', 'PR'),
(1, 'Santa Catarina', 'SC'),
(1, 'Rio Grande do Sul', 'RS'),
(1, 'Pernambuco', 'PE'),
(1, 'Ceará', 'CE'),
(1, 'Goiás', 'GO');

-- Inserir cidades
INSERT INTO cidades (estado_id, nome) VALUES 
(1, 'São Paulo'),
(1, 'Campinas'),
(1, 'Santos'),
(1, 'Guarulhos'),
(2, 'Rio de Janeiro'),
(2, 'Niterói'),
(2, 'Búzios'),
(3, 'Belo Horizonte'),
(3, 'Uberlândia'),
(4, 'Salvador'),
(5, 'Curitiba'),
(6, 'Florianópolis'),
(6, 'Balneário Camboriú'),
(7, 'Porto Alegre'),
(7, 'Gramado');

-- Configurações iniciais
INSERT INTO configuracoes (chave, valor) VALUES 
('site_nome', 'Fabio Leão Imobiliária'),
('site_descricao', 'Encontre o imóvel dos seus sonhos'),
('site_email', 'contato@fabioleao.com'),
('site_telefone', '(11) 99999-9999'),
('site_whatsapp', '5511999999999'),
('site_endereco', 'Av. Paulista, 1000 - São Paulo/SP'),
('site_instagram', '@fabioleaoimobiliaria'),
('site_facebook', 'fabioleaoimobiliaria'),
('moeda_simbolo', 'R$'),
('moeda_codigo', 'BRL');
