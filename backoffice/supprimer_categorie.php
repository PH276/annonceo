<?php
require_once('../inc/init.inc.php');

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){

	$resultat = $pdo -> prepare("SELECT * FROM categorie WHERE id_categorie = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if($resultat -> rowCount() > 0){ // signifie que l'annonce existe
		$categorie = $resultat -> fetch(PDO::FETCH_ASSOC);
		debug($categorie);

		// Supprimer la photo du serveur :
		$chemin_photo_a_supprimer = RACINE_ANNONCEO . 'photo/' . $categorie['photo'];
		// on recompose le chemin ABSOLU du fichier que l'on va supprimer.

		if(file_exists($chemin_photo_a_supprimer) && $chemin_photo_a_supprimer != 'default.jpg'){
			unlink($chemin_photo_a_supprimer);
			// Unlink() permet de supprimer un fichier sur notre serveur.
		}

		// supprimer le produit de la BDD :
		$resultat = $pdo -> exec("DELETE FROM categorie WHERE id_categorie = $categorie[id_categorie]");

		header('location:gestion_categories.php');
	} // fin du if $resultat -> rowCount()
}// fin du if(isset($_GET etc...
