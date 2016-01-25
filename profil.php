<?php
//------------------- les objets de l'application ----------------
// les objets en vente
require_once ("Modules/objets.php");
// les membres
require_once ("Modules/users.php");
// mes enchere
require_once ("Modules/enchere.php");
// les catégories
require_once ("Modules/categorie.php");

//---------------------- les vues -------------------------------
// moteur de template pour les vues
require_once ("moteurtemplate.php");

//---------------------- les models -----------------------------
require_once ("Models/enchereManager.php");
require_once ("Models/usersManager.php");
require_once ("Models/objetsManager.php");
require_once ("Models/categorieManager.php");


// --------------------- utilisation des sessions --------------
session_start();

if(!isset($_SESSION["co"])){ $_SESSION["co"] = "non";}
//$_SESSION["id"] = "-1";
if($_SESSION["co"] == "non"){ $_SESSION["id"] = "-1";}

// -------------------------------
$userManager = new UserManager($bdd);
$objetsManager = new ObjetManager($bdd);
$categorieManager = new CategorieManager($bdd);
$enchereManager = new EnchereManager($bdd);

// -------------------------------------------------------------------------------
// acces securisé : verif user connecté ou pas
// utilisation d'une variable de session "accessuser" avec comme valeurs oui/non
// definir l'acces à non si la variable de session n'existe pas

$iduser = $_SESSION["id"] ;  // !!!!!!! On fixe l'idmembre tant que l'on n'a pas fait l'identification


// controleur 	
if (isset($_GET["action"])) {

  $action = $_GET["action"]; //recupère l'action passé dans l'url
	switch ($action) {
		// affichage du profil d'un utilisateur	
		case "profil" :
			$userco = $userManager->get($iduser);
			echo $twig->render('index_user.html.twig', array('accessuser' => $_SESSION["co"], 'userco' => $userco));
		break;
		/////////////// OBJETS ACQUIS //////////////////


		// recupérer les enchères gagnées
		case "objetsachete" :
			$userco = $userManager->get($iduser);
			$objets = $objetsManager->getListTerm();
			$wins = [];


			foreach ($objets as $objet) {
				$enchere = $enchereManager->getWin($objet->getIdObj(), $iduser);
				if($enchere){
					$enchere = $enchereManager->getLast($objet->getIdObj());
					$objet->setPrix($enchere->getSomme());
					$wins[] = $objet;
				}
			}

			echo $twig->render('acquis.html.twig', array('objs' => $wins, 'accessuser' => $_SESSION["co"], 'userco' => $userco));
		break;


		//affiche la page de modification du profil
		case "modifProfil" :
			$userco = $userManager->get($iduser);
			echo $twig->render('modif_profil.html.twig', array('accessuser' => $_SESSION["co"], 'userco' => $userco));
		break;

		//envoi le formulaire de modif du profil + affiche le profil de la personne
		case "envoiModifProfil" :
			$userco = $userManager->get($iduser);
			$newInfoUser = new Users($_POST);
			$newInfoUser->setIdUser($iduser);
			$modif = $userManager->update($newInfoUser);
			if($modif){$msg = "Votre profil a bien été modifié";}else{$msg = "Problème lors de la modification";}

			echo $twig->render('index_user.html.twig', array('msg' => $msg, 'accessuser' => $_SESSION["co"], 'userco' => $userco));
		break;

		//affiche un tableau des enchères en cours pour l'utilisateur connecté
		case "enchereCours" :
			$listIdObj = $enchereManager->getEnchereCours($iduser);
			$userco = $userManager->get($iduser);
			//$objets = $objetsManager->getListDispo($p);

			echo $twig->render('enchere_cours.html.twig', array('accessuser' => $_SESSION["co"], 'userco' => $userco));
		break;
	}
}

//Cas par défaut (afficher la page d'accueil avec la liste des objets)
else {
	if(isset($_GET['p'])){
		$p = $_GET['p'];
	}else{ $p = 1;}
	
	$nbrp = $objetsManager->pagination(); //nbr de page pour afficher la pagination
	$objets = $objetsManager->getListDispo($p);
	$userco = $userManager->get($iduser);
	$categories = $categorieManager->getList();
	foreach ($objets as $objet) {
		$enchere = $enchereManager->getLast($objet->getIdObj());
		$objet->setPrix($enchere->getSomme());
	}
	echo $twig->render('index_site.html.twig', array('objs' => $objets, 'nbp' => $nbrp, 'cats' => $categories, 'accessuser' => $_SESSION["co"], 'userco' => $userco, 'pactu' => $_SESSION['pactu'])); 
}
?>
