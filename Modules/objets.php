<?php
// définition de la classe Objets
class Objet {
        private $idobj;   
		private $idcategorie;
        private $iduser;
        private $nom;
		private $prixini;
		private $datedebut;
		private $datefin;
		private $description;
		private $photo;
        private $fin;
        private $prix;
		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
            if(isset($donnees['idobj'])) $this->idobj = $donnees['idobj'];
            if(isset($donnees['idcategorie'])) $this->idcategorie = $donnees['idcategorie'];
            if(isset($donnees['iduser'])) $this->iduser = $donnees['iduser'];
            if(isset($donnees['nom'])) $this->nom = $donnees['nom'];
            if(isset($donnees['prixini'])) $this->prixini = $donnees['prixini'];
            if(isset($donnees['datedebut'])) $this->datedebut = $donnees['datedebut'];
            if(isset($donnees['datefin'])) $this->datefin = $donnees['datefin'];
            if(isset($donnees['description'])) $this->description = $donnees['description'];
            if(isset($donnees['photo'])) $this->photo = $donnees['photo'];
            if(isset($donnees['timefin'])) $this->fin = $donnees['timefin'];
            if(isset($donnees['prix'])) $this->prix = $donnees['prix'];

        }           
        // GETTERS //
        public function getIdObj(){
            return $this->idobj;
        }
        public function getIdCategorie(){
            return $this->idcategorie;
        }
        public function getIduser(){
            return $this->iduser;
        }
        public function getNom(){
            return $this->nom;
        }
        public function getPrixIni(){
            return $this->prixini;
        }
        public function getDateDebut(){
            return $this->datedebut;
        }
        public function getDateFin(){
            return $this->datefin;
        }
        public function getDescription(){
            return $this->description;
        }
        public function getPhoto(){
            return $this->photo;
        }
        public function getPrix(){
            return $this->prix;
        }
        /*public function getTpsRest(){
            $fin = $this->fin;
            $now = time();
            $tps = $fin - $now;
            echo $fin .'<br>';
            $jours = round($tps / (60 * 60 * 24)); 
            $heures = round(($tps - ($jours * 60 * 60 * 24)) / (60 * 60));
            $minutes = round(($tps - (($jours * 60 * 60 * 24 + $heures * 60 * 60))) / 60);
            $secondes = round($tps - (($jours * 60 * 60 * 24 + $heures * 60 * 60 + $minutes * 60)));
            $tp = "Il reste " . $jours . " jours " . $heures . "h".$minutes."min".$secondes."s";
            return $tp;
        }*/


		// SETTERS //
	   public function setIdObj($idobj){
            $this->idobj = $idobj;
        }
        public function setIdCategorie($idcategorie){
            $this->idcategorie = $idcategorie;
        }
        public function setIduser($iduser){
            $this->iduser = $iduser;
        }
        public function setNom($nom){
            $this->nom = $nom;
        }
        public function setPrixIni($prixini){
            $this->prixini = $prixini;
        }
        public function setDateDebut($datedebut){
            $this->datedebut = $datedebut;
        }
        public function setDateFin($datefin){
            $this->datefin = $datefin;
        }
        public function setDescription($description){
            $this->description = $description;
        }
        public function setPhoto($photo){
            $this->photo = $photo;
        }
        public function setPrix($prix){
            $this->prix = $prix;
        }
    }
?>