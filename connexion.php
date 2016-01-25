<?php
//------------------- les objets de l'application ----------------
// les membres
require_once ("Modules/users.php");

//---------------------- les vues -------------------------------
// moteur de template pour les vues
require_once ("moteurtemplate.php");

//---------------------- les models -----------------------------
require_once ("Models/usersManager.php");


// --------------------- utilisation des sessions --------------
session_start();

if(!isset($_SESSION["co"])){ $_SESSION["co"] = "non";}
//$_SESSION["id"] = "-1";
if($_SESSION["co"] == "non"){ $_SESSION["id"] = "-1";}

// -------------------------------
//$enchereManager = new EnchereManager($bdd);
$userManager = new UserManager($bdd);

// -------------------------------------------------------------------------------
// acces securisé : verif user connecté ou pas
// utilisation d'une variable de session "accessuser" avec comme valeurs oui/non
// definir l'acces à non si la variable de session n'existe pas

$iduser = $_SESSION["id"] ;  // !!!!!!! On fixe l'idmembre tantque l'on n'a pas fait l'identification

//click sur le btn connexion
if(isset($_POST['connexion'])){
	if(isset($_POST['pseudo']) && isset($_POST['mdp'])){
		$ok = $userManager->verif_identification($_POST['pseudo'], $_POST['mdp']);
		if($ok){
			$_SESSION['co'] = "oui";
			$_SESSION['id'] = $ok->getIdUser(); 
		}
		else{
			$_SESSION['co'] = "non";
		}
	}
}
if(isset($_GET['deconnexion'])){
	$_SESSION['co'] = "non";
	$_SESSION['id'] = "-1";
}

if(isset($_POST['inscription'])){
	$user = new Users($_POST);
	$userManager->add($user);
}

header('Location: index.php');

?>
