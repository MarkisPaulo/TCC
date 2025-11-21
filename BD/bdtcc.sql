-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/11/2025 às 03:07
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
(7, 1, 'Pregos e Fixadores'),
(8, 0, 'testeAllllll');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `codigo` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `cpf` varchar(20) NOT NULL,
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

INSERT INTO `cliente` (`codigo`, `status`, `cpf`, `nome`, `logradouro`, `endereco`, `cep`, `bairro`, `cidade`, `uf`, `telefone`, `email`) VALUES
(1, 1, '132456789', 'judite', 'sdafdsa', 'adfafads', '87400-000', 'asdfadsfa', 'Cruzeiro do Oeste', 'PR', '44444444444', 'asddf@gmail.com'),
(2, 1, '987.332.354-22', 'Wanderlei Cordeiro de Jesus', 'Rua', 'Getúlio Vargas', '87400-000', 'Centro', 'Cruzeiro do Oeste', 'PR', '(44) 89899-8988', 'wando_72@gmail.com'),
(3, 0, '11111111111', 'teste2', 'rua', 'Avenida Foz do Iguaçu 397', '87400-000', 'Sul Brasileiro 1', 'Cruzeiro do Oeste', 'PR', '(44) 99839-4465', 'markispaulo.atanasio3@gmail.com');

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
(1, 1, '132456789', 'user', 'adfafads', 'sdafdsa', '44444444444', '87400-000', 'PR', 'asdfadsfa', 'Cruzeiro do Oeste', 'user@gmail.com', 1, '2025-09-09', NULL, '1234'),
(2, 1, '050.789.624-39', 'Penélope Agnes', 'Brasil n° 89', 'Avenida', '(44) 99918-0176', '87540-000', 'PR', 'jardim do gregos', 'Pérola', 'agnes_pe@gmail.com', 0, '2025-11-06', NULL, '963852'),
(3, 1, '150.715.699-57', 'teste', 'Avenida Foz do Iguaçu 397', 'rua', '(44) 99839-4465', '87400-000', 'PR', 'Sul Brasileiro 1', 'Cruzeiro do Oeste', 'markispaulo.atanasio3@gmail.com', 1, '2025-11-20', NULL, '1234444');

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
(5, 0, 'Blukit1');

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
(1, 1, 'Vergalhão 8mm CA50', 24.00, 35.00, 50, '72142000', '5102', 4, 5, ''),
(2, 1, 'ESMERILHADEIRA ANGULAR 115MM (4 1/2 POL) 840W 220V 9557HNG', 280.00, 450.00, 5, '84659310', '5102', 2, 4, ''),
(3, 1, 'teste', 10.00, 15.00, 50, '000000001', '0000000001', 1, 6, 'JOGO');

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

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `numeroDaVenda` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `data/hora` datetime NOT NULL,
  `valorTotal` double NOT NULL,
  `formaDeRecebimento` varchar(45) NOT NULL,
  `observacoes` varchar(100) NOT NULL,
  `data/horaEntrega` datetime NOT NULL,
  `enderecoEntrega` varchar(100) NOT NULL,
  `statusEntrega` tinyint(1) NOT NULL,
  `idFuncionario` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `marca`
--
ALTER TABLE `marca`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `recebimentos`
--
ALTER TABLE `recebimentos`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `numeroDaVenda` int(11) NOT NULL AUTO_INCREMENT;

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
-- Restrições para tabelas `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `FKidCliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`codigo`),
  ADD CONSTRAINT `FKidFuncionario` FOREIGN KEY (`idFuncionario`) REFERENCES `funcionario` (`codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
