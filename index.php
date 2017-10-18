<?php require_once ('inc/init.inc.php') ?>
<?php include ('inc/header.inc.php') ?>

<?php
$resultat = $pdo->query('SELECT id_categorie, titre FROM categorie');
$categories = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo->query('SELECT id_membre, prenom, nom FROM membre ORDER BY nom, prenom');
$membres = $resultat -> fetchAll(PDO::FETCH_ASSOC);
?>

<?php $page = ''; ?>
<h1>Accueil test</h1>

<div class="selection">
	<form action="" method="post">

		<label>Catégorie </label><br/>
		<select>
			<option value="0">Toutes les Catégories</option>
			<?php foreach($categories as $categorie) : ?>
			<option value="<?= $categorie['id_categorie'] ?>"><?= $categorie['titre'] ?></option>
		<?php endforeach; ?>
	</select>
	<br/>

	<?php
	$regions = array(
		"Auvergne-Rhône-Alpes" => array(01,03,07,15,26,38,42,43,63,69,73,74),
		// $codepostal = substr($cp, 0,2)
		// 	select annonce WHERE 'cp'=
		// 	submit
		// function select 'cp'($codepostal)
		"Bourgogne-Franche-Comté" => array(21,25,39,58,70,71,89,90),
		"Bretagne" => array (22,29,35,56),
		"Centre-Val de Loire" => array (18,28,36,37,41,45),
		"Corse" => array (20),
		"Grand-Est" => array ("08",10,51,52,54,55,57,67,68,88),
		"Hauts-de-France" => array (02,59,60,62,80),
		"Île-de-France" => array (75,77,78,91,92,93,94,95),
		"Normandie" => array(14,27,50,61,76),
		"Nouvelle-Aquitaine" => array(16,17, 19,23,24,33,40,47,64,79,86,87),
		"Occitanie" => array ("09",11,12,30,31,32,34,46,48,65,66,81,82),
		"Pays-de-la-Loire" => array (44,49,53,72,85),
		"Provence-Alpes-Côte d'azur" => array(04,05,06,13,83,84),
	);
	// debug($regions);
	?>
	<label>Région :</label><br/>
	<select>
		<option value="">Toutes les régions</option>
		<?php foreach($regions as $region=>$departements) : ?>
			<option><?= $region ?></option>
		<?php endforeach; ?>
	</select>
	<?php

	// $(Auvergne-Rhone-Alpes) =

	// 		S
	// 		<!--Renvoie la région à partir du code postal ou du numéro de département-->
	//   		function region($codepostal)
	// {
	// 		global $liste_regions;
	// 		$departement = substr($codepostal,0,2);
	//
	// 		foreach($liste_regions as $region => $liste_dep)
	// 		{
	// 			if (in_array($departement, $liste_dep))
	// 			{
	// 				return $region;
	// 			}
	// 		}
	// }
	?>

	<br/>

	<label>membre :</label><br/>
	<select vale="membre">
		<option value="0">Tous les membres</option>
		<?php foreach($membres as $membre) : ?>
			<option value="<?= $membre['id_membre'] ?>"><?= $membre['prenom'].' '.$membre['nom'] ?></option>
		<?php endforeach; ?>
	</select>
	<br/>

	<label>Prix :</label><br/>
	<input name="prix" type="range" max="10000€" min="0 €" step="500€">
	maximum 10 000 €

	<br/>



</form>
</div>
<div class="tri">
	<form action="" method="post">
		<select>
			<option value="1">Trier par prix (du moins cher au plus cher)</option>
			<option value="1">Trier par date (du plus récent au moins récent)</option>
			<option value="1">Trier par prix (du plus cher au moins cher)</option>
		</select>
	</form>
</div>
<div class="resultat_annonces">

	<div class="annonce">




		<div>
			<hr>
			<p>Iphone 6<br/>
				<div>
					<img src="photos/iphone.jpg"/>
				</div>
				Ville Paris 75011<br/>
				Je vends mon iPhone 6 16gb débloqué tout opérateur.<br/>
				Il est vendu avec son câble de charge officiel et son verre trempé.<br/>
				Marie-Dominique       220 €<br/>
			</p>
			<hr>
		</div>
	</div>



</div>



</div>
<?php include ('inc/footer.inc.php') ?>
