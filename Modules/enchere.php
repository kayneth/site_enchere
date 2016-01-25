<?php
//définit mes enchères
class Enchere {
	private $idenchere;
	private $idobj;
	private $iduser;
	private $somme;
	private $dateenchere;
		
		public function __construct($donnees)
		{	
			if(isset($donnees['idenchere'])) $this->idenchere = $donnees['idenchere'];
			if(isset($donnees['idobj'])) $this->idobj = $donnees['idobj'];
			if(isset($donnees['iduser'])) $this->iduser = $donnees['iduser'];
			if(isset($donnees['somme'])) $this->somme = $donnees['somme'];
			if(isset($donnees['dateenchere'])) $this->dateenchere = $donnees['dateenchere'];
		}

		//GETTERS
		public function getIdEnchere(){
			return $this->idenchere;
		}
		public function getIdObj(){
			return $this->idobj;
		}
		public function getIdUser(){
			return $this->iduser;
		}
		public function getSomme(){
			return $this->somme;
		}
		public function getDateEnchere(){
			return $this->dateenchere;
		}
	
		//SETTERS
		public function setIdEnchere($idenchere){
			$this->idenchere = $idenchere;
		}
		public function setIdObj($idobj){
			$this->idobj = $idobj;
		}
		public function setIdUser($iduser){
			$this->iduser = $iduser;
		}
		public function setSomme($somme){
			$this->somme = $somme;
		}
		public function setDateEnchere($dateenchere){
			$this->dateenchere = $dateenchere;
		}
}
?>
