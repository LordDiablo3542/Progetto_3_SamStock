use samtstock;

CREATE TABLE samtstock.categorie_padre (
  ID_categoria_padre INT NOT NULL,
  NomeCP VARCHAR(45) NOT NULL,
  PRIMARY KEY (id));
  
ALTER TABLE samtstock.categorie 
ADD COLUMN Categoria_padre INT NOT NULL AFTER NomeC;

ALTER TABLE samtstock.categorie 
ADD INDEX Categoria_padre_idx (Categoria_padre ASC);
ALTER TABLE samtstock.categorie 
ADD CONSTRAINT Categoria_padre
  FOREIGN KEY (Categoria_padre)
  REFERENCES samtstock.categorie_padre(ID_categoria_padre)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;