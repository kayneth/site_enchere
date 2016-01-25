<?php
include "config.inc.php";
error_reporting(E_ALL);
try
{
	$bdd = new PDO("mysql:host=$server;dbname=$database", $user, $passwd);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
?>