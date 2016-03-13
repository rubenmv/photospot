-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2013 at 01:33 PM
-- Server version: 5.5.32
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `photospot_albumes`
--

CREATE TABLE IF NOT EXISTS `photospot_albumes` (
  `IdAlbum` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `Descripcion` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Pais` smallint(6) DEFAULT NULL,
  `Usuario` int(11) NOT NULL,
  `Fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdAlbum`),
  KEY `Pais` (`Pais`),
  KEY `Usuario` (`Usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `photospot_albumes`
--

INSERT INTO `photospot_albumes` (`IdAlbum`, `Titulo`, `Descripcion`, `Pais`, `Usuario`, `Fecha`) VALUES
(3, 'Fotografías', 'Algunas fotografías de prueba. Todas bajo licencia de dominio público', 1, 2, '2013-09-15 10:56:18'),
(4, 'Mis cosas', 'Viajes', 14, 2, '2013-09-15 11:28:47');

-- --------------------------------------------------------

--
-- Table structure for table `photospot_comentarios`
--

CREATE TABLE IF NOT EXISTS `photospot_comentarios` (
  `IdComentario` int(11) NOT NULL AUTO_INCREMENT,
  `IdFoto` int(11) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `Texto` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `Fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdComentario`),
  KEY `IdFoto` (`IdFoto`),
  KEY `IdUsuario` (`IdUsuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `photospot_comentarios`
--

INSERT INTO `photospot_comentarios` (`IdComentario`, `IdFoto`, `IdUsuario`, `Texto`, `Fecha`) VALUES
(5, 4, 2, 'La primera fotografía y el primer comentario', '2013-09-15 10:57:48'),
(6, 6, 2, 'Muy gracioso el pato', '2013-09-15 11:00:12'),
(7, 11, 2, 'Buen nadador', '2013-09-15 11:30:52'),
(8, 9, 2, 'Muy bonito, este comentario va a ser más largo para comprobar que se corta correctamente en portada', '2013-09-15 11:31:30'),
(9, 6, 2, 'Y el agua parece un espejo', '2013-09-15 11:32:08');

-- --------------------------------------------------------

--
-- Table structure for table `photospot_fotos`
--

CREATE TABLE IF NOT EXISTS `photospot_fotos` (
  `IdFoto` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `Pais` smallint(6) DEFAULT NULL,
  `Album` int(11) NOT NULL,
  `NumVotos` int(11) NOT NULL DEFAULT '0',
  `PuntuacionTotal` int(11) NOT NULL DEFAULT '0',
  `Fichero` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `Fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdFoto`),
  KEY `Pais` (`Pais`),
  KEY `Album` (`Album`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `photospot_fotos`
--

INSERT INTO `photospot_fotos` (`IdFoto`, `Titulo`, `Pais`, `Album`, `NumVotos`, `PuntuacionTotal`, `Fichero`, `Fecha`) VALUES
(4, 'Flores', NULL, 3, 1, 4, 'files/1379242633_almond-blossom-5378_1280.jpg', '2013-09-15 10:57:13'),
(5, 'Gota de agua', 3, 3, 0, 0, 'files/1379242731_drop-111991_1280.jpg', '2013-09-15 10:58:51'),
(6, 'Pato', 11, 3, 1, 3, 'files/1379242784_duck-110878_1280.jpg', '2013-09-15 10:59:44'),
(7, 'Manos', NULL, 3, 0, 0, 'files/1379243372_hands-105455_1280.jpg', '2013-09-15 11:09:32'),
(8, 'Ice Climbing', 2, 4, 0, 0, 'files/1379244554_ice-climbing-901_640.jpg', '2013-09-15 11:29:14'),
(9, 'Parrot', 1, 4, 0, 0, 'files/1379244575_parrot-165136_1280.jpg', '2013-09-15 11:29:35'),
(10, 'Zapatos', NULL, 4, 0, 0, 'files/1379244590_shoes-181744_1280.jpg', '2013-09-15 11:29:50'),
(11, 'Staniel Cay', NULL, 4, 1, 4, 'files/1379244604_staniel-cay-171908_640.jpg', '2013-09-15 11:30:04'),
(12, 'Sol', 1, 4, 0, 0, 'files/1379244611_sun-97255_1280.jpg', '2013-09-15 11:30:11');

-- --------------------------------------------------------

--
-- Table structure for table `photospot_paises`
--

CREATE TABLE IF NOT EXISTS `photospot_paises` (
  `IdPais` smallint(6) NOT NULL AUTO_INCREMENT,
  `NomPais` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdPais`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=249 ;

--
-- Dumping data for table `photospot_paises`
--

INSERT INTO `photospot_paises` (`IdPais`, `NomPais`) VALUES
(1, 'España'),
(2, 'Andorra'),
(3, 'United Arab Emirates'),
(4, 'Afghanistan'),
(5, 'Antigua and Barbuda'),
(6, 'Anguilla'),
(7, 'Albania'),
(8, 'Armenia'),
(9, 'Angola'),
(10, 'Antarctica'),
(11, 'Argentina'),
(12, 'American Samoa'),
(13, 'Austria'),
(14, 'Australia'),
(15, 'Aruba'),
(16, 'Azerbaijan'),
(17, 'Bosnia and Herzegovina'),
(18, 'Barbados'),
(19, 'Bangladesh'),
(20, 'Belgium'),
(21, 'Burkina Faso'),
(22, 'Bulgaria'),
(23, 'Bahrain'),
(24, 'Burundi'),
(25, 'Benin'),
(26, 'Bermuda'),
(27, 'Brunei'),
(28, 'Bolivia'),
(29, 'Bonaire'),
(30, 'Brazil'),
(31, 'Bahamas'),
(32, 'Bhutan'),
(33, 'Bouvet Island'),
(34, 'Botswana'),
(35, 'Belarus'),
(36, 'Belize'),
(37, 'Canada'),
(38, 'Cocos [Keeling] Islands'),
(39, 'Congo'),
(40, 'Central African Republic'),
(41, 'Republic of the Congo'),
(42, 'Switzerland'),
(43, 'Ivory Coast'),
(44, 'Cook Islands'),
(45, 'Chile'),
(46, 'Cameroon'),
(47, 'China'),
(48, 'Colombia'),
(49, 'Costa Rica'),
(50, 'Cuba'),
(51, 'Cape Verde'),
(52, 'Curacao'),
(53, 'Christmas Island'),
(54, 'Cyprus'),
(55, 'Czechia'),
(56, 'Germany'),
(57, 'Djibouti'),
(58, 'Denmark'),
(59, 'Dominica'),
(60, 'Dominican Republic'),
(61, 'Algeria'),
(62, 'Ecuador'),
(63, 'Estonia'),
(64, 'Egypt'),
(65, 'Western Sahara'),
(66, 'Eritrea'),
(67, 'Spain'),
(68, 'Ethiopia'),
(69, 'Finland'),
(70, 'Fiji'),
(71, 'Falkland Islands'),
(72, 'Micronesia'),
(73, 'Faroe Islands'),
(74, 'France'),
(75, 'Gabon'),
(76, 'United Kingdom'),
(77, 'Grenada'),
(78, 'Georgia'),
(79, 'French Guiana'),
(80, 'Guernsey'),
(81, 'Ghana'),
(82, 'Gibraltar'),
(83, 'Greenland'),
(84, 'Gambia'),
(85, 'Guinea'),
(86, 'Guadeloupe'),
(87, 'Equatorial Guinea'),
(88, 'Greece'),
(89, 'South Georgia and the South Sandwich Islands'),
(90, 'Guatemala'),
(91, 'Guam'),
(92, 'Guinea-Bissau'),
(93, 'Guyana'),
(94, 'Hong Kong'),
(95, 'Heard Island and McDonald Islands'),
(96, 'Honduras'),
(97, 'Croatia'),
(98, 'Haiti'),
(99, 'Hungary'),
(100, 'Indonesia'),
(101, 'Ireland'),
(102, 'Israel'),
(103, 'Isle of Man'),
(104, 'India'),
(105, 'British Indian Ocean Territory'),
(106, 'Iraq'),
(107, 'Iran'),
(108, 'Iceland'),
(109, 'Italy'),
(110, 'Jersey'),
(111, 'Jamaica'),
(112, 'Jordan'),
(113, 'Japan'),
(114, 'Kenya'),
(115, 'Kyrgyzstan'),
(116, 'Cambodia'),
(117, 'Kiribati'),
(118, 'Comoros'),
(119, 'Saint Kitts and Nevis'),
(120, 'North Korea'),
(121, 'South Korea'),
(122, 'Kuwait'),
(123, 'Cayman Islands'),
(124, 'Kazakhstan'),
(125, 'Laos'),
(126, 'Lebanon'),
(127, 'Saint Lucia'),
(128, 'Liechtenstein'),
(129, 'Sri Lanka'),
(130, 'Liberia'),
(131, 'Lesotho'),
(132, 'Lithuania'),
(133, 'Luxembourg'),
(134, 'Latvia'),
(135, 'Libya'),
(136, 'Morocco'),
(137, 'Monaco'),
(138, 'Moldova'),
(139, 'Montenegro'),
(140, 'Saint Martin'),
(141, 'Madagascar'),
(142, 'Marshall Islands'),
(143, 'Macedonia'),
(144, 'Mali'),
(145, 'Myanmar [Burma]'),
(146, 'Mongolia'),
(147, 'Macao'),
(148, 'Northern Mariana Islands'),
(149, 'Martinique'),
(150, 'Mauritania'),
(151, 'Montserrat'),
(152, 'Malta'),
(153, 'Mauritius'),
(154, 'Maldives'),
(155, 'Malawi'),
(156, 'Mexico'),
(157, 'Malaysia'),
(158, 'Mozambique'),
(159, 'Namibia'),
(160, 'New Caledonia'),
(161, 'Niger'),
(162, 'Norfolk Island'),
(163, 'Nigeria'),
(164, 'Nicaragua'),
(165, 'Netherlands'),
(166, 'Norway'),
(167, 'Nepal'),
(168, 'Nauru'),
(169, 'Niue'),
(170, 'New Zealand'),
(171, 'Oman'),
(172, 'Panama'),
(173, 'Peru'),
(174, 'French Polynesia'),
(175, 'Papua New Guinea'),
(176, 'Philippines'),
(177, 'Pakistan'),
(178, 'Poland'),
(179, 'Saint Pierre and Miquelon'),
(180, 'Pitcairn Islands'),
(181, 'Puerto Rico'),
(182, 'Palestine'),
(183, 'Portugal'),
(184, 'Palau'),
(185, 'Paraguay'),
(186, 'Qatar'),
(187, 'Romania'),
(188, 'Serbia'),
(189, 'Russia'),
(190, 'Rwanda'),
(191, 'Saudi Arabia'),
(192, 'Solomon Islands'),
(193, 'Seychelles'),
(194, 'Sudan'),
(195, 'Sweden'),
(196, 'Singapore'),
(197, 'Saint Helena'),
(198, 'Slovenia'),
(199, 'Svalbard and Jan Mayen'),
(200, 'Slovakia'),
(201, 'Sierra Leone'),
(202, 'San Marino'),
(203, 'Senegal'),
(204, 'Somalia'),
(205, 'España'),
(206, 'Suriname'),
(207, 'South Sudan'),
(208, 'El Salvador'),
(209, 'Sint Maarten'),
(210, 'Syria'),
(211, 'Swaziland'),
(212, 'Turks and Caicos Islands'),
(213, 'Chad'),
(214, 'French Southern Territories'),
(215, 'Togo'),
(216, 'Thailand'),
(217, 'Tajikistan'),
(218, 'Tokelau'),
(219, 'East Timor'),
(220, 'Turkmenistan'),
(221, 'Tunisia'),
(222, 'Tonga'),
(223, 'Turkey'),
(224, 'Trinidad and Tobago'),
(225, 'Tuvalu'),
(226, 'Taiwan'),
(227, 'Tanzania'),
(228, 'Ukraine'),
(229, 'Uganda'),
(230, 'U.S. Minor Outlying Islands'),
(231, 'United States'),
(232, 'Uruguay'),
(233, 'Uzbekistan'),
(234, 'Vatican City'),
(235, 'Saint Vincent and the Grenadines'),
(236, 'Venezuela'),
(237, 'British Virgin Islands'),
(238, 'U.S. Virgin Islands'),
(239, 'Vietnam'),
(240, 'Vanuatu'),
(241, 'Wallis and Futuna'),
(242, 'Samoa'),
(243, 'Kosovo'),
(244, 'Yemen'),
(245, 'Mayotte'),
(246, 'South Africa'),
(247, 'Zambia'),
(248, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `photospot_usuarios`
--

CREATE TABLE IF NOT EXISTS `photospot_usuarios` (
  `IdUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `NomUsuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `Clave` varchar(70) COLLATE utf8_spanish_ci NOT NULL,
  `Email` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `Sexo` tinyint(4) DEFAULT NULL,
  `FNacimiento` date NOT NULL,
  `Ciudad` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Pais` smallint(6) DEFAULT NULL,
  `Foto` varchar(256) COLLATE utf8_spanish_ci DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdUsuario`),
  UNIQUE KEY `NomUsuario` (`NomUsuario`),
  KEY `Pais` (`Pais`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `photospot_usuarios`
--

INSERT INTO `photospot_usuarios` (`IdUsuario`, `NomUsuario`, `Clave`, `Email`, `Sexo`, `FNacimiento`, `Ciudad`, `Pais`, `Foto`, `FRegistro`) VALUES
(2, 'test', 'f69ddcc92c44eb5a6320e241183ef551d9287d7fa6e4b2c77459145d8dd0bb37', 'rub3nmv@gmail.com', 0, '1986-11-08', 'Alicante', 10, 'files/profile/2-generico.jpg', '2013-09-15 10:55:01');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `photospot_albumes`
--
ALTER TABLE `photospot_albumes`
  ADD CONSTRAINT `albumes_ibfk_1` FOREIGN KEY (`Pais`) REFERENCES `photospot_paises` (`IdPais`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `albumes_ibfk_2` FOREIGN KEY (`Usuario`) REFERENCES `photospot_usuarios` (`IdUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `photospot_comentarios`
--
ALTER TABLE `photospot_comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`IdFoto`) REFERENCES `photospot_fotos` (`IdFoto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `photospot_usuarios` (`IdUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `photospot_fotos`
--
ALTER TABLE `photospot_fotos`
  ADD CONSTRAINT `fotos_ibfk_1` FOREIGN KEY (`Pais`) REFERENCES `photospot_paises` (`IdPais`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fotos_ibfk_2` FOREIGN KEY (`Album`) REFERENCES `photospot_albumes` (`IdAlbum`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `photospot_usuarios`
--
ALTER TABLE `photospot_usuarios`
  ADD CONSTRAINT `usuarios_ibfk` FOREIGN KEY (`Pais`) REFERENCES `photospot_paises` (`IdPais`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
