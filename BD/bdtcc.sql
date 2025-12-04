-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/12/2025 às 19:49
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bdtcc`
--
CREATE DATABASE IF NOT EXISTS `bdtcc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bdtcc`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `codigo` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `nome` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`codigo`, `status`, `nome`) VALUES
(1, 1, 'Artefatos de Cimento'),
(2, 1, 'Hidráulico'),
(4, 1, 'Ferramentas'),
(5, 1, 'Ferro '),
(6, 1, 'Aço'),
(7, 1, 'Pregos '),
(10, 1, 'Adesivos '),
(11, 1, 'Tubos e Conexões'),
(12, 1, 'Selantes');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `codigo` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `cpf_cnpj` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `logradouro` varchar(45) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `cep` varchar(45) NOT NULL,
  `bairro` varchar(60) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`codigo`, `status`, `cpf_cnpj`, `nome`, `logradouro`, `endereco`, `cep`, `bairro`, `cidade`, `uf`, `telefone`, `email`) VALUES
(1, 0, '132456789', 'judith', 'Av', 'Brasil n 889', '87400-000', 'centro', 'Cruzeiro do Oeste', 'PR', '4499598888', 'juh@gmail.com'),
(2, 1, '987.332.354-22', 'Wanderlei Cordeiro de Jesus', 'Rua', 'Getúlio Vargas', '87400-000', 'Centro', 'Cruzeiro do Oeste', 'PR', '(44) 89899-8988', 'wando_72@gmail.com'),
(3, 1, '121.859.689-98', 'Beattriz Gobbi', 'Rua', 'Nova Peabiru', '87400-000', 'Jardim Cruzeiro', 'Cruzeiro do Oeste', 'PR', '(44) 99748-1868', 'gobbi@gmail.com'),
(4, 1, '784.568.745-63', 'Leandro Coelho', 'Rua dos Papagaios', 'Rua dos Papagaios', '87075-260', 'Parque Hortência', 'Maringá', 'PR', '(44) 99982-6168', 'sargento@gmail.com'),
(5, 1, '132.273.859-96', 'João Victor Rodrigues Daniel', 'Rua', 'Rua das Rosas 150', '87400-000', 'JD. Das Flores', 'Cruzeiro do Oeste', 'PR', '(44) 99452-3117', 'joao@gmail.com'),
(6, 1, '092.828.569-33', 'João Pedro da Silva e Silva', 'Rua Armando Luiz Bretas', 'Rua Armando Luiz Bretas', '87508-180', 'Jardim San Fernando', 'Umuarama', 'PR', '(44) 98462-2203', 'jp@gmail.com'),
(7, 1, '037.416.909-88', 'Everton Baro', 'Rua João Carneiro Filgueiras', 'Rua João Carneiro Filgueiras', '87047-670', 'Jardim Paulista IV', 'Maringá', 'PR', '(44) 97777-4444', 'everton@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `codigo` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `cpf` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `logradouro` varchar(45) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cep` varchar(20) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `bairro` varchar(60) NOT NULL,
  `cidade` varchar(45) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `tipoDeAcesso` tinyint(1) NOT NULL,
  `dtAdmissao` date NOT NULL,
  `dtDemissao` date DEFAULT NULL,
  `senha` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`codigo`, `status`, `cpf`, `nome`, `endereco`, `logradouro`, `telefone`, `cep`, `uf`, `bairro`, `cidade`, `email`, `tipoDeAcesso`, `dtAdmissao`, `dtDemissao`, `senha`) VALUES
