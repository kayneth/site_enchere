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


//creation de session utilisateur
if(!isset($_SESSION["co"])){ $_SESSION["co"] = "non";}
//$_SESSION["id"] = "-1";
if($_SESSION["co"] == "non"){ $_SESSION["id"] = "-1";}

//utilisation de session pour obtenir la page actuelle dans la pagination
if(isset($_GET['p'])){$_SESSION['pactu'] = $_GET['p'];}else{$_SESSION['pactu'] = 1;}

// -------------------------------
//$enchereManager = new EnchereManager($bdd);
$userManager = new UserManager($bdd);
$objetsManager = new ObjetManager($bdd);
$categorieManager = new CategorieManager($bdd);
$enchereManager = new EnchereManager($bdd);

// -------------------------------------------------------------------------------
// acces securisé : verif user connecté ou pas
// utilisation d'une variable de session "accessuser" avec comme valeurs oui/non
// definir l'acces à non si la variable de session n'existe pas

$iduser = $_SESSION["id"] ;  // !!!!!!! On fixe l'idmembre tant que l'on n'a pas fait l'identification

// ------------------------------------------------------------------------------------


// controleur 	
if (isset($_GET["action"])) {

  $action = $_GET["action"]; //recupère l'action passé dans l'url


	switch ($action) {

	  	// liste de tous les objets dispos
		case "liste" :
			$objets = $objetsManager->getListDispo();
			$userco = $userManager->get($iduser);
			$categories = $categorieManager->getList();

			//boucle pour avoir pour chaque objet le prix actuel
			foreach ($objets as $objet) {
				$enchere = $enchereManager->getLast($objet->getIdObj());
				$objet->setPrix($enchere->getSomme());
			}

			echo $twig->render('index_site.html.twig', array('objs' => $objets, 'cats' => $categories, 'accessuser' => $_SESSION["co"], 'userco' => $userco));

		break;

		//affiche le résultat de la recherche
		case "search" :
			// liste de tous les objets dispos par rapport à la recherche effectuée
			$objets = $objetsManager->search($_POST);
			$nomObjCherch = $_POST;
			if($_POST['cat'] != 0){$categorie = $categorieManager->get($_POST['cat']);}
			else{$categorie = "";}
			$userco = $userManager->get($iduser);
			$categories = $categorieManager->getList();

			//boucle pour avoir pour chaque objet le prix actuel
			foreach ($objets as $objet) {
				$enchere = $enchereManager->getLast($objet->getIdObj());
				$objet->setPrix($enchere->getSomme());
			}

			echo $twig->render('search.html.twig', array('objs' => $objets, 'cat' => $categorie, 'cats' => $categories, 'msg' => $nomObjCherch, 'accessuser' => $_SESSION["co"], 'userco' => $userco)); 	
		
		break;


		// detail d'un objet
		case "details" :
			$objet = $objetsManager->get($_GET["idobj"]);
			$user = $userManager->get($_GET["iduser"]);
			$categorie = $categorieManager->get($objet->getIdCategorie());
			$enchere = $enchereManager->getLast($_GET["idobj"]);
			$userco = $userManager->get($iduser);
			echo $twig->render('objet_details.html.twig', array('obj' => $objet, 'user' => $user, 'accessuser' => $_SESSION["co"], 'cat' => $categorie ,'userco' => $userco, 'enchere' => $enchere)); 
		
		break;


		/////// CASES LIES A L'INSCRIPTION ///////////

		//affichage du formulaire d'inscription
		case "inscription" :
			$userco = $userManager->get($iduser);
			echo $twig->render('inscription.html.twig', array('accessuser' => $_SESSION["co"], 'userco' => $userco)); 
		break;


		// affichage du profil d'un utilisateur	
		case "profil" :
			$userco = $userManager->get($iduser);
			$user = $userManager->get($_GET["iduser"]);
			echo $twig->render('user_profil.html.twig', array('user' => $user, 'accessuser' => $_SESSION["co"], 'userco' => $userco));
		break;


		//////////////// AJOUT D'OBJET //////////////


		// affichage du formulaire d'ajout
		case "add" :
			$userco = $userManager->get($iduser);
			$categories = $categorieManager->getList();
			echo $twig->render('ajout.html.twig', array('accessuser' => $_SESSION["co"], 'userco' => $userco, "cats" => $categories));
		break;


		// envoie de l'objet en bdd
		case "addobj" :
			$userco = $userManager->get($iduser);
			$newobj = new Objet($_POST);
			$newobj->setIdUser($iduser);
			$objet = $objetsManager->add($newobj, $_FILES['photo']);
			header('location: index.php');
		break;


		////////////ENCHERE ////////////


		//affiche la page pour enchérir
		case "encherir" :
			$userco = $userManager->get($iduser);
			$objet = $objetsManager->get($_GET["idobj"]);
			$enchere = $enchereManager->getLast($_GET["idobj"]);
			$enchere->setIdUser($iduser);
			echo $twig->render('encherir.html.twig', array('accessuser' => $_SESSION["co"], 'userco' => $userco, 'obj' => $objet, 'enchere' => $enchere));
		break;


		// envoie de l'objet en bdd
		case "validenchere" :
			$enchere = new Enchere($_POST);
			$newEnch = $enchereManager->add($enchere);
			header('location: index.php');
		break;

		//affiche la liste des enchères effectuées sur un objet
		case 'listEncheres' :
			$userco = $userManager->get($iduser);
			$users = $userManager->getList();
			$objet = $objetsManager->get($_GET["idobj"]);
			$encheres = $enchereManager->getListObjet($_GET["idobj"]);
			foreach($encheres as $enchere){
				$pseudo = $userManager->get($enchere->getIdUser())->getPseudo();
				$enchere->setIdUser($pseudo);
			}
			//print_r($encheres);
			echo $twig->render('list_encheres.html.twig', array('accessuser' => $_SESSION["co"], 'userco' => $userco, 'obj' => $objet, 'encheres' => $encheres, 'users' => $users));
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
