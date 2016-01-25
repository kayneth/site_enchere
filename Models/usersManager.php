<?php
require_once("connect.php");
require_once("Modules/users.php");
// Définition d'une classe permettant de gérer les membres 
//   en relation avec la base de données	
class UserManager
    {
        private $db; // Instance de PDO - objet de connexion au SGBD
        
		// Constructeur = initialisation de la connexion vers le SGBD
        public function __construct($db) {
            $this->db=$db;
        }
        // ajout d'un user dans la BD
        public function add(Users $user) {
			// calcul d'un nouveau code de user non déja utilisé = Maximum + 1
			$user->setIdUser($this->db->query("SELECT MAX(IDUSER) AS MAXIMUM FROM utilisateur")->fetchColumn()+1);
			$mdp = sha1($user->getMdp());
			
			// requete d'ajout dans la BD
			$req = "INSERT INTO utilisateur (iduser,nom,prenom,pseudo,dateinscri,nbrventes,mail,mdp) VALUES(:iduser, :nom, :prenom, :pseudo, NOW(), 0, :mail, :mdp)";
			$add = $this->db->prepare($req);
			$added = $add->execute(array(
					"iduser" => $user->getIdUser(),
					"nom" => $user->getNom(),
					"prenom" => $user->getPrenom(),
					"pseudo" => $user->getPseudo(),
					"mail" => $user->getMail(),
					"mdp" => $mdp
				));
			return $added;
		}
        // nombre de membres dans la base de données
        public function count() {
			return $this->db->query('SELECT COUNT(*) FROM utilisateur')->fetchColumn();
        }
        // suppression d'un user dans la base de données
        public function delete(Users $user) {
			return $this->db->exec('DELETE FROM utilisateur WHERE iduser = '.$user->getIdUser());
        }
		// recherche dans la BD d'un membre à partir de son id
		public function get($iduser) {
			$q = $this->db->prepare('SELECT iduser,nom,prenom,pseudo,date_format(dateinscri,"%d/%c/%Y") as dateinscri,nbrventes,mail,mdp FROM utilisateur WHERE iduser = ?');
			$q->execute(array($iduser));
			$user = new Users($q->fetch());
			return $user;
		}		
		// retourne l'ensemble des users présents dans la BD 
        public function getList() {
			$users = array();  
            $q = $this->db->query('SELECT iduser,nom,prenom,pseudo,dateinscri,nbrventes,mail,mdp FROM utilisateur');
            while ($donnees = $q->fetch())
            {
                $users[] = new Users($donnees);
            }
            return $users;
        }
        // modification d'un user dans la BD 
        public function update(Users $user) {
        	$modif = $this->db->prepare("UPDATE utilisateur SET nom = :nom, prenom = :prenom, pseudo= :pseudo, mail = :mail WHERE iduser = :iduser");
        	$ok = $modif->execute(array(
        			"iduser" => $user->getIdUser(),
					"nom" => $user->getNom(),
					"prenom" => $user->getPrenom(),
					"pseudo" => $user->getPseudo(),
					"mail" => $user->getMail()
        		));

        	return $ok;
        }
		// verification de l'identité d'un user (pseudo/mdp)
		public function verif_identification($pseudo, $mdp) {
			$mdp = sha1($mdp);
			$query = "SELECT iduser,nom,prenom FROM utilisateur WHERE pseudo = ? AND mdp = ?";
			$q = $this->db->prepare($query);
			$q->execute(array($pseudo, $mdp));
			if($donnees = $q->fetch()){
				$user = new Users($donnees);
				return $user;
			}
			else{
				return false;
			}
		}
		// verification de l'identité d'un user ADMIN du site
		public function verif_identification_admin($login, $mdp) {
		}
    }
?>