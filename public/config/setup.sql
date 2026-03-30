-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS imobiliaria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE imobiliaria;

-- Tabela de administradores
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de categorias/tipos de imóveis
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icone VARCHAR(50) DEFAULT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de imóveis
CREATE TABLE IF NOT EXISTS imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    descricao TEXT,
    descricao_curta VARCHAR(500),
    categoria_id INT,
    tipo_negocio ENUM('venda', 'aluguel') DEFAULT 'venda',
    preco DECIMAL(15, 2) NOT NULL,
    preco_condominio DECIMAL(15, 2) DEFAULT NULL,
    preco_iptu DECIMAL(15, 2) DEFAULT NULL,
    
    -- Características
    quartos INT DEFAULT 0,
    banheiros INT DEFAULT 0,
    suites INT DEFAULT 0,
    vagas_garagem INT DEFAULT 0,
    area_total DECIMAL(10, 2) DEFAULT NULL,
    area_construida DECIMAL(10, 2) DEFAULT NULL,
    ano_construcao INT DEFAULT NULL,
    
    -- Localização
    endereco VARCHAR(255),
    numero VARCHAR(20),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    cep VARCHAR(10),
    latitude DECIMAL(10, 8) DEFAULT NULL,
    longitude DECIMAL(11, 8) DEFAULT NULL,
    
    -- Imagens
    imagem_principal VARCHAR(255),
    
    -- Status
    destaque TINYINT(1) DEFAULT 0,
    status ENUM('disponivel', 'vendido', 'alugado', 'reservado') DEFAULT 'disponivel',
    ativo TINYINT(1) DEFAULT 1,
    visualizacoes INT DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Tabela de imagens dos imóveis (galeria)
CREATE TABLE IF NOT EXISTS imoveis_imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    ordem INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE
);

-- Tabela de características/amenidades
CREATE TABLE IF NOT EXISTS amenidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    icone VARCHAR(50) DEFAULT NULL,
    ativo TINYINT(1) DEFAULT 1
);

-- Tabela de relação imóveis-amenidades
CREATE TABLE IF NOT EXISTS imoveis_amenidades (
    imovel_id INT NOT NULL,
    amenidade_id INT NOT NULL,
    PRIMARY KEY (imovel_id, amenidade_id),
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE,
    FOREIGN KEY (amenidade_id) REFERENCES amenidades(id) ON DELETE CASCADE
);

-- Tabela de contatos/leads
CREATE TABLE IF NOT EXISTS contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT DEFAULT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    telefone VARCHAR(20),
    mensagem TEXT,
    lido TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE SET NULL
);

-- Tabela de configurações do site
CREATE TABLE IF NOT EXISTS configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT,
    tipo VARCHAR(50) DEFAULT 'text'
);

-- =====================================================
-- INSERÇÃO DE DADOS INICIAIS
-- =====================================================

