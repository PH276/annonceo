<?php
require_once('../inc/init.inc.php');

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){

	$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if($resultat -> rowCount() > 0){ // signifie que l'annonce existe
		$membre = $resultat -> fetch(PDO::FETCH_ASSOC);
		debug($membre);

		// supprimer le produit de la BDD :
		$resultat = $pdo -> exec("DELETE FROM membre WHERE id_membre = $membre[id_membre]");

		if($resultat){
			header('location:gestion_membres.php');
		}
	} // fin du if $resultat -> rowCount()
}// fin du if(isset($_GET etc...
