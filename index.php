<?php 

// J'appelle les fonctions du ou des controllers en fonction de la variable $_GET["page"]

session_start();
include 'router.php';
$route=new \apps\router\router();// je fais un instantiation de la class router
$route->route();// je pointe vers la methode route qui me permet d'aller vers la premeire page d'affichage
