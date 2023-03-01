-- MySQL Script generated by MySQL Workbench
-- 03/03/22 17:30:30
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema ferreteria
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ferreteria
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ferreteria` DEFAULT CHARACTER SET utf8mb4 ;
USE `ferreteria` ;

-- -----------------------------------------------------
-- Table `ferreteria`.`clasificacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`clasificacion` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`clasificacion` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`persona`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`persona` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`persona` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipodocumento` ENUM('Cedula', 'Nit') NOT NULL,
  `documento` VARCHAR(15) NOT NULL,
  `nombre` VARCHAR(75) NOT NULL,
  `apellido` VARCHAR(75) NOT NULL,
  `telefono` VARCHAR(75) NULL DEFAULT NULL,
  `correo` VARCHAR(75) NULL DEFAULT NULL,
  `contrasena` VARCHAR(75) NULL DEFAULT NULL,
  `rol` ENUM('administrador', 'empleado', 'cliente', 'proveedor') NOT NULL,
  `estado` ENUM('activo', 'inactivo') NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`facturacompra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`facturacompra` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`facturacompra` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `monto` VARCHAR(75) NOT NULL,
  `proveedor_id` INT(11) UNSIGNED NOT NULL,
  `estado` ENUM('Proceso','Finalizada','Anulada') NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_compra_persona1_idx` (`proveedor_id` ASC),
  CONSTRAINT `fk_compra_persona1`
    FOREIGN KEY (`proveedor_id`)
    REFERENCES `ferreteria`.`persona` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`medida`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`medida` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`medida` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`marca`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`marca` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`marca` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(75) NOT NULL,
  `estado` ENUM('activo', 'inactivo') NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`material`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`material` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`material` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`producto` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`producto` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(75) NOT NULL,
  `stock` SMALLINT(6) NOT NULL,
  `precio` DOUBLE NOT NULL,
  `porcentaje_ganancia` FLOAT UNSIGNED NOT NULL,
  `clasificacion_id` INT(11) UNSIGNED NOT NULL,
  `estado` ENUM('activo', 'inactivo') NOT NULL,
  `medida_id` INT(11) UNSIGNED NULL,
  `marca_id` INT(11) UNSIGNED NOT NULL,
  `material_id` INT(11) UNSIGNED  NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_producto_clasificacion1_idx` (`clasificacion_id` ASC),
  INDEX `fk_producto_medida1_idx` (`medida_id` ASC),
  INDEX `fk_producto_marca1_idx` (`marca_id` ASC),
  INDEX `fk_producto_material1_idx` (`material_id` ASC),
  CONSTRAINT `fk_producto_clasificacion1`
    FOREIGN KEY (`clasificacion_id`)
    REFERENCES `ferreteria`.`clasificacion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_medida1`
    FOREIGN KEY (`medida_id`)
    REFERENCES `ferreteria`.`medida` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_marca1`
    FOREIGN KEY (`marca_id`)
    REFERENCES `ferreteria`.`marca` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_material1`
    FOREIGN KEY (`material_id`)
    REFERENCES `ferreteria`.`material` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`detallecompra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`detallecompra` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`detallecompra` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cantidad` DOUBLE NOT NULL,
  `valor` DOUBLE NOT NULL,
  `compra_id` INT(11) UNSIGNED NOT NULL,
  `producto_id` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_detallecompra_compra1_idx` (`compra_id` ASC),
  INDEX `fk_detallecompra_producto1_idx` (`producto_id` ASC),
  CONSTRAINT `fk_detallecompra_compra1`
    FOREIGN KEY (`compra_id`)
    REFERENCES `ferreteria`.`facturacompra` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detallecompra_producto1`
    FOREIGN KEY (`producto_id`)
    REFERENCES `ferreteria`.`producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`facturaventa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`facturaventa` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`facturaventa` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `monto` VARCHAR(75) NOT NULL,
  `cliente_id` INT(11) UNSIGNED NOT NULL,
  `estado` ENUM('Proceso', 'Finalizada', 'Anulada') NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_facturaventa_persona1_idx` (`cliente_id` ASC),
  CONSTRAINT `fk_facturaventa_persona1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `ferreteria`.`persona` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`detalleventa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`detalleventa` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`detalleventa` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cantidad` DOUBLE NOT NULL,
  `precio_venta` DOUBLE NOT NULL,
  `facturaventa_id` INT(11) UNSIGNED NOT NULL,
  `producto_id` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_detalleventa_facturaventa1_idx` (`facturaventa_id` ASC),
  INDEX `fk_detalleventa_producto1_idx` (`producto_id` ASC),
  CONSTRAINT `fk_detalleventa_facturaventa1`
    FOREIGN KEY (`facturaventa_id`)
    REFERENCES `ferreteria`.`facturaventa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleventa_producto1`
    FOREIGN KEY (`producto_id`)
    REFERENCES `ferreteria`.`producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `ferreteria`.`table1`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`table1` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`table1` (
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ferreteria`.`table2`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ferreteria`.`table2` ;

CREATE TABLE IF NOT EXISTS `ferreteria`.`table2` (
)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
