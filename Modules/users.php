<?php
// définition de la classe Users
class Users {
        private $iduser;
        private $nom;
        private $prenom;
		private $pseudo;
		private $dateinscri;
		private $nbrventes;
		private $mail;
		private $mdp;
		
        // contructeur
        public function __construct($donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['iduser'])) { $this->iduser = $donnees['iduser']; }
			if (isset($donnees['nom'])) { $this->nom = $donnees['nom']; }
			if (isset($donnees['prenom'])) { $this->prenom = $donnees['prenom']; }
			if (isset($donnees['pseudo'])) { $this->pseudo = $donnees['pseudo']; }
			if (isset($donnees['dateinscri'])) { $this->dateinscri = $donnees['dateinscri']; }
			if (isset($donnees['nbrventes'])) { $this->nbrventes = $donnees['nbrventes']; }
			if (isset($donnees['mail'])) { $this->mail = $donnees['mail']; }
			if (isset($donnees['mdp'])) { $this->mdp = $donnees['mdp']; }
        }           
        // GETTERS //
		public function getIdUser() { return $this->iduser;}
		public function getNom() { return $this->nom;}
		public function getPrenom() { return $this->prenom;}
		public function getPseudo() { return $this->pseudo;}
		public function getDateInscr() { return $this->dateinscri;}
		public function getNbrVentes() { return $this->nbrventes;}
		public function getMail() { return $this->mail;}
		public function getMdp() { return $this->mdp;}
		
		// SETTERS //
		public function setIdUser($iduser) { $this->iduser = $iduser; }
        public function setNom($nom) { $this->nom= $nom; }
		public function setPrenom($prenom) { $this->prenom = $prenom; }
		public function setPseudo($pseudo) { $this->pseudo = $pseudo; }
		public function setDateInscr($dateinscri) { $this->dateinscri = $dateinscri; }
		public function setNbrVentes($nbrventes) { $this->nbrventes = $nbrventes; }
		public function setMail($mail) { $this->mail = $mail; }
		public function setMdp($mdp) { $this->mdp = $mdp; }		

    }

?>