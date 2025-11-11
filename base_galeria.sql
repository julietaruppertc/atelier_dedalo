-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para galeria
CREATE DATABASE IF NOT EXISTS `galeria` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `galeria`;

-- Volcando estructura para tabla galeria.artista
CREATE TABLE IF NOT EXISTS `artista` (
  `ID_Artista` bigint(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `nacionalidad` varchar(50) DEFAULT NULL,
  `nacimiento` date DEFAULT NULL,
  `fallecimiento` date DEFAULT NULL,
  PRIMARY KEY (`ID_Artista`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.artista: ~20 rows (aproximadamente)
REPLACE INTO `artista` (`ID_Artista`, `nombre`, `apellido`, `nacionalidad`, `nacimiento`, `fallecimiento`) VALUES
	(1, 'Hilma', 'af Klint', 'Sueca', '1862-10-26', '1944-10-21'),
	(2, 'Olga', 'Rozanova', 'Rusa', '1886-06-22', '1918-11-07'),
	(3, 'Clara', 'Peeters', 'Flamenca', '1594-05-15', '1657-06-01'),
	(4, 'Cecilia', 'Vicuña', 'Chilena', '1948-07-22', '0000-00-00'),
	(5, 'Mariette', 'Lydis', 'Austriaca', '1887-08-24', '1970-04-26'),
	(6, 'Remedios', 'Varo', 'Española-Mexicana', '1908-12-16', '1963-10-08'),
	(7, 'Vincent', 'Van Gogh', 'Neerlandés', '1853-03-30', '1890-07-29'),
	(8, 'Claude', 'Monet', 'Francés', '1840-11-14', '1926-12-05'),
	(9, 'Frida', 'Kahlo', 'Mexicana', '1907-07-06', '1954-07-13'),
	(10, 'Diego', 'Rivera', 'Mexicano', '1886-12-08', '1957-11-24'),
	(11, 'Georgia', 'O\'Keeffe', 'Estadounidense', '1887-11-15', '1986-03-06'),
	(12, 'Sandro', 'Botticelli', 'Italiano', '1445-03-01', '1510-05-17'),
	(13, 'Michelangelo', 'Caravaggio', 'Italiano', '1571-09-29', '1610-07-18'),
	(14, 'Pablo', 'Picasso', 'Español', '1881-10-25', '1973-04-08'),
	(15, 'Paul', 'Cézanne', 'Francés', '1839-01-19', '1906-10-22'),
	(16, 'Diego', 'Velázquez', 'Español', '1599-06-06', '1660-08-06'),
	(18, 'Berthe', 'Morisot', 'Francesa', '1841-01-14', '1895-03-02'),
	(20, 'Katsushika', 'Hokusai', 'Japonés', '1760-10-31', '1849-05-10'),
	(21, 'Gustav', 'Klimt', 'Austriaco', '1862-07-14', '1918-02-06'),
	(22, ' Johannes', 'Vermeer', 'Neerlandés', '1632-10-31', '1675-12-15');

-- Volcando estructura para tabla galeria.condicion
CREATE TABLE IF NOT EXISTS `condicion` (
  `ID_Condicion` bigint(11) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Condicion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.condicion: ~3 rows (aproximadamente)
REPLACE INTO `condicion` (`ID_Condicion`, `descripcion`) VALUES
	(1, 'Pendiente'),
	(2, 'En curso'),
	(3, 'Finalizada');

-- Volcando estructura para función galeria.contar_ofertas_subasta
DELIMITER //
CREATE FUNCTION `contar_ofertas_subasta`(`p_ID_Subasta` INT
) RETURNS int(11)
BEGIN
    DECLARE total_ofertas INT;
    
    SELECT COUNT(*) INTO total_ofertas
    FROM oferta
    WHERE ID_Subasta = p_ID_Subasta;
    
    RETURN total_ofertas;
END//
DELIMITER ;

-- Volcando estructura para tabla galeria.detalle_obra
CREATE TABLE IF NOT EXISTS `detalle_obra` (
  `ID_Detalle` bigint(11) NOT NULL AUTO_INCREMENT,
  `ID_Material` bigint(11) DEFAULT NULL,
  `ID_Tecnica` bigint(11) DEFAULT NULL,
  `detalles` text DEFAULT NULL,
  `alto` float DEFAULT NULL,
  `ancho` float DEFAULT NULL,
  `profundidad` float DEFAULT NULL,
  PRIMARY KEY (`ID_Detalle`),
  KEY `ID_Material` (`ID_Material`),
  KEY `ID_Tecnica` (`ID_Tecnica`),
  CONSTRAINT `detalle_obra_ibfk_1` FOREIGN KEY (`ID_Material`) REFERENCES `material` (`ID_Material`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_obra_ibfk_2` FOREIGN KEY (`ID_Tecnica`) REFERENCES `tecnica` (`ID_Tecnica`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.detalle_obra: ~14 rows (aproximadamente)
REPLACE INTO `detalle_obra` (`ID_Detalle`, `ID_Material`, `ID_Tecnica`, `detalles`, `alto`, `ancho`, `profundidad`) VALUES
	(2, 2, 12, 'Representación abstracta de colores', 30.4, 44, 0),
	(3, 3, 12, 'Bodegón con alto nivel de detalle', 52, 73, 0),
	(4, 2, 12, 'Retrato abstracto en óleo', 140, 170, 0),
	(5, 2, 12, 'Representación del odio', 114, 145, 0),
	(6, 4, 12, 'Obra en madera dorada con nácar', 60, 73.2, 5.5),
	(7, 2, 12, 'Noche estrellada con óleo sobre lienzo', 73.7, 92.1, 0),
	(8, 2, 12, 'Paisaje impresionista', 48, 63, 0),
	(9, 2, 12, 'Representación dual', 173.5, 173, 0),
	(12, 2, 12, '', 100, 80, 2),
	(14, 1, 13, 'Obra inspirada en la marea ', 100, 80, 2),
	(15, 2, 12, 'Paisaje impresionista', 48, 63, 2),
	(16, 1, 14, 'Obra con temple en papel', 330, 240, 0),
	(17, 5, 12, 'Fondo brillante con un patrón extravagante', 180, 180, 0),
	(18, 2, 12, 'Se trata de un tronie, un género pictórico típico de la holanda del siglo XVII que significa “rostro” o “expresión”', 46.5, 40, 0);

-- Volcando estructura para tabla galeria.material
CREATE TABLE IF NOT EXISTS `material` (
  `ID_Material` bigint(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Material`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.material: ~18 rows (aproximadamente)
REPLACE INTO `material` (`ID_Material`, `descripcion`) VALUES
	(1, 'Papel'),
	(2, 'Lienzo'),
	(3, 'Panel'),
	(4, 'Madera dorada'),
	(5, 'Tela'),
	(6, 'Piedra'),
	(7, 'Mármol'),
	(8, 'Acero'),
	(9, 'Oro'),
	(10, 'Vidrio'),
	(13, 'Madera'),
	(14, 'Cartón'),
	(15, 'Piedra o mármol'),
	(16, 'Metal'),
	(19, 'Azulejos'),
	(20, 'Cartulina'),
	(21, 'Plástico'),
	(22, 'Piel');

-- Volcando estructura para tabla galeria.obra
CREATE TABLE IF NOT EXISTS `obra` (
  `ID_Obra` bigint(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `ID_Artista` bigint(11) DEFAULT NULL,
  `fecha` int(4) DEFAULT NULL,
  `ID_Detalle` bigint(11) DEFAULT NULL,
  `imagen` blob DEFAULT NULL,
  PRIMARY KEY (`ID_Obra`),
  KEY `ID_Artista` (`ID_Artista`),
  KEY `ID_Detalle` (`ID_Detalle`),
  CONSTRAINT `obra_ibfk_1` FOREIGN KEY (`ID_Artista`) REFERENCES `artista` (`ID_Artista`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `obra_ibfk_3` FOREIGN KEY (`ID_Detalle`) REFERENCES `detalle_obra` (`ID_Detalle`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.obra: ~14 rows (aproximadamente)
REPLACE INTO `obra` (`ID_Obra`, `titulo`, `ID_Artista`, `fecha`, `ID_Detalle`, `imagen`) VALUES
	(1, 'Adulthood, Nr. 6', 1, 1907, 16, _binary 0x48696c6d612e6a666966),
	(2, 'Raya verde', 2, 1917, 2, _binary 0x4f49502e6a666966),
	(3, 'Bodegón con frutos secos, caramelos y flores', 3, 1611, 3, _binary 0x7374696c6c2d6c6966652d776974682d6e7574732d63616e64792d616e642d666c6f776572732d313631312e6a7067214c617267652e6a7067),
	(4, 'Retrato doble', 4, 1970, 4, _binary 0x522e6a666966),
	(5, 'De la malicia y del odio', 5, 1940, 5, _binary 0x696d6167656e322e6a7067),
	(6, 'Icono (abierto)', 6, 1945, 6, _binary 0x52656d6564696f732d5661726f2d49636f6e2d312e6a666966),
	(7, 'Noche estrellada', 7, 1889, 7, _binary 0x56616e5f476f67685f2d5f5374617272795f4e696768745f2d5f476f6f676c655f4172745f50726f6a6563742e6a7067),
	(8, 'Impresión, sol naciente', 8, 1872, 15, _binary 0x636c617564652d6d6f6e65742d496d7072657373696f6e2d736f6c65696c2d6c6576616e742d313837322d4d757365652d4d61726d6f7474616e2d4d6f6e65742d50617269732dc2a92d534c422d43687269737469616e2d426172616a612d31323030783931342d312e6a706567),
	(9, 'Las dos Fridas', 9, 1939, 9, _binary 0x696d61676573202831292e6a7067),
	(11, 'Flora Y Fauna', 10, 2002, 14, _binary 0x6f6272612d73616e6472612d7661737175657a2e77656270),
	(12, 'Viento y Marea ', 16, 1995, 12, _binary 0x63656239336338392d303437362d343664642d383864382d3866633536316239363564325f3236382e77656270),
	(13, 'El beso', 21, 1907, 17, _binary 0x5468655f4b6973735f2d5f4775737461765f4b6c696d745f2d5f476f6f676c655f43756c747572616c5f496e737469747574652e6a7067),
	(16, 'La joven de la perla', 22, 1677, 18, _binary 0x4d6569736a655f6d65745f64655f706172656c2e6a7067);

-- Volcando estructura para procedimiento galeria.ObtenerInformacionSubasta
DELIMITER //
CREATE PROCEDURE `ObtenerInformacionSubasta`(
	IN `obra` VARCHAR(255)
)
BEGIN
    SELECT 
        obra.ID_Obra,
        obra.titulo AS nombre_obra, 
        CONCAT(artista.nombre, ' ', artista.apellido) AS nombre_artista, 
        contar_ofertas_subasta(subasta.ID_Subasta) AS cantidad_ofertas, -- Llamada a la función de contar ofertas
		  MAX(oferta.propuesta) AS oferta_mas_alta,  
        subasta.fecha_inicio,
        subasta.fecha_fin,
        condicion.descripcion AS condicion
    FROM subasta
    INNER JOIN obra ON subasta.ID_Obra = obra.ID_Obra
    INNER JOIN artista ON obra.ID_Artista = artista.ID_Artista
    INNER JOIN condicion ON subasta.ID_Condicion = condicion.ID_Condicion
    LEFT JOIN oferta ON subasta.ID_Subasta = oferta.ID_Subasta
    WHERE obra.titulo LIKE CONCAT('%', obra, '%')
    GROUP BY obra.ID_Obra, obra.titulo, artista.nombre, artista.apellido, subasta.fecha_inicio, subasta.fecha_fin, condicion.descripcion
    ORDER BY subasta.fecha_fin DESC;
END//
DELIMITER ;

-- Volcando estructura para tabla galeria.oferta
CREATE TABLE IF NOT EXISTS `oferta` (
  `ID_Oferta` bigint(11) NOT NULL AUTO_INCREMENT,
  `ID_Subasta` bigint(11) DEFAULT NULL,
  `DNI_Usuario` bigint(11) DEFAULT NULL,
  `propuesta` int(11) DEFAULT NULL,
  `dia_hora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID_Oferta`),
  KEY `DNI_Usuario` (`DNI_Usuario`),
  KEY `ID_Subasta` (`ID_Subasta`),
  CONSTRAINT `oferta_ibfk_1` FOREIGN KEY (`DNI_Usuario`) REFERENCES `usuario` (`DNI_Usuario`),
  CONSTRAINT `oferta_ibfk_2` FOREIGN KEY (`ID_Subasta`) REFERENCES `subasta` (`ID_Subasta`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.oferta: ~9 rows (aproximadamente)
REPLACE INTO `oferta` (`ID_Oferta`, `ID_Subasta`, `DNI_Usuario`, `propuesta`, `dia_hora`) VALUES
	(8, 2, 313123123, 5000000, '2025-02-05 22:11:51'),
	(9, 12, 313123123, 1300000, '2025-02-05 22:12:00'),
	(10, 2, 313123123, 20000000, '2025-02-05 22:11:57'),
	(11, 9, 313123123, 2000000, '2025-02-03 00:53:59'),
	(12, 7, 1111, 1500000, '2025-02-05 22:12:05'),
	(22, 26, 1111, 1290000, '2025-02-05 23:24:27'),
	(23, 4, 1111, 12341, '2025-02-07 14:11:46'),
	(24, 6, 1111, 2350500, '2025-02-07 14:18:20'),
	(25, 1, 1111, 120012, '2025-02-07 14:19:54');

-- Volcando estructura para tabla galeria.subasta
CREATE TABLE IF NOT EXISTS `subasta` (
  `ID_Subasta` bigint(11) NOT NULL AUTO_INCREMENT,
  `ID_Obra` bigint(11) DEFAULT NULL,
  `valor_inicial` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `ID_Condicion` bigint(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Subasta`),
  KEY `ID_Obra` (`ID_Obra`),
  KEY `ID_Condicion` (`ID_Condicion`),
  CONSTRAINT `subasta_ibfk_1` FOREIGN KEY (`ID_Obra`) REFERENCES `obra` (`ID_Obra`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subasta_ibfk_2` FOREIGN KEY (`ID_Condicion`) REFERENCES `condicion` (`ID_Condicion`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.subasta: ~13 rows (aproximadamente)
REPLACE INTO `subasta` (`ID_Subasta`, `ID_Obra`, `valor_inicial`, `fecha_inicio`, `fecha_fin`, `ID_Condicion`) VALUES
	(1, 1, 11, '2025-01-31', '2025-02-13', 2),
	(2, 12, 1203900, '2025-02-03', '2025-02-16', 3),
	(3, 2, 456523, '2025-02-03', '2025-02-07', 3),
	(4, 3, 12, '2025-02-07', '2025-02-08', 3),
	(5, 4, 2, '2025-02-07', '2025-02-08', 3),
	(6, 5, 2350000, '2025-02-06', '2025-02-14', 2),
	(7, 6, 12000, '2025-02-03', '2025-02-04', 3),
	(8, 7, NULL, NULL, NULL, 1),
	(9, 8, 1000000, '2025-02-05', '2025-02-09', 2),
	(10, 9, NULL, NULL, NULL, 1),
	(12, 11, 1222200, '2025-02-04', '2025-02-21', 2),
	(26, 13, 1000000, '2025-02-05', '2025-02-06', 3),
	(29, 16, 12300000, '2025-02-07', '2025-02-07', 3);

-- Volcando estructura para tabla galeria.tecnica
CREATE TABLE IF NOT EXISTS `tecnica` (
  `ID_Tecnica` bigint(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Tecnica`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.tecnica: ~15 rows (aproximadamente)
REPLACE INTO `tecnica` (`ID_Tecnica`, `descripcion`) VALUES
	(11, 'Acuarela'),
	(12, 'Óleo'),
	(13, 'Acrílico'),
	(14, 'Tempera'),
	(15, 'Fresco'),
	(16, 'Pastel'),
	(17, 'Encaústica'),
	(18, 'Tempera al huevo'),
	(19, 'Pintura digital'),
	(20, 'Espátula o paleta'),
	(21, 'Pointillismo'),
	(22, 'Sfumato'),
	(23, 'Impasto'),
	(24, 'Collage'),
	(25, 'Muralismo');

-- Volcando estructura para tabla galeria.tipo_usuario
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `ID_Tipo` int(11) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.tipo_usuario: ~2 rows (aproximadamente)
REPLACE INTO `tipo_usuario` (`ID_Tipo`, `descripcion`) VALUES
	(0, 'Administrador'),
	(1, 'Subastante');

-- Volcando estructura para tabla galeria.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `DNI_Usuario` bigint(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `telefono` bigint(11) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `ID_Tipo` int(11) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `localidad` varchar(50) DEFAULT NULL,
  `provincia` varchar(50) DEFAULT NULL,
  `CP` bigint(7) DEFAULT NULL,
  PRIMARY KEY (`DNI_Usuario`),
  KEY `ID_Tipo` (`ID_Tipo`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`ID_Tipo`) REFERENCES `tipo_usuario` (`ID_Tipo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla galeria.usuario: ~4 rows (aproximadamente)
REPLACE INTO `usuario` (`DNI_Usuario`, `nombre`, `apellido`, `mail`, `telefono`, `password`, `ID_Tipo`, `estado`, `localidad`, `provincia`, `CP`) VALUES
	(333, 'Diego', 'Jofre', 'diego@gmail.com', 526267, '4fb845c67d91bcb3178498fc6fe1fedc', 0, 'A', 'Las Heras', 'Mendoza', 5539),
	(1111, 'Julieta', 'Ruppert', 'juli@gmail.com', 2614567878, '919cd6c84443ec964fb0aa7196e1baec', 1, 'A', 'Las Heras', 'Mendoza', 5539),
	(8888, 'Martin', 'Martinez', 'naranja@gmail.com', 789788, '81d6f316d169150d0e8733866c38684d', 0, 'A', 'Trelew', 'Chubut', 5678),
	(313123123, 'Emilia', 'Mernez', 'emilia@gmail.com', 23213123, '63dac7596153ef4da82e037963713a54', 1, 'A', 'Guaymellen', 'Mendoza', 5519);

-- Volcando estructura para vista galeria.vista_ofertas
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `vista_ofertas` (
	`Usuario` VARCHAR(101) NULL COLLATE 'utf8mb4_general_ci',
	`Correo Electrónico` VARCHAR(50) NULL COLLATE 'utf8mb4_general_ci',
	`Teléfono` BIGINT(11) NULL,
	`Obra` VARCHAR(100) NULL COLLATE 'utf8mb4_general_ci',
	`Artista` VARCHAR(101) NULL COLLATE 'utf8mb4_general_ci',
	`Oferta` INT(11) NULL,
	`Fecha de Inicio` DATE NULL,
	`Fecha de Fin` DATE NULL,
	`Condición` VARCHAR(50) NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Volcando estructura para vista galeria.vista_oferta_usuarios
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `vista_oferta_usuarios` (
	`ID_Obra` BIGINT(11) NULL,
	`nombre_obra` VARCHAR(100) NULL COLLATE 'utf8mb4_general_ci',
	`nombre_artista` VARCHAR(101) NULL COLLATE 'utf8mb4_general_ci',
	`oferta` INT(11) NULL,
	`oferta_mas_alta` INT(11) NULL,
	`fecha_inicio` DATE NULL,
	`fecha_fin` DATE NULL,
	`condicion` VARCHAR(50) NULL COLLATE 'utf8mb4_general_ci',
	`DNI_Usuario` BIGINT(11) NULL
) ENGINE=MyISAM;

-- Volcando estructura para disparador galeria.actualizar_condicion_subasta
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `actualizar_condicion_subasta` BEFORE UPDATE ON `subasta` FOR EACH ROW 
BEGIN
    -- Si la fecha de fin ha pasado y la condición no es 'Finalizada' (ID_Condicion = 3), cambiar el estado
    IF NEW.fecha_fin <= CURDATE() AND NEW.ID_Condicion != 3 THEN
        SET NEW.ID_Condicion = 3;
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador galeria.obra_AFTER_INSERT
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `obra_AFTER_INSERT` AFTER INSERT ON `obra` FOR EACH ROW BEGIN
    -- Inserta un nuevo registro en la tabla subasta cuando se agrega una obra
    INSERT INTO subasta (ID_Obra, valor_inicial, ID_Condicion) 
    VALUES (NEW.ID_Obra, 0, 1); 
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `vista_ofertas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_ofertas` AS SELECT 
    CONCAT(u.nombre, ' ', u.apellido) AS Usuario,
    u.mail AS `Correo Electrónico`,
    u.telefono AS Teléfono,
    o.ID_Obra,  -- Se agregó el ID de la obra
    o.titulo AS Obra,
    CONCAT(a.nombre, ' ', a.apellido) AS Artista,
    ofe.propuesta AS Oferta,
    s.fecha_inicio AS `Fecha de Inicio`,
    s.fecha_fin AS `Fecha de Fin`,
    c.descripcion AS Condición
FROM oferta ofe
JOIN usuario u ON ofe.DNI_Usuario = u.DNI_Usuario
JOIN subasta s ON ofe.ID_Subasta = s.ID_Subasta
JOIN obra o ON s.ID_Obra = o.ID_Obra
JOIN artista a ON o.ID_Artista = a.ID_Artista
JOIN condicion c ON s.ID_Condicion = c.ID_Condicion ;

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `vista_oferta_usuarios`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_oferta_usuarios` AS SELECT 
    obra.ID_Obra, 
    obra.titulo AS nombre_obra, 
    CONCAT(artista.nombre, ' ', artista.apellido) AS nombre_artista, 
    oferta.propuesta AS oferta,
    MAX(oferta.propuesta) OVER (PARTITION BY oferta.ID_Subasta) AS oferta_mas_alta, 
    subasta.fecha_inicio,
    subasta.fecha_fin,
    condicion.descripcion AS condicion,
    usuario.DNI_Usuario 
FROM oferta
INNER JOIN subasta ON oferta.ID_Subasta = subasta.ID_Subasta
INNER JOIN obra ON subasta.ID_Obra = obra.ID_Obra
INNER JOIN artista ON obra.ID_Artista = artista.ID_Artista
INNER JOIN condicion ON subasta.ID_Condicion = condicion.ID_Condicion
INNER JOIN usuario ON oferta.DNI_Usuario = usuario.DNI_Usuario ;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
