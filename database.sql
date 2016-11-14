-- phpMyAdmin SQL Dump
-- version 4.3.7
-- http://www.phpmyadmin.net
--
-- Host: mysql01-farm62.kinghost.net
-- Tempo de geração: 14/11/2016 às 15:24
-- Versão do servidor: 5.7.16-log
-- Versão do PHP: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `demolaysp02`
--
CREATE DATABASE IF NOT EXISTS `demolaysp02` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `demolaysp02`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `checkin`
--

DROP TABLE IF EXISTS `checkin`;
CREATE TABLE IF NOT EXISTS `checkin` (
  `id_checkin` int(10) NOT NULL,
  `id_inscrito` int(11) NOT NULL,
  `camiseta` int(2) DEFAULT NULL,
  `epoc` int(2) DEFAULT NULL,
  `ca9` int(2) DEFAULT NULL,
  `pgto_balcao` int(2) DEFAULT NULL,
  `menor` int(2) DEFAULT NULL,
  `checkin_start` datetime NOT NULL,
  `checkin_end` datetime NOT NULL,
  `observacao` text NOT NULL,
  `finalizado` int(2) NOT NULL,
  `user` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `inscritos`
--

DROP TABLE IF EXISTS `inscritos`;
CREATE TABLE IF NOT EXISTS `inscritos` (
  `id` int(11) NOT NULL,
  `cid` varchar(80) DEFAULT NULL,
  `nome` varchar(200) DEFAULT NULL,
  `dataNascimento` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `confirmada` int(1) DEFAULT '0',
  `formaPgto` int(1) DEFAULT NULL,
  `cb_data_venc` date DEFAULT NULL,
  `cb_link_2via` text,
  `cb_forma_pgto` tinyint(4) DEFAULT NULL,
  `cb_id` int(10) DEFAULT NULL,
  `cb_valor` decimal(6,2) DEFAULT NULL,
  `lote` int(11) DEFAULT NULL,
  `cb_liquidada` date DEFAULT NULL,
  `regular` varchar(5) DEFAULT NULL,
  `macom` varchar(5) DEFAULT NULL,
  `grau` varchar(20) DEFAULT NULL,
  `capitulo` varchar(150) DEFAULT NULL,
  `capituloNro` int(11) DEFAULT NULL,
  `cavaleiro` varchar(30) DEFAULT NULL,
  `convento` varchar(150) DEFAULT NULL,
  `conventoNro` int(11) DEFAULT NULL,
  `tamanhoCamiseta` varchar(3) DEFAULT NULL,
  `grauDesejado` varchar(10) DEFAULT NULL,
  `dataInscricao` datetime DEFAULT NULL,
  `dataUltimoAcesso` datetime DEFAULT NULL,
  `ipUltimoAcesso` varchar(20) DEFAULT NULL,
  `token` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=861 DEFAULT CHARSET=utf8;

--
-- Gatilhos `inscritos`
--
DROP TRIGGER IF EXISTS `upd_inscritos`;
DELIMITER $$
CREATE TRIGGER `upd_inscritos` BEFORE INSERT ON `inscritos`
 FOR EACH ROW BEGIN
   DECLARE next_id INT;
   SET next_id = (SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='inscritos');

      IF (NEW.cid IS NULL OR NEW.cid = '') THEN
            SET NEW.cid = CONCAT("EXT",next_id);
      ELSE
            SET NEW.cid = NEW.cid;
      END IF;
    END
$$
DELIMITER ;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `checkin`
--
ALTER TABLE `checkin`
  ADD PRIMARY KEY (`id_checkin`), ADD KEY `id_inscrito` (`id_inscrito`);

--
-- Índices de tabela `inscritos`
--
ALTER TABLE `inscritos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `checkin`
--
ALTER TABLE `checkin`
  MODIFY `id_checkin` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de tabela `inscritos`
--
ALTER TABLE `inscritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=861;
--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `checkin`
--
ALTER TABLE `checkin`
ADD CONSTRAINT `checkin_ibfk_1` FOREIGN KEY (`id_inscrito`) REFERENCES `inscritos` (`id`) ON DELETE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