(1, 1, '124.386.099-58', 'Anna Carolina Oliveira da Silva', 'Ortigueira n56', 'Rua', '(44) 99981-1067', '87400-000', 'PR', 'Jardim da Luz', 'Cruzeiro do Oeste', 'anna@gmail.com', 0, '2025-09-09', NULL, '1234'),
(2, 1, '050.789.624-39', 'Penélope Agnes', 'Brasil n° 89', 'Avenida', '(44) 99918-0176', '87540-000', 'PR', 'jardim do gregos', 'Pérola', 'agnes_pe@gmail.com', 0, '2025-11-06', '2025-11-21', '963852'),
(3, 1, '150.715.699-57', 'Markis', 'Avenida Foz do Iguaçu 397', 'rua', '(44) 99839-4466', '87400-000', 'PR', 'Sul Brasileiro 1', 'Cruzeiro do Oeste', 'markis@gmail.com', 0, '2025-11-20', NULL, '1234567'),
(4, 0, '070.956.982-45', ' Dirce Gomes', 'Rua Coronel Armando Mendes', 'Rua Coronel Armando Mendes', '(44) 99999-9999', '49048-060', 'SE', 'Luzia', 'Aracaju', 'dirce321@hotmail.com', 1, '2025-11-21', NULL, '741258');

-- --------------------------------------------------------

--
-- Estrutura para tabela `marca`
--

CREATE TABLE `marca` (
  `codigo` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `nome` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `marca`
--

INSERT INTO `marca` (`codigo`, `status`, `nome`) VALUES
(1, 1, 'Tigre'),
(2, 1, 'Makita'),
(3, 1, 'Votoran'),
(4, 1, 'Gerdau'),
(5, 1, 'Blukit'),
(6, 1, 'Astra'),
(7, 1, '3M');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `codigo` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `nome` varchar(100) NOT NULL,
  `precoUnitarioDaCompra` double(10,2) NOT NULL,
  `precoUnitarioDaVenda` double(10,2) NOT NULL,
  `quantEstoque` int(11) NOT NULL,
  `ncm` varchar(10) NOT NULL,
  `cfop` varchar(10) NOT NULL,
  `idMarca` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  `unidMedida` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`codigo`, `status`, `nome`, `precoUnitarioDaCompra`, `precoUnitarioDaVenda`, `quantEstoque`, `ncm`, `cfop`, `idMarca`, `idCategoria`, `unidMedida`) VALUES
(1, 1, 'Vergalhão 8mm CA50', 24.00, 35.00, 31, '72142000', '5102', 4, 5, 'UNID'),
(2, 1, 'ESMERILHADEIRA ANGULAR 115MM (4 1/2 POL) 840W 220V 9557HNG', 280.00, 450.00, 2, '84659310', '5102', 2, 4, ''),
(5, 1, 'Cimento', 20.00, 37.00, 20, '2131.31.25', '5102', 3, 1, 'UNID'),
(6, 1, 'Tubo PVC Esgoto 100mm', 5500.00, 7500.00, 48, '2312.13.21', '5102', 1, 11, 'UNID'),
(7, 1, 'Ligação Flexível Aço Inox 3 em 1', 8.00, 15.00, 25, '2312.13.21', '5102', 5, 2, 'UNID'),
(8, 1, 'Fita Isolante 10m', 98.00, 250.00, 80, '2312.13.21', '5102', 7, 10, 'UNID');

-- --------------------------------------------------------

--
-- Estrutura para tabela `recebimentos`
--

