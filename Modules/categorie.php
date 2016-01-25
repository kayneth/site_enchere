<?php
// définition de la classe Categories
class Categorie {
        private $idcategorie;   
		private $nom;
		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
            if(isset($donnees['idcategorie'])) $this->idcategorie = $donnees['idcategorie'];
            if(isset($donnees['nom'])) $this->nom = $donnees['nom'];
        }           
        // GETTERS //
        public function getIdCategorie(){
            return $this->idcategorie;
        }
        public function getNom(){
            return $this->nom;
        }
        
		// SETTERS //
        public function setIdCategorie($idcategorie){
            $this->idcategorie = $idcategorie;
        }
	   public function setNom($nom){
            $this->nom = $nom;
        }
    }
?>