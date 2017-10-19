<?php

require_once('inc/init.inc.php');


if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) ){
	
	$resultat = $pdo -> prepare("SELECT * FROM annonce WHERE id_annonce = :id_annonce");
	$resultat -> bindParam(':id_annonce', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();
	
	if($resultat -> rowCount() > 0){ // l' annonce existe bien !
		$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
		extract($annonce);
		debug($annonce);
	}
	else{ // l'annonce n'existe pas/plus : REDIRECTION ! 
		header('location:index.php');
	}
}
else{ // Il n'y a pas d'ID dans l'url ou vide, ou pas numérique : REDIRECTION !
	header('location:index.php');
}


if(!empty($_POST)){
	ajouterAnnonce($id_annonce, $photo, $titre, $prix);
	// Fonction codée dans le fichier ajout_annonce.php
	
}

debug($_SESSION);



$page = 'repert_annonces';
require_once('inc/header.inc.php');
?>
<h1><?= $titre ?></h1>
<div>
<img src="<?= RACINE_ANNONCEO ?>photo/<?= $photo ?>" width="250" />
</div>
<div>
<p>Description : <br/>
<em><?= $description_longue ?></em></p>
</div>
<div>	
	<ul>
		<li>Date de publication: <b><?= $datepub ?></b></li>
		<li>Catégorie : <b><?= $categorie ?></b></li>
		<li>Prix : <b><?= $prix ?>€ TTC</b></li>
	</ul>
</div>
<div>
	<fieldset>
		<legend>Contacter <b><?= $membre ?></b> 
			<input type="submit" value="Contacter <b><?= $membre ?>"/>
		</legend>
	</fieldset>
</div>
<div>

    <div id="map" style{height: 100px;width: 100%;}>
	</div>
		<script>
		function initMap() {
			var clermont = {lat: 45.465, lng: 3.045};
			var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 4,
			center: clermont
			});
			var marker = new google.maps.Marker({
			position: clermont,
			map: map
			});
		}
		</script>
		<script async defer
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWHYF1HxL1feSSF8akAoFjC7uGRk27YG4&callback=initMap">
		</script>
</div>
<div>
<h2>Autres annonces</h2>
<hr>
	<div class = "autrannonce">
		<img src="photos/<?= ?>.jpg" alt="">
	</div>
	<div class= "autrannonce">
		<img src="photos/<?= ?>.jpg" alt="">
	</div>
	<div class = "autrannonce">
		<img src="photos/<?= ?>.jpg" alt="">
	</div>
	<div class ="autrannonce">
		<img src="photos/<?= ?>.jpg" alt="">
	</div>
</div>
<div>
<p><a href="formulaire_de_commentaire.php">déposer un commentaire ou une note</a></p>

<p><a href="annonces.php">retourner vers les annonces</a></p>


</div>

<?php
require_once('inc/footer.inc.php');
?>