CREATE TABLE `recebimentos` (
  `codigo` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `formaDeRecebimento` varchar(45) NOT NULL,
  `valorRecebido` double NOT NULL,
  `valorReceber` double NOT NULL,
  `dataVencimento` date NOT NULL,
  `dataRecebimento` date NOT NULL,
  `idVenda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `recebimentos`
--

INSERT INTO `recebimentos` (`codigo`, `status`, `formaDeRecebimento`, `valorRecebido`, `valorReceber`, `dataVencimento`, `dataRecebimento`, `idVenda`) VALUES
(1, 1, 'Dinheiro', 720, 0, '2025-11-29', '2025-11-29', 1),
(2, 1, 'A Prazo', 400, 50, '2025-11-29', '2025-11-29', 2),
(3, 1, 'A Prazo', 550, -180, '2025-12-30', '2025-11-30', 3),
(4, 1, 'A Prazo', 440, 10, '2026-01-01', '2025-12-02', 4),
(5, 1, 'A Prazo', 310, -10, '2026-01-01', '2025-12-01', 5),
(6, 1, 'A Prazo', 130, -30, '2026-01-01', '0000-00-00', 6),
(7, 1, 'A Prazo', 109, -1.5, '2026-01-01', '0000-00-00', 7),
(8, 1, 'A Prazo', 600, 0, '2026-01-01', '2025-12-02', 8),
(9, 1, 'A Prazo', 350, 0, '2026-01-02', '2025-12-04', 9),
(10, 1, 'Dinheiro', 213, 0, '2026-01-02', '0000-00-00', 10),
(11, 1, 'Cartão de Débito', 177.5, 0, '2026-01-02', '0000-00-00', 11),
(12, 1, 'A Prazo', 625, 0, '2026-01-03', '2025-12-04', 12),
(13, 1, 'Dinheiro', 370, 0, '2026-01-03', '0000-00-00', 13),
(14, 1, 'Boleto', 17765, 0, '2026-01-03', '2025-12-04', 14);

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendahasproduto`
--

CREATE TABLE `vendahasproduto` (
  `id` int(11) NOT NULL,
  `FkNumeroDaVenda` int(11) NOT NULL,
  `FkCodigoProduto` int(11) NOT NULL,
  `quantidade` int(4) NOT NULL,
  `precoUnitDaVenda` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendahasproduto`
--

INSERT INTO `vendahasproduto` (`id`, `FkNumeroDaVenda`, `FkCodigoProduto`, `quantidade`, `precoUnitDaVenda`) VALUES
(1, 1, 1, 10, 35.00),
(2, 1, 5, 10, 37.00),
(3, 2, 2, 1, 450.00),
(4, 3, 5, 10, 37.00),
(5, 4, 2, 1, 450.00),
(6, 5, 1, 10, 35.00),
(7, 6, 7, 4, 35.50),
(8, 7, 7, 5, 35.50),
(9, 8, 8, 20, 30.00),
(10, 9, 1, 10, 35.00),
(11, 10, 7, 6, 35.50),
(12, 11, 7, 5, 35.50),
(13, 12, 2, 1, 450.00),
(14, 12, 1, 5, 35.00),
(15, 13, 5, 10, 37.00),
(16, 14, 5, 70, 37.00),
(17, 14, 6, 2, 7500.00),
(18, 14, 1, 5, 35.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `numeroDaVenda` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `data/hora` datetime NOT NULL DEFAULT current_timestamp(),
  `valorTotal` double NOT NULL,
  `formaDeRecebimento` varchar(45) NOT NULL,
  `observacoes` varchar(100) DEFAULT NULL,
  `dataHoraEntrega` datetime DEFAULT NULL,
  `enderecoEntrega` varchar(100) DEFAULT NULL,
  `statusEntrega` tinyint(1) NOT NULL,
  `idFuncionario` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendas`
--

INSERT INTO `vendas` (`numeroDaVenda`, `status`, `data/hora`, `valorTotal`, `formaDeRecebimento`, `observacoes`, `dataHoraEntrega`, `enderecoEntrega`, `statusEntrega`, `idFuncionario`, `idCliente`) VALUES
(1, 0, '2025-11-29 12:13:23', 720, 'Dinheiro', 'Entregar depois das 14:00H', '0000-00-00 00:00:00', '', 0, 1, 4),
(2, 1, '2025-11-29 12:23:42', 450, 'A Prazo', '', '0000-00-00 00:00:00', '', 0, 1, 2),
(3, 1, '2025-11-29 21:02:37', 370, 'A Prazo', '', '0000-00-00 00:00:00', '', 0, 1, 4),
(4, 1, '2025-12-02 08:41:11', 450, 'A Prazo', '', '0000-00-00 00:00:00', '', 0, 1, 4),
(5, 1, '2025-12-02 08:47:18', 350, 'A Prazo', '', '0000-00-00 00:00:00', '', 0, 1, 2),
(6, 1, '2025-12-02 09:06:53', 142, 'A Prazo', '', '0000-00-00 00:00:00', '', 0, 1, 3),
(7, 1, '2025-12-02 09:08:32', 177.5, 'A Prazo', '', '0000-00-00 00:00:00', '', 0, 1, 3),
(8, 1, '2025-12-02 09:53:51', 600, 'A Prazo', '', '0000-00-00 00:00:00', '', 0, 1, 2),
(9, 1, '2025-12-03 09:25:02', 350, 'A Prazo', '', '2025-12-03 12:00:00', 'Avenida Foz do Iguaçu 397', 0, 1, 3),
(10, 1, '2025-12-03 23:24:44', 213, 'Dinheiro', '', '2025-12-04 08:00:00', 'Avenida Foz do Iguaçu 397', 0, 1, 3),
(11, 0, '2025-12-03 23:27:49', 177.5, 'Cartão de Débito', '', '2025-12-04 09:00:00', 'Avenida Foz do Iguaçu 397', 1, 1, 3),
(12, 1, '2025-12-04 09:28:25', 625, 'A Prazo', '', '2025-12-04 11:00:00', 'Rua João Carneiro Filgueiras', 0, 3, 7),
(13, 1, '2025-12-04 09:45:44', 370, 'Dinheiro', '', '2025-12-04 11:00:00', 'Rua João Carneiro Filgueiras', 1, 3, 7),
(14, 1, '2025-12-04 12:47:06', 17765, 'Boleto', 'cuidado com o cachorro', '2025-12-25 10:00:00', 'Rua João Carneiro Filgueiras', 0, 3, 7);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `FKidMarca` (`idMarca`),
  ADD KEY `FKidCategoria` (`idCategoria`);

--
-- Índices de tabela `recebimentos`
--
ALTER TABLE `recebimentos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `FKidVenda` (`idVenda`);

--
-- Índices de tabela `vendahasproduto`
--
ALTER TABLE `vendahasproduto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FkNumerodaVenda` (`FkNumeroDaVenda`),
  ADD KEY `FkCodigoProduto` (`FkCodigoProduto`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`numeroDaVenda`),
  ADD KEY `FKidFuncionario` (`idFuncionario`),
  ADD KEY `FKidCliente` (`idCliente`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `marca`
--
ALTER TABLE `marca`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `recebimentos`
--
ALTER TABLE `recebimentos`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `vendahasproduto`
--
ALTER TABLE `vendahasproduto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `numeroDaVenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `FKidCategoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`codigo`),
  ADD CONSTRAINT `FKidMarca` FOREIGN KEY (`idMarca`) REFERENCES `marca` (`codigo`);

--
-- Restrições para tabelas `recebimentos`
--
ALTER TABLE `recebimentos`
  ADD CONSTRAINT `FKidVenda` FOREIGN KEY (`idVenda`) REFERENCES `vendas` (`numeroDaVenda`);

--
-- Restrições para tabelas `vendahasproduto`
--
ALTER TABLE `vendahasproduto`
  ADD CONSTRAINT `FkCodigoProduto` FOREIGN KEY (`FkCodigoProduto`) REFERENCES `produto` (`codigo`),
  ADD CONSTRAINT `FkNumerodaVenda` FOREIGN KEY (`FkNumeroDaVenda`) REFERENCES `vendas` (`numeroDaVenda`);

--
-- Restrições para tabelas `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `FKidCliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`codigo`),
  ADD CONSTRAINT `FKidFuncionario` FOREIGN KEY (`idFuncionario`) REFERENCES `funcionario` (`codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
