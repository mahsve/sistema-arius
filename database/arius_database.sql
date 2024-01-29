-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 29-01-2024 a las 18:21:39
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `arius_database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_cargos`
--

CREATE TABLE `tb_cargos` (
  `id_cargo` int(11) NOT NULL,
  `cargo` varchar(60) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_cargos`
--

INSERT INTO `tb_cargos` (`id_cargo`, `cargo`, `id_departamento`, `created`, `updated`, `estatus`) VALUES
(1, 'Administrador', 1, '2024-01-16 11:08:23', '2024-01-16 11:08:23', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_clientes`
--

CREATE TABLE `tb_clientes` (
  `identificacion` varchar(11) NOT NULL,
  `tipo_cliente` char(1) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `correo_electronico` varchar(60) DEFAULT NULL,
  `telefono1` char(11) NOT NULL,
  `telefono2` char(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_clientes`
--

INSERT INTO `tb_clientes` (`identificacion`, `tipo_cliente`, `nombre_completo`, `correo_electronico`, `telefono1`, `telefono2`, `created`, `updated`, `estatus`) VALUES
('10142010', 'N', 'Ramón Herrera', NULL, '2132123123', NULL, '2024-01-24 11:42:48', '2024-01-24 11:42:48', 'A'),
('12312312312', 'N', 'Pedro Camejo', 'asdasd@gmail.com', '04120570120', NULL, '2024-01-24 10:38:43', '2024-01-24 10:49:14', 'A'),
('25791966', 'N', 'Miguel Herrera', 'miguelsot959@gmail.com', '04120570120', NULL, '2024-01-24 10:43:19', '2024-01-24 10:43:19', 'A'),
('258426472', 'N', 'Ramon Herrera', NULL, '12312312321', NULL, '2024-01-24 11:51:18', '2024-01-24 11:51:18', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_contactos`
--

CREATE TABLE `tb_contactos` (
  `id_contacto` int(11) NOT NULL,
  `id_cliente` varchar(11) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `id_codigo` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_contactos`
--

INSERT INTO `tb_contactos` (`id_contacto`, `id_cliente`, `contrasena`, `observacion`, `id_codigo`) VALUES
(1, '10142010', 'adasdsadassd', NULL, '1234'),
(2, '258426472', 'adasdad', NULL, '1445');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_departamentos`
--

CREATE TABLE `tb_departamentos` (
  `id_departamento` int(11) NOT NULL,
  `departamento` varchar(45) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_departamentos`
--

INSERT INTO `tb_departamentos` (`id_departamento`, `departamento`, `created`, `updated`, `estatus`) VALUES
(1, 'Informáctica', '2024-01-16 11:08:22', '2024-01-16 11:08:22', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_mapa_zonas`
--

CREATE TABLE `tb_mapa_zonas` (
  `id_codigo` char(4) NOT NULL,
  `id_cliente` varchar(11) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `punto_referencia` varchar(250) DEFAULT NULL,
  `hora_operacion_1` char(20) DEFAULT NULL,
  `hora_operacion_2` char(20) DEFAULT NULL,
  `sabado_domingo` varchar(20) DEFAULT NULL,
  `panel_version` varchar(120) DEFAULT NULL,
  `modelo_teclado` varchar(120) DEFAULT NULL,
  `reporta_por` varchar(120) DEFAULT NULL,
  `telefono_assig` char(11) DEFAULT NULL,
  `fecha_instalacion` date DEFAULT NULL,
  `cedula_asesor` char(8) NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `ubicacion_panel` varchar(200) DEFAULT NULL,
  `observaciones` varchar(2000) DEFAULT NULL,
  `particiones_sistema` varchar(120) DEFAULT NULL,
  `imei` char(15) DEFAULT NULL,
  `linea_principal` varchar(20) DEFAULT NULL,
  `linea_respaldo` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_mapa_zonas`
--

INSERT INTO `tb_mapa_zonas` (`id_codigo`, `id_cliente`, `direccion`, `punto_referencia`, `hora_operacion_1`, `hora_operacion_2`, `sabado_domingo`, `panel_version`, `modelo_teclado`, `reporta_por`, `telefono_assig`, `fecha_instalacion`, `cedula_asesor`, `fecha_entrega`, `ubicacion_panel`, `observaciones`, `particiones_sistema`, `imei`, `linea_principal`, `linea_respaldo`, `created`, `updated`, `estatus`) VALUES
('1234', '25791966', 'Urb. Prados del sol', 'dasdasdasd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25791966', NULL, NULL, 'asdadasdasd asdasd', NULL, NULL, NULL, NULL, '2024-01-24 11:42:48', '2024-01-24 11:49:57', 'A'),
('1445', '25791966', 'Urb. Prados del sol', '2222', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25791966', NULL, NULL, 'aaaa 2', NULL, NULL, NULL, NULL, '2024-01-24 11:51:18', '2024-01-24 11:52:43', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_notas`
--

CREATE TABLE `tb_notas` (
  `id_nota` int(11) NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_personal`
--

CREATE TABLE `tb_personal` (
  `cedula` char(8) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `correo` varchar(45) DEFAULT NULL,
  `telefono1` char(11) NOT NULL,
  `telefono2` char(11) DEFAULT NULL,
  `direccion` varchar(120) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_personal`
--

INSERT INTO `tb_personal` (`cedula`, `nombres`, `apellidos`, `correo`, `telefono1`, `telefono2`, `direccion`, `id_cargo`, `created`, `updated`, `estatus`) VALUES
('25791966', 'Miguel', 'Herrera', 'miguelsot959@gmail.com', '04120570120', NULL, 'Urb. Prados del sol', 1, '2024-01-16 11:08:23', '2024-01-16 11:08:23', 'A'),
('25791967', 'Miguel', 'Herrera', 'miguelsot959@gmail.com', '045678456', NULL, 'asdasdadsd', 1, '2024-01-24 09:32:43', '2024-01-24 09:32:43', 'A'),
('2579198', 'Miguel', 'Herrera', 'miguelsot95922@gmail.com', '04120570120', NULL, 'sadasdsd', 1, '2024-01-24 09:39:44', '2024-01-24 09:39:44', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_rd_detalles`
--

CREATE TABLE `tb_rd_detalles` (
  `id_detalle` int(11) NOT NULL,
  `id_reporte_diario` int(11) NOT NULL,
  `id_codigo` char(4) NOT NULL,
  `hora` time NOT NULL,
  `evento` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_reportes_diarios`
--

CREATE TABLE `tb_reportes_diarios` (
  `id_reporte_diario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cedula` char(8) NOT NULL,
  `turno` varchar(20) NOT NULL,
  `ap_sin_clo` varchar(45) NOT NULL,
  `ap_con_clo` varchar(45) NOT NULL,
  `eventualidad_emp` varchar(45) NOT NULL,
  `informado_por` varchar(45) NOT NULL,
  `activaciones` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_reporte_turnos`
--

CREATE TABLE `tb_reporte_turnos` (
  `id_reporte_turno` int(11) NOT NULL,
  `id_reporte_diario` int(11) NOT NULL,
  `tipo_estatus` char(1) NOT NULL,
  `servidor_msj` varchar(45) NOT NULL,
  `radio` varchar(45) NOT NULL,
  `lineas_reportes` varchar(45) NOT NULL,
  `observaciones` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_servicios_solicitados`
--

CREATE TABLE `tb_servicios_solicitados` (
  `id_servicio_solicitado` int(11) NOT NULL,
  `id_codigo` char(4) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `motivo` varchar(2000) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_tecnicos_instalacion`
--

CREATE TABLE `tb_tecnicos_instalacion` (
  `id_tecnico_instalacion` int(11) NOT NULL,
  `cedula` char(8) NOT NULL,
  `id_codigo` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_tecnicos_visitas`
--

CREATE TABLE `tb_tecnicos_visitas` (
  `id_tecnico_visita` int(11) NOT NULL,
  `cedula` char(8) NOT NULL,
  `id_detalle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuarios`
--

CREATE TABLE `tb_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `cedula` char(8) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `pregunta1` varchar(60) DEFAULT NULL,
  `respuesta1` varchar(255) DEFAULT NULL,
  `pregunta2` varchar(60) DEFAULT NULL,
  `respuesta2` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `estatus` char(1) NOT NULL DEFAULT 'P'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id_usuario`, `cedula`, `usuario`, `contrasena`, `pregunta1`, `respuesta1`, `pregunta2`, `respuesta2`, `created`, `updated`, `estatus`) VALUES
(1, '25791966', 'admin', '$2y$10$OP4yhioIZ74qvQGVl982WOiBDxOAtPcKrxP695XI0ALMf7KSUjZyq', '-', '-', '-', '-', '2024-01-16 11:08:23', '2024-01-16 11:08:23', 'P'),
(2, '25791967', 'MiguelHerrera4', '$2y$10$kFE6jzVtWYHFI3sYWLRNYeYmA4ZXVtHi5iStgCFTxRAGO62JgaFCW', NULL, NULL, NULL, NULL, '2024-01-24 09:32:43', '2024-01-24 09:32:43', 'P'),
(3, '2579198', 'MiguelHerrera4', '$2y$10$oqbofTMvMC58yXFKdMTUiOBy1w6W162DHdUcG4drFS5tw.SljX3Ha', NULL, NULL, NULL, NULL, '2024-01-24 09:39:44', '2024-01-24 09:39:44', 'P');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_visitas`
--

CREATE TABLE `tb_visitas` (
  `id_visitas` int(11) NOT NULL,
  `id_codigo` char(4) NOT NULL,
  `id_servicio_solicitado` int(11) DEFAULT NULL,
  `fecha_visita` date NOT NULL,
  `servicio_prestado` varchar(2000) NOT NULL,
  `pendientes` varchar(250) DEFAULT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_zonas`
--

CREATE TABLE `tb_zonas` (
  `id_zona` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `configuracion` varchar(60) DEFAULT NULL,
  `equipos` varchar(40) NOT NULL,
  `id_codigo` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_cargos`
--
ALTER TABLE `tb_cargos`
  ADD PRIMARY KEY (`id_cargo`),
  ADD KEY `fk_tb_tipos_personal_tb_departamentos1_idx` (`id_departamento`);

--
-- Indices de la tabla `tb_clientes`
--
ALTER TABLE `tb_clientes`
  ADD PRIMARY KEY (`identificacion`);

--
-- Indices de la tabla `tb_contactos`
--
ALTER TABLE `tb_contactos`
  ADD PRIMARY KEY (`id_contacto`),
  ADD KEY `fk_tb_usuarios_tb_clientes1_idx` (`id_cliente`),
  ADD KEY `fk_tb_usuarios_tb_contratos1_idx` (`id_codigo`);

--
-- Indices de la tabla `tb_departamentos`
--
ALTER TABLE `tb_departamentos`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `tb_mapa_zonas`
--
ALTER TABLE `tb_mapa_zonas`
  ADD PRIMARY KEY (`id_codigo`),
  ADD KEY `fk_tb_contrato_tb_clientes1_idx` (`id_cliente`),
  ADD KEY `fk_tb_mapa_zonas_tb_personal1_idx` (`cedula_asesor`);

--
-- Indices de la tabla `tb_notas`
--
ALTER TABLE `tb_notas`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `fk_tb_tareas_tb_usuarios1_idx` (`id_usuario`);

--
-- Indices de la tabla `tb_personal`
--
ALTER TABLE `tb_personal`
  ADD PRIMARY KEY (`cedula`),
  ADD KEY `fk_tb_personal_tb_tipo_personal1_idx` (`id_cargo`);

--
-- Indices de la tabla `tb_rd_detalles`
--
ALTER TABLE `tb_rd_detalles`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_tb_rd_detalles_tb_reportes_diarios1_idx` (`id_reporte_diario`),
  ADD KEY `fk_tb_rd_detalles_tb_mapa_zonas1_idx` (`id_codigo`);

--
-- Indices de la tabla `tb_reportes_diarios`
--
ALTER TABLE `tb_reportes_diarios`
  ADD PRIMARY KEY (`id_reporte_diario`),
  ADD KEY `fk_tb_reportes_diarios_tb_personal1_idx` (`cedula`);

--
-- Indices de la tabla `tb_reporte_turnos`
--
ALTER TABLE `tb_reporte_turnos`
  ADD PRIMARY KEY (`id_reporte_turno`),
  ADD KEY `fk_tb_reporte_turnos_tb_reportes_diarios1_idx` (`id_reporte_diario`);

--
-- Indices de la tabla `tb_servicios_solicitados`
--
ALTER TABLE `tb_servicios_solicitados`
  ADD PRIMARY KEY (`id_servicio_solicitado`),
  ADD KEY `fk_tb_servicios_solicitados_tb_mapa_zonas1_idx` (`id_codigo`);

--
-- Indices de la tabla `tb_tecnicos_instalacion`
--
ALTER TABLE `tb_tecnicos_instalacion`
  ADD PRIMARY KEY (`id_tecnico_instalacion`),
  ADD KEY `fk_tb_tecnicos_tb_personal1_idx` (`cedula`),
  ADD KEY `fk_tb_tecnicos_instalacion_tb_mapa_zonas1_idx` (`id_codigo`);

--
-- Indices de la tabla `tb_tecnicos_visitas`
--
ALTER TABLE `tb_tecnicos_visitas`
  ADD PRIMARY KEY (`id_tecnico_visita`),
  ADD KEY `fk_tb_tecnicos_visitas_tb_personal1_idx` (`cedula`),
  ADD KEY `fk_tb_tecnicos_visitas_tb_visitas_detalles1_idx` (`id_detalle`);

--
-- Indices de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_tb_usuarios_tb_personal1_idx` (`cedula`);

--
-- Indices de la tabla `tb_visitas`
--
ALTER TABLE `tb_visitas`
  ADD PRIMARY KEY (`id_visitas`),
  ADD KEY `fk_tb_visitas_detalles_tb_mapa_zonas1_idx` (`id_codigo`),
  ADD KEY `fk_tb_visitas_detalles_tb_servicios_solicitados1_idx` (`id_servicio_solicitado`);

--
-- Indices de la tabla `tb_zonas`
--
ALTER TABLE `tb_zonas`
  ADD PRIMARY KEY (`id_zona`),
  ADD KEY `fk_tb_zonas_tb_contratos1_idx` (`id_codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_cargos`
--
ALTER TABLE `tb_cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_contactos`
--
ALTER TABLE `tb_contactos`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tb_departamentos`
--
ALTER TABLE `tb_departamentos`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_notas`
--
ALTER TABLE `tb_notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_rd_detalles`
--
ALTER TABLE `tb_rd_detalles`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_reportes_diarios`
--
ALTER TABLE `tb_reportes_diarios`
  MODIFY `id_reporte_diario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_reporte_turnos`
--
ALTER TABLE `tb_reporte_turnos`
  MODIFY `id_reporte_turno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_servicios_solicitados`
--
ALTER TABLE `tb_servicios_solicitados`
  MODIFY `id_servicio_solicitado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_tecnicos_instalacion`
--
ALTER TABLE `tb_tecnicos_instalacion`
  MODIFY `id_tecnico_instalacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_tecnicos_visitas`
--
ALTER TABLE `tb_tecnicos_visitas`
  MODIFY `id_tecnico_visita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tb_visitas`
--
ALTER TABLE `tb_visitas`
  MODIFY `id_visitas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_zonas`
--
ALTER TABLE `tb_zonas`
  MODIFY `id_zona` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_cargos`
--
ALTER TABLE `tb_cargos`
  ADD CONSTRAINT `fk_tb_tipos_personal_tb_departamentos1` FOREIGN KEY (`id_departamento`) REFERENCES `tb_departamentos` (`id_departamento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_contactos`
--
ALTER TABLE `tb_contactos`
  ADD CONSTRAINT `fk_tb_usuarios_tb_clientes1` FOREIGN KEY (`id_cliente`) REFERENCES `tb_clientes` (`identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_usuarios_tb_contratos1` FOREIGN KEY (`id_codigo`) REFERENCES `tb_mapa_zonas` (`id_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_mapa_zonas`
--
ALTER TABLE `tb_mapa_zonas`
  ADD CONSTRAINT `fk_tb_contrato_tb_clientes1` FOREIGN KEY (`id_cliente`) REFERENCES `tb_clientes` (`identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_mapa_zonas_tb_personal1` FOREIGN KEY (`cedula_asesor`) REFERENCES `tb_personal` (`cedula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_notas`
--
ALTER TABLE `tb_notas`
  ADD CONSTRAINT `fk_tb_tareas_tb_usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_personal`
--
ALTER TABLE `tb_personal`
  ADD CONSTRAINT `fk_tb_personal_tb_tipo_personal1` FOREIGN KEY (`id_cargo`) REFERENCES `tb_cargos` (`id_cargo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_rd_detalles`
--
ALTER TABLE `tb_rd_detalles`
  ADD CONSTRAINT `fk_tb_rd_detalles_tb_mapa_zonas1` FOREIGN KEY (`id_codigo`) REFERENCES `tb_mapa_zonas` (`id_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_rd_detalles_tb_reportes_diarios1` FOREIGN KEY (`id_reporte_diario`) REFERENCES `tb_reportes_diarios` (`id_reporte_diario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_reportes_diarios`
--
ALTER TABLE `tb_reportes_diarios`
  ADD CONSTRAINT `fk_tb_reportes_diarios_tb_personal1` FOREIGN KEY (`cedula`) REFERENCES `tb_personal` (`cedula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_reporte_turnos`
--
ALTER TABLE `tb_reporte_turnos`
  ADD CONSTRAINT `fk_tb_reporte_turnos_tb_reportes_diarios1` FOREIGN KEY (`id_reporte_diario`) REFERENCES `tb_reportes_diarios` (`id_reporte_diario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_servicios_solicitados`
--
ALTER TABLE `tb_servicios_solicitados`
  ADD CONSTRAINT `fk_tb_servicios_solicitados_tb_mapa_zonas1` FOREIGN KEY (`id_codigo`) REFERENCES `tb_mapa_zonas` (`id_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_tecnicos_instalacion`
--
ALTER TABLE `tb_tecnicos_instalacion`
  ADD CONSTRAINT `fk_tb_tecnicos_instalacion_tb_mapa_zonas1` FOREIGN KEY (`id_codigo`) REFERENCES `tb_mapa_zonas` (`id_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_tecnicos_tb_personal1` FOREIGN KEY (`cedula`) REFERENCES `tb_personal` (`cedula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_tecnicos_visitas`
--
ALTER TABLE `tb_tecnicos_visitas`
  ADD CONSTRAINT `fk_tb_tecnicos_visitas_tb_personal1` FOREIGN KEY (`cedula`) REFERENCES `tb_personal` (`cedula`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_tecnicos_visitas_tb_visitas_detalles1` FOREIGN KEY (`id_detalle`) REFERENCES `tb_visitas` (`id_visitas`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD CONSTRAINT `fk_tb_usuarios_tb_personal1` FOREIGN KEY (`cedula`) REFERENCES `tb_personal` (`cedula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_visitas`
--
ALTER TABLE `tb_visitas`
  ADD CONSTRAINT `fk_tb_visitas_detalles_tb_mapa_zonas1` FOREIGN KEY (`id_codigo`) REFERENCES `tb_mapa_zonas` (`id_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_visitas_detalles_tb_servicios_solicitados1` FOREIGN KEY (`id_servicio_solicitado`) REFERENCES `tb_servicios_solicitados` (`id_servicio_solicitado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_zonas`
--
ALTER TABLE `tb_zonas`
  ADD CONSTRAINT `fk_tb_zonas_tb_contratos1` FOREIGN KEY (`id_codigo`) REFERENCES `tb_mapa_zonas` (`id_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
