-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versione server:              5.6.20 - MySQL Community Server (GPL)
-- S.O. server:                  Win32
-- HeidiSQL Versione:            8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dump della struttura di tabella samtstock.categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `ID_Categoria` int(11) NOT NULL AUTO_INCREMENT,
  `NomeC` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_Categoria`),
  UNIQUE KEY `NomeC` (`NomeC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dump dei dati della tabella samtstock.categorie: ~0 rows (circa)
/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;


-- Dump della struttura di tabella samtstock.prodotti
CREATE TABLE IF NOT EXISTS `prodotti` (
  `ID_Prodotto` int(11) NOT NULL AUTO_INCREMENT,
  `NomeP` varchar(50) NOT NULL,
  `Categoria` int(11) DEFAULT NULL,
  `Modello` varchar(50) DEFAULT '-',
  `Numero di serie` varchar(100) DEFAULT NULL,
  `Disponibile` tinyint(1) NOT NULL DEFAULT '0',
  `Portabile` tinyint(1) NOT NULL DEFAULT '0',
  `Icona` varchar(5000) NOT NULL DEFAULT 'noicon.png',
  `Aula` varchar(10) DEFAULT '-',
  `DateTime` datetime DEFAULT NULL,
  `Descrizione` longtext,
  `Riservato` int(11) DEFAULT NULL,
  `Comprato` int(11) DEFAULT NULL,
  `Responsabile` int(11) DEFAULT NULL,
  `Prezzo` int(11) DEFAULT '0',
  `Quantita` int(11) DEFAULT '1',
  `Limite` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_Prodotto`),
  UNIQUE KEY `Numero di serie` (`Numero di serie`),
  KEY `Categoria` (`Categoria`),
  KEY `Responsabile` (`Responsabile`),
  KEY `FK_Riservato` (`Riservato`),
  KEY `Comprato` (`Comprato`),
  CONSTRAINT `FK_Categoria` FOREIGN KEY (`Categoria`) REFERENCES `categorie` (`ID_Categoria`),
  CONSTRAINT `FK_Comprato` FOREIGN KEY (`Comprato`) REFERENCES `utenti` (`ID_Utente`),
  CONSTRAINT `FK_Responsabile` FOREIGN KEY (`Responsabile`) REFERENCES `utenti` (`ID_Utente`),
  CONSTRAINT `FK_Riservato` FOREIGN KEY (`Riservato`) REFERENCES `utenti` (`ID_Utente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella con tutte le informazione di un determinato oggetto nel magazzino.';

-- Dump dei dati della tabella samtstock.prodotti: ~0 rows (circa)
/*!40000 ALTER TABLE `prodotti` DISABLE KEYS */;
/*!40000 ALTER TABLE `prodotti` ENABLE KEYS */;


-- Dump della struttura di tabella samtstock.usato
CREATE TABLE IF NOT EXISTS `usato` (
  `ID_Data` int(11) NOT NULL,
  `ID_Prodotto` int(11) NOT NULL,
  `ID_Utente` int(11) NOT NULL,
  PRIMARY KEY (`ID_Data`,`ID_Prodotto`,`ID_Utente`),
  KEY `ID_Prodotto` (`ID_Prodotto`),
  KEY `ID_Utente` (`ID_Utente`),
  CONSTRAINT `FK_Prodotto` FOREIGN KEY (`ID_Prodotto`) REFERENCES `prodotti` (`ID_Prodotto`),
  CONSTRAINT `FK_Utente` FOREIGN KEY (`ID_Utente`) REFERENCES `utenti` (`ID_Utente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dump dei dati della tabella samtstock.usato: ~0 rows (circa)
/*!40000 ALTER TABLE `usato` DISABLE KEYS */;
/*!40000 ALTER TABLE `usato` ENABLE KEYS */;


-- Dump della struttura di tabella samtstock.utenti
CREATE TABLE IF NOT EXISTS `utenti` (
  `ID_Utente` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Username` varchar(101) NOT NULL,
  `Password` varchar(40) NOT NULL,
  `Power` int(11) NOT NULL DEFAULT '0',
  `EMail` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_Utente`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dump dei dati della tabella samtstock.utenti: ~3 rows (circa)
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` (`ID_Utente`, `Nome`, `Cognome`, `Username`, `Password`, `Power`, `EMail`) VALUES
	(1, 'Angelo', 'Sanker', 'angelo.sanker', 'b324516e348567a933c810e00444c2891b903f8d', 1, 'angelo.sanker@samtrevano.ch'),
	(2, 'Pablo', 'Rossi', 'pablo.rossi', '1f875bd624a0f089e134ac56521d9e8fd1a8630c', 0, 'angelo.sanker@samtrevano.ch'),
	(3, 'Francesco', 'Mussi', 'francesco.mussi', 'd5fb073d9ae18761ae0a3c2ce03555fcb7373c29', 2, 'angelo.sanker@samtrevano.ch');
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
