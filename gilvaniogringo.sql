-- -----------------------------------------------------
-- Banco de dados: gilvaniogringo
-- -----------------------------------------------------

-- Criar tabela postagens
CREATE TABLE IF NOT EXISTS `postagens` (
  `idPrimária` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('publicado','rascunho') COLLATE utf8mb4_unicode_ci DEFAULT 'rascunho',
  `data_publicacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idPrimária`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir dados de teste
INSERT INTO `postagens` (`titulo`, `conteudo`, `autor`, `status`) VALUES
('Primeiro post', 'Conteúdo do primeiro post', 'Gilvanio', 'publicado'),
('Segundo post', 'Conteúdo do segundo post', 'Gilvanio', 'rascunho');

-- Criar tabela usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir usuários de teste
INSERT INTO `usuarios` (`nome`, `email`, `senha`) VALUES
('Gilvanio Gringo', 'gilvaniogringo@gmail.com', '123456'),
('Karina Maia', 'karina@gmail.com', 'senha123');
