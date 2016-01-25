<?php
require_once("connect.php");
require_once("Modules/commentaire.php");
// Définition d'une classe permettant de gérer les commentaires 
//   en relation avec la base de données	
class CommentaireManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
		// Constructeur = initialisation de la connexion vers le SGBD
        public function __construct($db) {
            $this->_db=$db;
        }
        // ajout d'un commentaire dans la BD
        public function add(Commentaire $commentaire) {
       }
        // nombre de commentaires dans la base de données
        public function count() {
        }
        // suppression d'un commentaire dans la base de données
        public function delete(Commentaire $commentaire) {
        }
		// recherche les commentaires d'un covoitureur à partir de son id
		public function getCovoitureur($idmembrecovoitureur) {
		}	
		// recherche les commentaires d'un conducteur à partir de son id
		public function getConducteur($idmembreconducteur) {
		}	
		// retourne l'ensemble des commentaires présents dans la BD 
        public function getList() {
        }
        // modification d'un commentaire dans la BD 
        public function update(Commentaire $commentaire) {
         }
    }
?>