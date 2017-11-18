

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";



CREATE DATABASE IF NOT EXISTS `agendaJMAN` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `agendaJMAN`;

-- --------------------------------------------------------


CREATE TABLE `eventos` (
  `id` int(5) NOT NULL,
  `email` text NOT NULL,
  `titulo` text NOT NULL,
  `fechainicio` date NOT NULL,
  `horainicio` time DEFAULT NULL,
  `fechafinal` date DEFAULT NULL,
  `horafinal` time DEFAULT NULL,
  `todoeldia` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------



CREATE TABLE `usuarios` (
  `email` text NOT NULL,
  `nombre` text NOT NULL,
  `password` text NOT NULL,
  `nacimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`email`(45));


ALTER TABLE `eventos`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;


