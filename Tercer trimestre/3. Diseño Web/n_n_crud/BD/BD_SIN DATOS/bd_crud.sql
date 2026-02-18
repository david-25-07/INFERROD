
CREATE SCHEMA telefonos;
USE telefonos ;

-- -----------------------------------------------------
-- Table User
-- -----------------------------------------------------
CREATE TABLE User (
  Id_user VARCHAR(15) NOT NULL,
  nombre_user VARCHAR(30) NOT NULL,
  apellido_user VARCHAR(30) NOT NULL,
  Direccion_user VARCHAR(45) NOT NULL,
  PRIMARY KEY (Id_user))
;


-- -----------------------------------------------------
-- Table Vendedor
-- -----------------------------------------------------
CREATE TABLE Vendedor (
  Id_vendedor VARCHAR(15) NOT NULL,
  Sueldo_user DOUBLE NOT NULL,
  PRIMARY KEY (Id_vendedor),
  CONSTRAINT fk_Vendedor_User
    FOREIGN KEY (Id_vendedor)
    REFERENCES User (Id_user)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
;


-- -----------------------------------------------------
-- Table Cliente
-- -----------------------------------------------------
CREATE TABLE Cliente (
  Id_cliente VARCHAR(15) NOT NULL,
  fech_nacimiento DATE NOT NULL,
  PRIMARY KEY (Id_cliente),
  CONSTRAINT fk_Cliente_User1
    FOREIGN KEY (Id_cliente)
    REFERENCES User (Id_user)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
;


-- -----------------------------------------------------
-- Table tipo_telefono
-- -----------------------------------------------------
CREATE TABLE tipo_telefono (
  tipo_tel VARCHAR(15) NOT NULL,
  estado_tipotel TINYINT(1) NOT NULL,
  PRIMARY KEY (tipo_tel))
;


-- -----------------------------------------------------
-- Table telefono_has_User
-- -----------------------------------------------------
CREATE TABLE telefono_has_User (
  tipotel VARCHAR(15) NOT NULL,
  Id_usu VARCHAR(15) NOT NULL,
  PRIMARY KEY (tipotel, Id_usu),
  INDEX fk_tipo_telefono_has_User_User1_idx (Id_usu ASC),
  INDEX fk_tipo_telefono_has_User_tipo_telefono1_idx (tipotel ASC),
  CONSTRAINT fk_tipo_telefono_has_User_tipo_telefono1
    FOREIGN KEY (tipotel)
    REFERENCES tipo_telefono (tipo_tel)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_tipo_telefono_has_User_User1
    FOREIGN KEY (Id_usu)
    REFERENCES User (Id_user)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
;
