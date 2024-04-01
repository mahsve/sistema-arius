-- MySQL Script generated by MySQL Workbench
-- Mon Apr  1 12:22:37 2024
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema arius_database
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema arius_database
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `arius_database` DEFAULT CHARACTER SET utf8 ;
USE `arius_database` ;

-- -----------------------------------------------------
-- Table `arius_database`.`tb_clientes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_clientes` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_clientes` (
  `identificacion` VARCHAR(12) NOT NULL,
  `tipo_identificacion` CHAR(1) NOT NULL COMMENT 'CÉDULA|RIF',
  `nombre` VARCHAR(120) NOT NULL,
  `telefono1` CHAR(14) NOT NULL,
  `telefono2` CHAR(14) NULL,
  `correo` VARCHAR(60) NULL,
  `direccion` VARCHAR(250) NOT NULL,
  `referencia` VARCHAR(250) NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`identificacion`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_departamentos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_departamentos` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_departamentos` (
  `iddepartamento` INT NOT NULL AUTO_INCREMENT,
  `departamento` VARCHAR(45) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT now(),
  `updated` DATETIME NOT NULL DEFAULT now(),
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`iddepartamento`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_cargos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_cargos` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_cargos` (
  `idcargo` INT NOT NULL AUTO_INCREMENT,
  `cargo` VARCHAR(60) NOT NULL,
  `iddepartamento` INT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT now(),
  `updated` DATETIME NOT NULL DEFAULT now(),
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`idcargo`),
  INDEX `fk_tb_cargos_tb_departamentos1_idx` (`iddepartamento` ASC),
  CONSTRAINT `fk_tb_cargos_tb_departamentos1`
    FOREIGN KEY (`iddepartamento`)
    REFERENCES `arius_database`.`tb_departamentos` (`iddepartamento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_personal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_personal` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_personal` (
  `cedula` CHAR(10) NOT NULL,
  `nombre` VARCHAR(120) NOT NULL,
  `telefono1` CHAR(14) NOT NULL,
  `telefono2` CHAR(14) NULL,
  `correo` VARCHAR(60) NOT NULL,
  `direccion` VARCHAR(250) NOT NULL,
  `referencia` VARCHAR(250) NULL,
  `idcargo` INT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT now(),
  `updated` DATETIME NOT NULL DEFAULT now(),
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`cedula`),
  INDEX `fk_tb_personal_tb_cargos1_idx` (`idcargo` ASC),
  CONSTRAINT `fk_tb_personal_tb_cargos1`
    FOREIGN KEY (`idcargo`)
    REFERENCES `arius_database`.`tb_cargos` (`idcargo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_mapa_zonas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_mapa_zonas` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_mapa_zonas` (
  `idcodigo` CHAR(4) NULL COMMENT '4 DIGITOS',
  `registro` DATE NOT NULL,
  `tipocontracto` CHAR(1) NOT NULL COMMENT 'HOGAR, OFICINA, EMPRESA, OTROS',
  `idcliente` VARCHAR(12) NOT NULL,
  `direccion` VARCHAR(250) NOT NULL,
  `referencia` VARCHAR(250) NULL,
  `panel_version` VARCHAR(120) NULL,
  `modelo_teclado` VARCHAR(120) NULL,
  `reporta_por` VARCHAR(120) NULL,
  `fecha_instalacion` DATE NULL,
  `fecha_entrega` DATE NULL,
  `cedula_asesor` CHAR(10) NOT NULL,
  `ubicacion_panel` VARCHAR(200) NULL,
  `particiones_sistema` VARCHAR(120) NULL,
  `imei` CHAR(15) NULL,
  `linea_principal` VARCHAR(20) NULL,
  `linea_respaldo` VARCHAR(20) NULL,
  `observaciones` VARCHAR(2000) NULL,
  `monitoreo` CHAR(1) NOT NULL DEFAULT 'N' COMMENT 'MONITOREO CONTRATADO',
  `estatus_monitoreo` CHAR(1) NOT NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  INDEX `fk_tb_contrato_tb_clientes1_idx` (`idcliente` ASC),
  PRIMARY KEY (`idcodigo`),
  INDEX `fk_tb_mapa_zonas_tb_personal1_idx` (`cedula_asesor` ASC),
  CONSTRAINT `fk_tb_contrato_tb_clientes1`
    FOREIGN KEY (`idcliente`)
    REFERENCES `arius_database`.`tb_clientes` (`identificacion`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tb_mapa_zonas_tb_personal1`
    FOREIGN KEY (`cedula_asesor`)
    REFERENCES `arius_database`.`tb_personal` (`cedula`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_servicios_solicitados`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_servicios_solicitados` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_servicios_solicitados` (
  `idsolicitud` INT NOT NULL AUTO_INCREMENT,
  `idcodigo` CHAR(4) NOT NULL,
  `fecha` DATE NOT NULL,
  `motivo` VARCHAR(2000) NOT NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`idsolicitud`),
  INDEX `fk_tb_servicios_solicitados_tb_mapa_zonas1_idx` (`idcodigo` ASC),
  CONSTRAINT `fk_tb_servicios_solicitados_tb_mapa_zonas1`
    FOREIGN KEY (`idcodigo`)
    REFERENCES `arius_database`.`tb_mapa_zonas` (`idcodigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_visitas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_visitas` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_visitas` (
  `idvisita` INT NOT NULL AUTO_INCREMENT,
  `idcodigo` CHAR(4) NOT NULL,
  `idsolicitud` INT NULL,
  `fecha` DATE NOT NULL,
  `servicioprestado` VARCHAR(2000) NOT NULL,
  `pendientes` VARCHAR(250) NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`idvisita`),
  INDEX `fk_tb_visitas_detalles_tb_mapa_zonas1_idx` (`idcodigo` ASC),
  INDEX `fk_tb_visitas_detalles_tb_servicios_solicitados1_idx` (`idsolicitud` ASC),
  CONSTRAINT `fk_tb_visitas_detalles_tb_mapa_zonas1`
    FOREIGN KEY (`idcodigo`)
    REFERENCES `arius_database`.`tb_mapa_zonas` (`idcodigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tb_visitas_detalles_tb_servicios_solicitados1`
    FOREIGN KEY (`idsolicitud`)
    REFERENCES `arius_database`.`tb_servicios_solicitados` (`idsolicitud`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_contactos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_contactos` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_contactos` (
  `idcontacto` INT NOT NULL AUTO_INCREMENT,
  `idcliente` VARCHAR(11) NOT NULL,
  `contrasena` VARCHAR(255) NULL,
  `nota` VARCHAR(250) NULL,
  `idcodigo` CHAR(4) NOT NULL,
  PRIMARY KEY (`idcontacto`),
  INDEX `fk_tb_usuarios_tb_clientes1_idx` (`idcliente` ASC),
  INDEX `fk_tb_usuarios_tb_contratos1_idx` (`idcodigo` ASC),
  CONSTRAINT `fk_tb_usuarios_tb_clientes1`
    FOREIGN KEY (`idcliente`)
    REFERENCES `arius_database`.`tb_clientes` (`identificacion`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tb_usuarios_tb_contratos1`
    FOREIGN KEY (`idcodigo`)
    REFERENCES `arius_database`.`tb_mapa_zonas` (`idcodigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_dispositivos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_dispositivos` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_dispositivos` (
  `iddispositivo` INT NOT NULL AUTO_INCREMENT,
  `dispositivo` VARCHAR(60) NOT NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`iddispositivo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_config_disp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_config_disp` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_config_disp` (
  `idconfiguracion` INT NOT NULL AUTO_INCREMENT,
  `iddispositivo` INT NOT NULL,
  `configuracion` VARCHAR(45) NOT NULL,
  `descripcion` VARCHAR(200) NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`idconfiguracion`),
  INDEX `fk_tb_configuracion_tb_dispositivos1_idx` (`iddispositivo` ASC),
  CONSTRAINT `fk_tb_configuracion_tb_dispositivos1`
    FOREIGN KEY (`iddispositivo`)
    REFERENCES `arius_database`.`tb_dispositivos` (`iddispositivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_zonas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_zonas` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_zonas` (
  `idzona` INT NOT NULL AUTO_INCREMENT,
  `zona` VARCHAR(250) NOT NULL,
  `iddispositivo` INT NOT NULL,
  `idconfiguracion` INT NULL,
  `nota` VARCHAR(250) NULL,
  `idcodigo` CHAR(4) NOT NULL,
  PRIMARY KEY (`idzona`),
  INDEX `fk_tb_zonas_tb_contratos1_idx` (`idcodigo` ASC),
  INDEX `fk_tb_zonas_tb_configuracion1_idx` (`idconfiguracion` ASC),
  INDEX `fk_tb_zonas_tb_dispositivos1_idx` (`iddispositivo` ASC),
  CONSTRAINT `fk_tb_zonas_tb_contratos1`
    FOREIGN KEY (`idcodigo`)
    REFERENCES `arius_database`.`tb_mapa_zonas` (`idcodigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tb_zonas_tb_configuracion1`
    FOREIGN KEY (`idconfiguracion`)
    REFERENCES `arius_database`.`tb_config_disp` (`idconfiguracion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tb_zonas_tb_dispositivos1`
    FOREIGN KEY (`iddispositivo`)
    REFERENCES `arius_database`.`tb_dispositivos` (`iddispositivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_instalacion_tecnicos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_instalacion_tecnicos` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_instalacion_tecnicos` (
  `iddetalle` INT NOT NULL AUTO_INCREMENT,
  `cedula` CHAR(10) NOT NULL,
  `idcodigo` CHAR(4) NOT NULL,
  INDEX `fk_tb_tecnicos_tb_personal1_idx` (`cedula` ASC),
  PRIMARY KEY (`iddetalle`),
  INDEX `fk_tb_tecnicos_instalacion_tb_mapa_zonas1_idx` (`idcodigo` ASC),
  CONSTRAINT `fk_tb_tecnicos_tb_personal1`
    FOREIGN KEY (`cedula`)
    REFERENCES `arius_database`.`tb_personal` (`cedula`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tb_tecnicos_instalacion_tb_mapa_zonas1`
    FOREIGN KEY (`idcodigo`)
    REFERENCES `arius_database`.`tb_mapa_zonas` (`idcodigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_visitas_tecnicos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_visitas_tecnicos` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_visitas_tecnicos` (
  `iddetalle` INT NOT NULL AUTO_INCREMENT,
  `cedula` CHAR(10) NOT NULL,
  `idvisita` INT NOT NULL,
  INDEX `fk_tb_tecnicos_visitas_tb_personal1_idx` (`cedula` ASC),
  PRIMARY KEY (`iddetalle`),
  INDEX `fk_tb_tecnicos_visitas_tb_visitas_detalles1_idx` (`idvisita` ASC),
  CONSTRAINT `fk_tb_tecnicos_visitas_tb_personal1`
    FOREIGN KEY (`cedula`)
    REFERENCES `arius_database`.`tb_personal` (`cedula`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tb_tecnicos_visitas_tb_visitas_detalles1`
    FOREIGN KEY (`idvisita`)
    REFERENCES `arius_database`.`tb_visitas` (`idvisita`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_roles` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_roles` (
  `idrol` INT NOT NULL AUTO_INCREMENT,
  `rol` VARCHAR(60) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT now(),
  `updated` DATETIME NOT NULL DEFAULT now(),
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`idrol`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_usuarios` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_usuarios` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  `cedula` CHAR(10) NOT NULL,
  `usuario` VARCHAR(30) NOT NULL,
  `contrasena` VARCHAR(255) NOT NULL,
  `idrol` INT NOT NULL,
  `pregunta1` VARCHAR(60) NULL,
  `respuesta1` VARCHAR(255) NULL,
  `pregunta2` VARCHAR(60) NULL,
  `respuesta2` VARCHAR(255) NULL,
  `created` DATETIME NOT NULL DEFAULT now(),
  `updated` DATETIME NOT NULL DEFAULT now(),
  `estatus` CHAR(1) NOT NULL DEFAULT 'P',
  PRIMARY KEY (`idusuario`),
  INDEX `fk_tb_usuarios_tb_personal1_idx` (`cedula` ASC),
  INDEX `fk_tb_usuarios_tb_roles1_idx` (`idrol` ASC),
  CONSTRAINT `fk_tb_usuarios_tb_personal1`
    FOREIGN KEY (`cedula`)
    REFERENCES `arius_database`.`tb_personal` (`cedula`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tb_usuarios_tb_roles1`
    FOREIGN KEY (`idrol`)
    REFERENCES `arius_database`.`tb_roles` (`idrol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_reportes_diarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_reportes_diarios` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_reportes_diarios` (
  `idreporte` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `cedula` CHAR(10) NOT NULL,
  `turno` VARCHAR(20) NOT NULL,
  `ap_sin_clo` VARCHAR(45) NOT NULL,
  `ap_con_clo` VARCHAR(45) NOT NULL,
  `eventualidad_emp` VARCHAR(45) NOT NULL,
  `informado_por` VARCHAR(45) NOT NULL,
  `activaciones` VARCHAR(200) NOT NULL,
  `servidormensajeria` VARCHAR(250) NULL,
  `radio` VARCHAR(45) NULL,
  `lineasreportes` VARCHAR(45) NULL,
  `observaciones` VARCHAR(2000) NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`idreporte`),
  INDEX `fk_tb_reportes_diarios_tb_personal1_idx` (`cedula` ASC),
  CONSTRAINT `fk_tb_reportes_diarios_tb_personal1`
    FOREIGN KEY (`cedula`)
    REFERENCES `arius_database`.`tb_personal` (`cedula`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_reportes_detalles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_reportes_detalles` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_reportes_detalles` (
  `iddetalle` INT NOT NULL AUTO_INCREMENT,
  `idreporte` INT NOT NULL,
  `idcodigo` CHAR(4) NOT NULL,
  `hora` TIME NOT NULL,
  `evento` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`iddetalle`),
  INDEX `fk_tb_rd_detalles_tb_reportes_diarios1_idx` (`idreporte` ASC),
  INDEX `fk_tb_rd_detalles_tb_mapa_zonas1_idx` (`idcodigo` ASC),
  CONSTRAINT `fk_tb_rd_detalles_tb_reportes_diarios1`
    FOREIGN KEY (`idreporte`)
    REFERENCES `arius_database`.`tb_reportes_diarios` (`idreporte`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tb_rd_detalles_tb_mapa_zonas1`
    FOREIGN KEY (`idcodigo`)
    REFERENCES `arius_database`.`tb_mapa_zonas` (`idcodigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_notas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_notas` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_notas` (
  `idnota` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(60) NOT NULL,
  `descripcion` VARCHAR(200) NOT NULL,
  `idusuario` INT NOT NULL,
  `fechatope` DATETIME NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'P' COMMENT 'PENDIENTE, COMPLETADO',
  PRIMARY KEY (`idnota`),
  INDEX `fk_tb_tareas_tb_usuarios1_idx` (`idusuario` ASC),
  CONSTRAINT `fk_tb_tareas_tb_usuarios1`
    FOREIGN KEY (`idusuario`)
    REFERENCES `arius_database`.`tb_usuarios` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_horarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_horarios` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_horarios` (
  `idhorario` INT NOT NULL AUTO_INCREMENT,
  `marcados` VARCHAR(45) NOT NULL,
  `horainicio` CHAR(8) NOT NULL,
  `horafinal` CHAR(8) NOT NULL,
  `idcodigo` CHAR(4) NOT NULL,
  PRIMARY KEY (`idhorario`),
  INDEX `fk_Horario_tb_mapa_zonas1_idx` (`idcodigo` ASC),
  CONSTRAINT `fk_tb_horario_tb_mapa_zonas1`
    FOREIGN KEY (`idcodigo`)
    REFERENCES `arius_database`.`tb_mapa_zonas` (`idcodigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_modulos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_modulos` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_modulos` (
  `idmodulo` INT NOT NULL AUTO_INCREMENT,
  `modulo` VARCHAR(60) NOT NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`idmodulo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_servicios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_servicios` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_servicios` (
  `idservicio` INT NOT NULL AUTO_INCREMENT,
  `servicio` VARCHAR(60) NOT NULL,
  `enlace` VARCHAR(45) NOT NULL,
  `visible` TINYINT NOT NULL DEFAULT 1,
  `idmodulo` INT NOT NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`idservicio`),
  INDEX `fk_tb_servicios_tb_modulos1_idx` (`idmodulo` ASC),
  CONSTRAINT `fk_tb_servicios_tb_modulos1`
    FOREIGN KEY (`idmodulo`)
    REFERENCES `arius_database`.`tb_modulos` (`idmodulo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_rol_modulo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_rol_modulo` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_rol_modulo` (
  `idrol` INT NOT NULL,
  `idmodulo` INT NOT NULL,
  INDEX `fk_tb_modulo_detalle_tb_modulos1_idx` (`idmodulo` ASC),
  INDEX `fk_tb_modulo_detalle_tb_roles1_idx` (`idrol` ASC),
  CONSTRAINT `fk_tb_modulo_detalle_tb_modulos1`
    FOREIGN KEY (`idmodulo`)
    REFERENCES `arius_database`.`tb_modulos` (`idmodulo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tb_modulo_detalle_tb_roles1`
    FOREIGN KEY (`idrol`)
    REFERENCES `arius_database`.`tb_roles` (`idrol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_bitacora`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_bitacora` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_bitacora` (
  `idbitacora` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `ip` VARCHAR(15) NOT NULL,
  `operacion` CHAR(1) NOT NULL,
  `descripcion` VARCHAR(600) NOT NULL,
  `idusuario` INT NOT NULL,
  PRIMARY KEY (`idbitacora`),
  INDEX `fk_tb_bitacora_tb_usuarios1_idx` (`idusuario` ASC),
  CONSTRAINT `fk_tb_bitacora_tb_usuarios1`
    FOREIGN KEY (`idusuario`)
    REFERENCES `arius_database`.`tb_usuarios` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arius_database`.`tb_rol_servicio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arius_database`.`tb_rol_servicio` ;

CREATE TABLE IF NOT EXISTS `arius_database`.`tb_rol_servicio` (
  `idrol` INT NOT NULL,
  `idservicio` INT NOT NULL,
  INDEX `fk_tb_rol_servicio_tb_roles1_idx` (`idrol` ASC),
  INDEX `fk_tb_rol_servicio_tb_servicios1_idx` (`idservicio` ASC),
  CONSTRAINT `fk_tb_rol_servicio_tb_roles1`
    FOREIGN KEY (`idrol`)
    REFERENCES `arius_database`.`tb_roles` (`idrol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tb_rol_servicio_tb_servicios1`
    FOREIGN KEY (`idservicio`)
    REFERENCES `arius_database`.`tb_servicios` (`idservicio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `arius_database`.`tb_departamentos`
-- -----------------------------------------------------
START TRANSACTION;
USE `arius_database`;
INSERT INTO `arius_database`.`tb_departamentos` (`iddepartamento`, `departamento`, `created`, `updated`, `estatus`) VALUES (1, 'GENERENCIA', DEFAULT, DEFAULT, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `arius_database`.`tb_cargos`
-- -----------------------------------------------------
START TRANSACTION;
USE `arius_database`;
INSERT INTO `arius_database`.`tb_cargos` (`idcargo`, `cargo`, `iddepartamento`, `created`, `updated`, `estatus`) VALUES (1, 'GERENTE GENERAL', 1, DEFAULT, DEFAULT, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `arius_database`.`tb_personal`
-- -----------------------------------------------------
START TRANSACTION;
USE `arius_database`;
INSERT INTO `arius_database`.`tb_personal` (`cedula`, `nombre`, `telefono1`, `telefono2`, `correo`, `direccion`, `referencia`, `idcargo`, `created`, `updated`, `estatus`) VALUES ('V-25791966', 'MIGUEL HERRERA', '(424) 507-1156', NULL, 'MIGUELSOT959@GMAIL.COM', '_', NULL, 1, DEFAULT, DEFAULT, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `arius_database`.`tb_roles`
-- -----------------------------------------------------
START TRANSACTION;
USE `arius_database`;
INSERT INTO `arius_database`.`tb_roles` (`idrol`, `rol`, `created`, `updated`, `estatus`) VALUES (1, 'WEBMASTER', DEFAULT, DEFAULT, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `arius_database`.`tb_usuarios`
-- -----------------------------------------------------
START TRANSACTION;
USE `arius_database`;
INSERT INTO `arius_database`.`tb_usuarios` (`idusuario`, `cedula`, `usuario`, `contrasena`, `idrol`, `pregunta1`, `respuesta1`, `pregunta2`, `respuesta2`, `created`, `updated`, `estatus`) VALUES (1, 'V-25791966', 'admin', '$2y$10$49nb7Pm3SQUjN.sVuFqD8uGprcI1whJLXV/Z5iFhEvkqgRYw2Z9/6', 1, '-', '-', '-', '-', DEFAULT, DEFAULT, DEFAULT);

COMMIT;

