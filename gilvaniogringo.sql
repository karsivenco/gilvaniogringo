-- -----------------------------------------------------
-- Banco de dados: gilvaniogringo
-- -----------------------------------------------------

-- Tabela usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir usuários
INSERT INTO `usuarios` (`nome`, `usuario`, `senha`, `perfil`) VALUES
('Grazielle Albuquerque', 'grazielle.albuquerque', 'Gringo1975', 'Jornalismo'),
('Gabriel Amaral', 'gabriel.amaral', 'Gringo1975', 'Jornalismo'),
('Karina Maia', 'karina.maia', 'Gringo@20', 'Desenvolvedora Social Mídia'),
('Gilvani Gringo', 'gilvaniogringo', 'Gringo10000', 'Vereador'),
('Anne Mattos', 'anne.mattos', 'Gringo1975', 'Comunicação'),
('Tati Garcia', 'tati.garcia', 'Gringo1975', 'Comunicação'),
('Cristiano Santos', 'cristiano.santos', 'Gringo1975', 'Mídias Sociais'),
('Vitor Mello', 'vitor.mello', 'Gringo1975', 'Comunicação'),
('Lucas Bitencourt', 'lucas.bitencourt', 'Gringo1975', 'Comunicação');

-- Tabela postagens
CREATE TABLE IF NOT EXISTS `postagens` (
  `idPrimária` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Gilvani',
  `status` enum('publicado','rascunho') COLLATE utf8mb4_unicode_ci DEFAULT 'rascunho',
  `data_publicacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idPrimária`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir posts de teste
INSERT INTO `postagens` (`titulo`, `conteudo`, `autor`, `status`) VALUES
('Primeiro post', 'Conteúdo do primeiro post', 'Gilvani', 'publicado'),
('Segundo post', 'Conteúdo do segundo post', 'Gilvani', 'rascunho');

-- Tabela contatos
CREATE TABLE IF NOT EXISTS `contatos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

