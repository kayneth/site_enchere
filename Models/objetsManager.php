<?php
require_once("connect.php");
require_once("Modules/objets.php");
// Définition d'une classe permettant de gérer les itinéraires 
//   en relation avec la base de données	
class ObjetManager
    {
        private $db; // Instance de PDO - objet de connexion au SGBD
        
		// Constructeur = initialisation de la connexion vers le SGBD
        public function __construct($db) {
            $this->db=$db;
        }
        // ajout d'un itineraire dans la BD
        public function add(Objet $obj, $file) {
			// calcul d'un nouveau code d'itineraire non déja utilisé = Maximum + 1
			$obj->setIdObj($this->db->query("SELECT MAX(IDOBJ) AS MAXIMUM FROM objets")->fetchColumn()+1); //nouvel ID

            if (isset($file) AND $file['error'] == 0){
                if ($file['size'] <= 1000000000) //1 MILLIARD
                {
                    $infosfichier = pathinfo($file['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = ['jpg', 'png', 'gif'];
                    if (in_array($extension_upload, $extensions_autorisees))
                    {
                            move_uploaded_file($file['tmp_name'], './images/' . $obj->getIdObj() . '.' .  $extension_upload);
                    }
                }
            }

            $photo = $obj->getIdObj() . '.' .  $extension_upload;
			
			// requete d'ajout dans la BD
			$req = "INSERT INTO objets (idobj,idcategorie,iduser,nom,prixini,datedebut,datefin,description,photo) VALUES(:idobj, :idcategorie, :iduser, :nom, :prixini, NOW(), :datefin, :description, :photo)";
			$add = $this->db->prepare($req);
            $ok = $add->execute(array(
                    "idobj" => $obj->getIdObj(),
                    "idcategorie" => $obj->getIdCategorie(),
                    "iduser" => $obj->getIdUser(),
                    "nom" => $obj->getNom(),
                    "prixini" => $obj->getPrixIni(),
                    "datefin" => $obj->getDateFin(),
                    "description" => $obj->getDescription(),
                    "photo" => $photo
                ));
			return $ok;
        }
        // nombre d'itinéraires dans la base de données
        public function count() {
            return $this->db->query('SELECT COUNT(*) FROM objets')->fetchColumn();
        }
        // suppression d'un itineraire dans la base de données
        public function delete(Objet $obj) {
            return $this->db->exec('DELETE FROM objets WHERE idobj = '.$obj->getIdObj());
        }
		// recherche dans la BD d'un itineraire à partir de son id
		public function get($idobj) {
			$q=$this->db->prepare('SELECT idobj,idcategorie,iduser,nom,prixini,date_format(datedebut,"%d/%c/%Y") as datedebut,date_format(datefin,"%d/%c/%Y") as datefin,description,photo FROM objets WHERE idobj=?');
            $q->execute(array($idobj));
			$obj = new Objet($q->fetch());
			return $obj;
		}		
		// retourne l'ensemble des itinéraires présents dans la BD 
        public function getList() {
            $objs = array();  
            //
            $q = $this->db->query('SELECT idobj,idcategorie,iduser,nom,prixini,date_format(datedebut,"%d/%c/%Y") as datedebut,date_format(datefin,"%d/%c/%Y") as datefin,description,photo FROM objets');
            while ($donnees = $q->fetch())
            {
                $objs[] = new Objet($donnees);
            }
            return $objs;
        }


		// retourne l'ensemble des objets disponibles par rapport à la date
        public function getListDispo($p){
            $objs = array();
            $epp = 5;
            $start = (($p-1)*$epp);
                        
            $req = 'SELECT idobj,idcategorie,iduser,nom,prixini,date_format(datedebut,"%d/%c/%Y") as datedebut,date_format(datefin,"%Y-%c-%d %H:%i:%s") as datefin,description,photo, UNIX_TIMESTAMP(datefin) as timefin FROM objets WHERE datefin > curdate() LIMIT '.$start.','.$epp ;
            $q = $this->db->query($req);
            while ($donnees = $q->fetch())
            {
                $objs[] = new Objet($donnees);
            }
            return $objs;
        }

        //retourne l'ensemble des objets dont le temps est dépassé
        //
        public function getListTerm() {
            $objs = array();
                        
            $req = 'SELECT idobj,idcategorie,iduser,nom,prixini,date_format(datedebut,"%d/%c/%Y") as datedebut,date_format(datefin,"%Y-%c-%d %H:%i:%s") as datefin,description,photo, UNIX_TIMESTAMP(datefin) as timefin FROM objets WHERE datefin <= NOW() ';
            $q = $this->db->query($req);
            while ($donnees = $q->fetch())
            {
                $objs[] = new Objet($donnees);
            }
            return $objs;
        }

        //renvoie true si l'objet est dispo
        public function getDispo(){

        }

		// méthode de recherche d'itinéraires dans la BD à partir des critères passés en paramètres
		// renvoit uniquement les itinéraires disponibles par rapport à la date (et par rapport au nombre de places.....) 
		public function search($received)
		{
			$req = 'SELECT idobj,idcategorie,iduser,nom,prixini,date_format(datedebut,"%d/%c/%Y") as datedebut, date_format(datefin,"%d/%c/%Y") as datefin,description,photo FROM objets';
			$cond = ' WHERE datefin > curdate() ';
            if(!empty($received['objNom'])){
                $cond .= " AND nom LIKE '%".$received['objNom']."%'";
            }
            else if(!empty($received['cat'])){
                $cond .= ' AND idcategorie = '.$received['cat'];
            }

            // execution de la requete  
            $req .= $cond;

			$q = $this->db->query($req);
			$objs = array();
            while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
            {
                $objs[] = new Objet($donnees);
            }
            return $objs;
		}
        // modification d'un itineraire dans la BD (sauf iditi et idmembre
        public function update(Objet $obj)
        {
            $req = "UPDATE objets SET idcategorie = '".$obj->getIdCategorie()."', "
						. "nom = '".$obj->getNom()."', "
						. "prixini = '".$obj->getPrixIni()."', "
						. "datedebut = '".$obj->getDateDebut()."', "
						. "datefin = ".$obj->getDateFin().", "
						. "description = ".$obj->getDescription().", "
						. "photo = '".$obj->getPhoto()."'  " 
						." WHERE idobj =".$obj->getIdObj();
                        echo $req;
            return $this->db->exec($req);
			
        }
		
		// retourne l'ensemble des itinéraires d'un membre
        public function getListUser($iduser) {
            $objs = array();
            
            $q = $this->db->query('SELECT idobj,idcategorie,iduser,nom,prixini,date_format(datedebut,"%d/%c/%Y") as datedebut, date_format(datefin,"%d/%c/%Y") as datefin,description,photo FROM objets WHERE iduser ='.$iduser);
            while ($donnees = $q->fetch())
            {
                $objs[] = new Objet($donnees);
            }
            return $objs;
        }

        //affiche la navigation numérique de pagination
        public function pagination(){
            $nbentries = $this->db->query('SELECT COUNT(*) FROM objets WHERE datefin > curdate()')->fetchColumn();
            $epp = 5;
            $nbpages = ceil($nbentries / $epp);
            return $nbpages;
        }
    }

    /*pour la pagination :
    vérifier le nbr d'entrées dans la bdd
    le nbr par page
    le nbr de pages au total
    $current = page actuelle valeur session pour se souvenir
    choisir à partir de quel élement on veut afficher : $start = (($current-1) * $epp)
    afficher le nombre d'entrée autorisées par page
    afficher la navigation de page en page
    dès qu'on choisit la page qui suit, on envoie un get pour afficher les éléments suivant ex : si $_GET['p'] = 2, LIMIT $start,$epp

    */
?>