<?php
require_once("connect.php");
require_once("Modules/enchere.php");
require_once ("Modules/objets.php");
require_once ("Models/objetsManager.php");
// Définition d'une classe permettant de gérer les réservations 
//   en relation avec la base de données
class EnchereManager
    {
        private $db; // Instance de PDO - objet de connexion au SGBD
        
		// Constructeur = initialisation de la connexion vers le SGBD
        public function __construct($db) {
            $this->db=$db;
        }

        // ajout d'une enchere dans la BD
        public function add(Enchere $enchere) {

            $objetsManager = new ObjetManager($this->db);
            $datefin = $objetsManager->get($enchere->getIdUser())->getDateFin();
            $today = date("d/m/Y");

            //si la date n'est pas dépassée on autorise l'enchère 
            if(strtotime($datefin) > strtotime($today)){
    			$last = $this->db->prepare('SELECT  IFNULL(MAX(SOMME),0) as somme FROM enchere WHERE idobj = ?');
                $last->execute(array(
                    $enchere->getIdObj()
                    ));
                $lastEnch = $last->fetch();
                //si la nouvelle offre est supérieure à l'ancienne, on autorise l'ajout
                if( $lastEnch[0] < $enchere->getSomme()){
                    $enchere->setIdEnchere($this->db->query("SELECT MAX(IDENCHERE) AS MAXIMUM FROM enchere")->fetchColumn()+1); //NOUVEL ID
                   
                    $req = "INSERT INTO enchere (idenchere,idobj,iduser,somme, dateenchere) VALUES(:idenchere, :idobj,:iduser,:somme, NOW())";
                    $add = $this->db->prepare($req);
                    $added = $add->execute( array(
                        "idenchere" => $enchere->getIdEnchere(),
                        "idobj" => $enchere->getIdObj(),
                        "iduser" => $enchere->getIdUser(),
                        "somme" => $enchere->getSomme()
                        ));
                    return $added;
                }    
            }

        }
        // nombre de encheres dans la base de données
        public function count() {
            return $this->db->query("SELECT COUNT(*) FROM enchere")->fetchColumn();
        }
        // suppression d'une réservation dans la base de données
        public function delete(Enchere $enchere) {
            return $this->db->exec("DELETE FROM enchere WHERE idenchere= ".$enchere->getIdEnchere()." AND idobj= ".$enchere->getIdObj());
        }
		// recherche les encheres d'un membre à partir de son id
		public function get($idenchere) {
			$encheres = array();
            echo 'SELECT idenchere,idobj,iduser,somme, dateenchere FROM enchere WHERE idenchere='.$idenchere;
			$q=$this->db->query('SELECT idenchere,idobj,iduser,somme, dateenchere FROM enchere WHERE idenchere='.$idenchere);
            while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
            {
                $encheres[] = new Enchere($donnees);
            }
            return $encheres;
		}		
		// retourne l'ensemble des encheres présents dans la BD 
        public function getList() {
            $encheres = array();
            
            $q = $this->db->query('SELECT idenchere,idobj,iduser,somme, dateenchere FROM enchere');
            while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
            {
                $encheres[] = new Enchere($donnees);
            }
            return $encheres;
        }

        //retourne la liste des enchères fait sur un objet
        public function getListObjet($idobj){
            $encheres = array();
            $q = $this->db->prepare('SELECT idenchere,idobj,iduser,somme, dateenchere FROM enchere WHERE idobj = ?');
            $q->execute(array($idobj));
            while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
            {
                $encheres[] = new Enchere($donnees);
            }
            return $encheres;
        }

        // retourne la dernière enchère sur un objet dans la BDD
        public function getLast($idobj) {
            $q = $this->db->prepare('SELECT idenchere, IFNULL(MAX(SOMME),0) as somme FROM enchere WHERE idobj = ?');
            $q->execute(array($idobj));
            $enchere = new Enchere($q->fetch());
            return $enchere;
        }

        // retourne true si la dernière enchère a été effectué par l'utilisateur co
        public function getWin($idobj, $iduser) {
            $isTrue = false;
            $q = $this->db->prepare('SELECT MAX(IDUSER) as iduser FROM enchere WHERE idobj = ?');
            $q->execute(array($idobj));
            $enchere = new Enchere($q->fetch());
            if($enchere->getIdUser() == $iduser){
                $isTrue = true;
            }
            return $isTrue;
        }

         // retourne la dernière enchère sur la liste de tous les objets dans la BDD
        public function getListLast() {
            $encheres = array();
            $q = $this->db->prepare('SELECT idenchere, IFNULL(MAX(SOMME),0) as somme FROM enchere');
            $q->execute(array($idobj));
            while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
            {
                $encheres[] = new Enchere($donnees);
            }
            return $encheres;
        }

        // modification d'une enchere dans la BD 
        public function update(Enchere $enchere)
        {
            $req = "UPDATE enchere SET "
						. "datereservation = '".$enchere->dateReservation()."', "
						. "nbplacesreservees = '".$enchere->nbPlacesReservees()."', "
						." WHERE idenchere =".$enchere->getIdEnchere()." AND iditi=".$enchere->idIti();
// echo "req : $req";
            return $this->db->exec($req);	
        }

        //récupère l'idobj des enchères en cours pour un utilisateur
        public function getEnchereCours($iduser){
            $listIdObj = array();
            $query = "SELECT DISTINCT idobj FROM enchere WHERE iduser = ?";

            $q = $this->db->prepare($query);
            $q->execute(array($iduser));

            while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
            {
                $encheres[] = $donnees;
            }
            return $listIdObj;
        }
    }
?>