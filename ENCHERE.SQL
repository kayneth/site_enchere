
# -----------------------------------------------------------------------------
#       TABLE : ENCHERE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS ENCHERE
 (
   IDENCHERE INTEGER NOT NULL  ,
   IDOBJ INTEGER NOT NULL  ,
   IDUSER INTEGER NOT NULL  ,
   SOMME DECIMAL(13,2) NOT NULL  ,
   DATEENCHERE DATETIME NOT NULL  
   , PRIMARY KEY (IDENCHERE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE ENCHERE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_ENCHERE_OBJETS
     ON ENCHERE (IDOBJ ASC);

CREATE  INDEX I_FK_ENCHERE_UTILISATEUR
     ON ENCHERE (IDUSER ASC);

# -----------------------------------------------------------------------------
#       TABLE : UTILISATEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS UTILISATEUR
 (
   IDUSER INTEGER NOT NULL  ,
   NOM VARCHAR(128) NOT NULL  ,
   PRENOM VARCHAR(128) NOT NULL  ,
   PSEUDO VARCHAR(128) NOT NULL  ,
   DATEINSCRI DATE NOT NULL  ,
   NBRVENTES INTEGER NULL  ,
   MAIL VARCHAR(128) NOT NULL  ,
   MDP VARCHAR(128) NOT NULL  
   , PRIMARY KEY (IDUSER) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CATEGORIE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATEGORIE
 (
   IDCATEGORIE INTEGER NOT NULL  ,
   NOM VARCHAR(128) NOT NULL  
   , PRIMARY KEY (IDCATEGORIE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : EVALUATION
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EVALUATION
 (
   IDEVAL INTEGER NOT NULL  ,
   IDEVALUATEUR INTEGER NOT NULL  ,
   IDUSER_EVALUER INTEGER NOT NULL  ,
   NOTE INTEGER NOT NULL  ,
   COMMENTAIRE VARCHAR(128) NULL  ,
   DATEEVAL DATETIME NOT NULL  
   , PRIMARY KEY (IDEVAL) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE EVALUATION
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_EVALUATION_UTILISATEUR
     ON EVALUATION (IDEVALUATEUR ASC);

CREATE  INDEX I_FK_EVALUATION_UTILISATEUR2
     ON EVALUATION (IDUSER_EVALUER ASC);

# -----------------------------------------------------------------------------
#       TABLE : OBJETS
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS OBJETS
 (
   IDOBJ INTEGER NOT NULL  ,
   IDCATEGORIE INTEGER NOT NULL  ,
   IDUSER INTEGER NOT NULL  ,
   NOM VARCHAR(128) NOT NULL  ,
   PRIXINI DECIMAL(13,2) NOT NULL  ,
   DATEDEBUT DATE NOT NULL  ,
   DATEFIN DATETIME NULL  ,
   DESCRIPTION VARCHAR(255) NULL  ,
   PHOTO LONGBLOB NULL  
   , PRIMARY KEY (IDOBJ) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE OBJETS
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_OBJETS_CATEGORIE
     ON OBJETS (IDCATEGORIE ASC);

CREATE  INDEX I_FK_OBJETS_UTILISATEUR
     ON OBJETS (IDUSER ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE ENCHERE 
  ADD FOREIGN KEY FK_ENCHERE_OBJETS (IDOBJ)
      REFERENCES OBJETS (IDOBJ) ;


ALTER TABLE ENCHERE 
  ADD FOREIGN KEY FK_ENCHERE_UTILISATEUR (IDUSER)
      REFERENCES UTILISATEUR (IDUSER) ;


ALTER TABLE EVALUATION 
  ADD FOREIGN KEY FK_EVALUATION_UTILISATEUR (IDEVALUATEUR)
      REFERENCES UTILISATEUR (IDUSER) ;


ALTER TABLE EVALUATION 
  ADD FOREIGN KEY FK_EVALUATION_UTILISATEUR2 (IDUSER_EVALUER)
      REFERENCES UTILISATEUR (IDUSER) ;


ALTER TABLE OBJETS 
  ADD FOREIGN KEY FK_OBJETS_CATEGORIE (IDCATEGORIE)
      REFERENCES CATEGORIE (IDCATEGORIE) ;


ALTER TABLE OBJETS 
  ADD FOREIGN KEY FK_OBJETS_UTILISATEUR (IDUSER)
      REFERENCES UTILISATEUR (IDUSER) ;

