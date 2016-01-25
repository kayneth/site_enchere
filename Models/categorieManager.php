<?php
require_once("connect.php");
require_once("Modules/categorie.php");
// Définition d'une classe permettant de gérer les membres 
//   en relation avec la base de données	
class CategorieManager
    {
        private $db; // Instance de PDO - objet de connexion au SGBD
        
		// Constructeur = initialisation de la connexion vers le SGBD
        public function __construct($db) {
            $this->db=$db;
        }
        // ajout d'un categorie dans la BD
        public function add(Categorie $categorie) {
			// calcul d'un nouveau code de categorie non déja utilisé = Maximum + 1
			$categorie->setIdMembre($this->db->query("SELECT MAX(idcategorie) AS MAXIMUM FROM categorie")->fetchColumn()+1);
			
			// requete d'ajout dans la BD
			$req = "INSERT INTO categorie (idcategorie,nom) "
				. " VALUES(".$categorie->getIdCategorie()."','".$categorie->getNom()."')";
			
			return $this->db->exec($req);
		}
        // nombre de membres dans la base de données
        public function count() {
			return $this->db->query('SELECT COUNT(*) FROM categorie')->fetchColumn();
        }
        // suppression d'un categorie dans la base de données
        public function delete(Categorie $categorie) {
			return $this->db->exec('DELETE FROM categorie WHERE idcategorie = '.$categorie->getIdCategorie());
        }
		// recherche dans la BD d'un membre à partir de son id
		public function get($idcategorie) {
			$q = $this->db->prepare('SELECT idcategorie,nom FROM categorie WHERE idcategorie = ?');
			$q->execute(array($idcategorie));
			$categorie = new Categorie($q->fetch());
			return $categorie;
		}		
		// retourne l'ensemble des categories présents dans la BD 
        public function getList() {
			$categories = array();  
            $q = $this->db->query('SELECT idcategorie,nom FROM categorie');
            while ($donnees = $q->fetch())
            {
                $categories[] = new Categorie($donnees);
            }
            return $categories;
        }
        // modification d'un categorie dans la BD 
        public function update(Categorie $categorie) {
        	$modif = $this->db->prepare("INSERT INTO categorie (idcategorie,nom) VALUES(:idcategorie, :nom) WHERE idcategorie = :idcategorie");
        	$ok = $modif->execute(array(
        			"idcategorie" => $categorie->getidcategorie(),
        			"nom" => $categorie->getNom()
        		));

        	return $ok;
        }
    }
?>