-- Inserir admin padrão (senha: admin123)
INSERT INTO admins (nome, email, senha) VALUES 
('Administrador', 'admin@imobiliaria.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Inserir categorias
INSERT INTO categorias (nome, slug, icone) VALUES 
('Casa', 'casa', 'icon-house'),
('Apartamento', 'apartamento', 'icon-building'),
('Terreno', 'terreno', 'icon-land'),
('Sala Comercial', 'sala-comercial', 'icon-office'),
('Galpão', 'galpao', 'icon-warehouse'),
('Chácara', 'chacara', 'icon-farm'),
('Cobertura', 'cobertura', 'icon-penthouse'),
('Studio', 'studio', 'icon-studio');

-- Inserir amenidades
INSERT INTO amenidades (nome, icone) VALUES 
('Piscina', 'icon-pool'),
('Churrasqueira', 'icon-bbq'),
('Academia', 'icon-gym'),
('Salão de Festas', 'icon-party'),
('Playground', 'icon-playground'),
('Segurança 24h', 'icon-security'),
('Elevador', 'icon-elevator'),
('Ar Condicionado', 'icon-ac'),
('Aquecimento', 'icon-heating'),
('Varanda', 'icon-balcony'),
('Jardim', 'icon-garden'),
('Garagem Coberta', 'icon-garage'),
('Lavanderia', 'icon-laundry'),
('Closet', 'icon-closet'),
('Despensa', 'icon-pantry'),
('Portaria', 'icon-concierge'),
('Quadra Esportiva', 'icon-sports'),
('Sauna', 'icon-sauna'),
('Spa', 'icon-spa'),
('Home Office', 'icon-office');

-- Inserir configurações do site
INSERT INTO configuracoes (chave, valor, tipo) VALUES 
('site_nome', 'FABIOLEAO Imóveis', 'text'),
('site_telefone', '(11) 99999-9999', 'text'),
('site_whatsapp', '5511999999999', 'text'),
('site_email', 'contato@fabioleao.com.br', 'text'),
('site_endereco', 'Av. Principal, 1000 - Centro, São Paulo - SP', 'text'),
('site_instagram', 'https://instagram.com/fabioleaoimoveis', 'text'),
('site_facebook', 'https://facebook.com/fabioleaoimoveis', 'text');

-- =====================================================
-- INSERÇÃO DE IMÓVEIS DE TESTE
-- =====================================================

INSERT INTO imoveis (titulo, slug, descricao, descricao_curta, categoria_id, tipo_negocio, preco, quartos, banheiros, suites, vagas_garagem, area_total, area_construida, ano_construcao, endereco, bairro, cidade, estado, cep, imagem_principal, destaque, status) VALUES 

('Casa Moderna em Condomínio Fechado', 'casa-moderna-condominio-fechado', 
'Linda casa moderna em condomínio fechado de alto padrão. Projeto arquitetônico contemporâneo com amplas áreas de convivência, integração total entre sala, cozinha e área gourmet. Acabamentos de primeira linha, porcelanato em todos os ambientes, iluminação planejada e automação residencial. Jardim com paisagismo e piscina aquecida. Localização privilegiada com fácil acesso às principais vias da cidade.',
'Casa moderna em condomínio fechado com 4 suítes, piscina e área gourmet completa.',
1, 'venda', 1850000.00, 4, 5, 4, 4, 500.00, 380.00, 2022, 'Rua das Palmeiras, 150', 'Jardim Europa', 'São Paulo', 'SP', '01401-000', 'house-1.jpg', 1, 'disponivel'),

('Apartamento Luxo Vista Mar', 'apartamento-luxo-vista-mar',
'Apartamento de alto padrão com vista panorâmica para o mar. Ampla sala de estar com varanda gourmet, cozinha americana totalmente equipada, suíte master com closet e banheira de hidromassagem. Acabamentos em mármore e madeira nobre. Condomínio com infraestrutura completa incluindo piscina, academia e salão de festas.',
'Apartamento de luxo com vista mar, 3 suítes e varanda gourmet.',
2, 'venda', 2500000.00, 3, 4, 3, 3, 180.00, 180.00, 2021, 'Av. Beira Mar, 500', 'Praia Grande', 'Santos', 'SP', '11030-000', 'apartment-1.jpg', 1, 'disponivel'),

('Cobertura Duplex Centro', 'cobertura-duplex-centro',
'Espetacular cobertura duplex no coração da cidade. Piso inferior com living integrado, lavabo, cozinha gourmet e 2 suítes. Piso superior com suíte master, escritório e terraço com piscina e churrasqueira. Vista de 360 graus para a cidade. Acabamentos de altíssimo padrão.',
'Cobertura duplex com piscina privativa e vista panorâmica.',
7, 'venda', 3200000.00, 3, 4, 3, 4, 350.00, 300.00, 2020, 'Rua Augusta, 2000', 'Consolação', 'São Paulo', 'SP', '01305-000', 'penthouse-1.jpg', 1, 'disponivel'),

('Casa Térrea Jardins', 'casa-terrea-jardins',
'Excelente casa térrea em bairro nobre. Ampla sala de estar e jantar, cozinha planejada, 3 quartos sendo 1 suíte, banheiro social, área de serviço e quintal com churrasqueira. Toda reformada com acabamentos modernos. Rua tranquila e arborizada.',
'Casa térrea reformada com 3 quartos e quintal com churrasqueira.',
1, 'venda', 890000.00, 3, 2, 1, 2, 250.00, 150.00, 2015, 'Rua dos Jardins, 789', 'Jardim Paulista', 'São Paulo', 'SP', '01401-000', 'house-2.jpg', 0, 'disponivel'),

('Apartamento Compacto Metrô', 'apartamento-compacto-metro',
'Apartamento moderno e funcional a 200m do metrô. Ideal para jovens profissionais ou investimento. Sala com cozinha americana, 1 quarto, banheiro e área de serviço. Condomínio com lavanderia, bicicletário e coworking.',
'Apartamento 1 quarto próximo ao metrô, perfeito para investimento.',
2, 'aluguel', 2500.00, 1, 1, 0, 0, 35.00, 35.00, 2023, 'Rua Vergueiro, 1500', 'Paraíso', 'São Paulo', 'SP', '04101-000', 'apartment-2.jpg', 0, 'disponivel'),

('Studio Mobiliado Pinheiros', 'studio-mobiliado-pinheiros',
'Studio totalmente mobiliado e decorado em região premium. Ambiente integrado com cama, sofá, mesa de trabalho, cozinha completa e banheiro. Prédio com rooftop, academia e lavanderia. A 5 min do metrô Faria Lima.',
'Studio mobiliado e equipado em localização privilegiada.',
8, 'aluguel', 3200.00, 1, 1, 0, 0, 28.00, 28.00, 2022, 'Rua dos Pinheiros, 800', 'Pinheiros', 'São Paulo', 'SP', '05422-000', 'studio-1.jpg', 1, 'disponivel'),

('Terreno Comercial Zona Norte', 'terreno-comercial-zona-norte',
'Excelente terreno plano em avenida de grande movimento. Ideal para construção de prédio comercial, galpão ou estacionamento. Documentação 100% regularizada. Ótima oportunidade de investimento.',
'Terreno comercial de 1.000m² em avenida movimentada.',
3, 'venda', 1500000.00, 0, 0, 0, 0, 1000.00, 0.00, NULL, 'Av. Inajar de Souza, 3000', 'Freguesia do Ó', 'São Paulo', 'SP', '02715-000', 'land-1.jpg', 0, 'disponivel'),

('Sala Comercial Centro Empresarial', 'sala-comercial-centro-empresarial',
'Sala comercial em centro empresarial de alto padrão. Amplo espaço com divisórias removíveis, ar condicionado central, piso elevado e cabeamento estruturado. Prédio com segurança 24h, estacionamento rotativo e praça de alimentação.',
'Sala comercial 80m² em prédio AAA com infraestrutura completa.',
4, 'aluguel', 5500.00, 0, 2, 0, 2, 80.00, 80.00, 2019, 'Av. Paulista, 1000', 'Bela Vista', 'São Paulo', 'SP', '01310-000', 'office-1.jpg', 0, 'disponivel'),

('Chácara com Lago e Pomar', 'chacara-lago-pomar',
'Linda chácara para lazer ou moradia. Casa principal com 4 quartos, casa de caseiro, churrasqueira coberta, piscina, campo de futebol, lago com peixes e extenso pomar. Terreno totalmente cercado com portão eletrônico. A 50km de São Paulo.',
'Chácara completa com lago, piscina e muito verde.',
6, 'venda', 980000.00, 4, 3, 1, 6, 5000.00, 250.00, 2010, 'Estrada do Sítio, km 12', 'Zona Rural', 'Cotia', 'SP', '06709-000', 'farm-1.jpg', 0, 'disponivel'),

('Galpão Industrial Guarulhos', 'galpao-industrial-guarulhos',
'Galpão industrial com excelente localização logística, próximo ao aeroporto e principais rodovias. Pé direito de 12m, piso de alta resistência, docas para carga e descarga, escritório administrativo e vestiários. Área de manobra para carretas.',
'Galpão 2.000m² com localização estratégica para logística.',
5, 'aluguel', 35000.00, 0, 4, 0, 10, 3000.00, 2000.00, 2018, 'Rod. Presidente Dutra, km 225', 'Cumbica', 'Guarulhos', 'SP', '07190-000', 'warehouse-1.jpg', 0, 'disponivel'),

('Apartamento Garden Alphaville', 'apartamento-garden-alphaville',
'Lindo apartamento garden com área privativa de 100m². Sala ampla com saída para o jardim, cozinha integrada, 2 suítes com armários planejados. Condomínio completo com segurança, lazer e áreas verdes. Próximo a shopping e escolas.',
'Apartamento garden com quintal privativo em Alphaville.',
2, 'venda', 750000.00, 2, 2, 2, 2, 160.00, 90.00, 2021, 'Alameda Grajaú, 300', 'Alphaville', 'Barueri', 'SP', '06454-000', 'apartment-3.jpg', 0, 'disponivel'),

('Casa Sobrado Moema', 'casa-sobrado-moema',
'Sobrado em rua residencial de Moema. Térreo com sala, lavabo, cozinha e área gourmet com churrasqueira. Piso superior com 3 suítes e escritório. Edícula com quarto e banheiro. Jardim frontal e 4 vagas de garagem.',
'Sobrado 3 suítes em Moema com área gourmet e edícula.',
1, 'venda', 2100000.00, 4, 5, 3, 4, 300.00, 280.00, 2017, 'Rua Canário, 450', 'Moema', 'São Paulo', 'SP', '04521-000', 'house-3.jpg', 1, 'disponivel');

-- Inserir algumas amenidades para os imóveis
INSERT INTO imoveis_amenidades (imovel_id, amenidade_id) VALUES 
(1, 1), (1, 2), (1, 6), (1, 8), (1, 10), (1, 11), (1, 12),
(2, 1), (2, 3), (2, 4), (2, 6), (2, 7), (2, 8), (2, 10),
(3, 1), (3, 2), (3, 3), (3, 6), (3, 7), (3, 8), (3, 18),
(4, 2), (4, 11), (4, 12),
(5, 3), (5, 7), (5, 13),
(6, 3), (6, 7), (6, 13), (6, 20),
(9, 1), (9, 2), (9, 11), (9, 17),
(11, 1), (11, 3), (11, 4), (11, 6), (11, 11),
(12, 2), (12, 8), (12, 10), (12, 12), (12, 14);
