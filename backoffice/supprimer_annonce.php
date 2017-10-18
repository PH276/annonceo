<?php
require_once('../inc/init.inc.php');
// pas besoin de design (header, footer, contenu) sur cette page, car elle a seulement vocation à nous faire un traitement puis à nous rediriger vers l'affiche de toutes les annonces. 


// On vérifie qu'il a bien un id dans l'URL et que c'est un chiffre
// On récupere les infos de l'annonce
// On vérifie que l'annonce existe
// On supprime la photo si elle existe et que c'est pas default.jpg
// On supprime l'annonce de la BDD
// on redirige vers l'affichage des annonces (gestion_annonces.php). 

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
	
	$resultat = $pdo -> prepare("SELECT * FROM annonce WHERE id_annonce = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute(); 
	
	if($resultat -> rowCount() > 0){ // signifie que l'annonce existe
		$annonce = $resultat -> fetch(PDO::FETCH_ASSOC);
		debug($annonce);
		
		// Supprimer la photo du serveur : 
		$chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_ANNONCEO . 'photo/' . $annonce['photo'];
		// on recompose le chemin ABSOLU du fichier que l'on va supprimer.
		
		if(file_exists($chemin_photo_a_supprimer) && $chemin_photo_a_supprimer != 'default.jpg'){
			unlink($chemin_photo_a_supprimer);
			// Unlink() permet de supprimer un fichier sur notre serveur. 
		}
		
		// supprimer le produit de la BDD : 
		
		$resultat = $pdo -> exec("DELETE FROM annonce WHERE id_annonce = $annonce[id_annonce]");
		
		if($resultat){
			header('location:gestion_annonces.php?msg=suppr&id=' . $annonce['id_annonce']);
		}
	} // fin du if $resultat -> rowCount()
}// fin du if(isset($_GET etc...








