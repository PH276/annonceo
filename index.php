<?php require_once ('inc/init.inc.php') ?>
<?php include ('inc/header.inc.php') ?>

<?php
$regions = array(
	"Auvergne-Rhône-Alpes" => array('01', '03', '07', '15', '26', '38', '42', '43', '63', '69', '73', '74'),
	"Bourgogne-Franche-Comté" => array('21', '25', '39', '58', '70', '71', '89', '90'),
	"Bretagne" => array ('22', '29', '35', '56'),
	"Centre-Val de Loire" => array ('18', '28', '36', '37', '41', '45'),
	"Corse" => array ('20'),
	"Grand-Est" => array ('08', '10', '51', '52', '54', '55', '57', '67', '68', '88'),
	"Hauts-de-France" => array ('02', '59', '60', '62', '80'),
	"Île-de-France" => array ('75', '77', '78', '91', '92', '93', '94', '95'),
	"Normandie" => array('14', '27', '50', '61', '76'),
	"Nouvelle-Aquitaine" => array('16', '17', ' 19', '23', '24', '33', '40', '47', '64', '79', '86', '87'),
	"Occitanie" => array ('09', '11', '12', '30', '31', '32', '34', '46', '48', '65', '66', '81', '82'),
	"Pays-de-la-Loire" => array ('44', '49', '53', '72', '85'),
	"Provence-Alpes-Côte d'azur" => array('04', '05', '06', '13', '83', '84'),
);

$resultat = $pdo->query('SELECT id_categorie, titre FROM categorie');
$categories = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo->query('SELECT id_membre, prenom, nom FROM membre ORDER BY nom, prenom');
$membres = $resultat -> fetchAll(PDO::FETCH_ASSOC);

// $resultat = $pdo->query('SELECT id_membre, prenom, nom, avg(note) moyenne,  FROM membre
// LEFT JOIN note ON id_membre=membre_id2
// ORDER BY moyenne DESC');
// $membres = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$req = 'SELECT *, prenom FROM annonce
LEFT JOIN membre ON id_membre=membre_id WHERE 1';

// filtre de catégorie
$req .= (isset($_POST['categorie']) && $_POST['categorie']!='0')?' AND categorie_id=' . $_POST['categorie']:'';

//filtre de région
if (isset($_POST['region']) && $_POST['region']!='0'){
	$region = $regions["$_POST[region]"];
	print_r ($region).'<br>';
	$departements = '(0';
	foreach($region as $departement){
		$departements .= ', '.$departement;
	}
	$departements .= ')';
	echo $departements.'<br>';
	$req .= ' AND LEFT (cp, 2) IN ' . $departements;
}

// filtre de membre
$req .= (isset($_POST['membre']) && $_POST['membre']!='0')?' AND membre_id=' . $_POST['membre']:'';
echo $req.'<br>';
$resultat = $pdo->query($req. ' LIMIT 0,3 ');
$annonces = $resultat -> fetchAll(PDO::FETCH_ASSOC);


?>

<?php $page = ''; ?>
<h1>Accueil test</h1>

<div class="selection">
	<form action="" method="post">

		<label>Catégorie </label><br/>
		<select name="categorie">
			<option value="0">Toutes les Catégories</option>
			<?php foreach($categories as $categorie) : ?>
				<option value="<?= $categorie['id_categorie'] ?>"><?= $categorie['titre'] ?></option>
			<?php endforeach; ?>
		</select>
		<br/>

		<label>Région :</label><br/>
		<select name="region">
			<option value="0">Toutes les régions</option>
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
		<select name="membre">
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

		<input type="submit" value="Filtrer">



	</form>
</div>
<div class="tri">
	<form action="" method="post">
		<select name="tri">
			<option value="0">Trier par...</option>
			<option value="prix">Trier par prix (du moins cher au plus cher)</option>
			<option value="prix DESC">Trier par prix (du plus cher au moins cher)</option>
			<option value="date_enregistrement DESC">Trier par date (du plus récent au moins récent)</option>
			<option value="date_enregistrement">Trier par date (moins récent au du plus récent)</option>
			<option value="">Meilleur vendeur</option>
		</select>
	</form>
</div>
<div class="resultat_annonces">

	<div class="annonce">



		<?php foreach ($annonces as $annonce) : ?>
			<?php
			$resultat = $pdo->query('SELECT avg(note) note FROM note WHERE membre_id2=' . $annonce['membre_id'] . ' GROUP BY membre_id2');
			$note = $resultat -> fetch(PDO::FETCH_ASSOC);
			?>

			<hr>
			<div>
				<p><?= $annonce['titre']  ?><br/>
					<div>
						<img src="photos/<?= $annonce['photo']  ?>"/>
					</div>
					<?= $annonce['cp'] . ' ' . $annonce['ville']  ?><br/>
					<?= $annonce['description_courte']  ?>
					<?= $annonce['prenom'] ?>
					<?php for ($i = 0 ; $i < $note['note'] ; $i++) : ?>
						<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
					<?php endfor; ?>
					<?php for ($i = $note['note'] ; $i < 6 ; $i++) : ?>
						<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
					<?php endfor; ?>

					<?= $annonce['prix']  ?> €
				</p>
			</div>
		<?php endforeach; ?>
	</div>



</div>



</div>
<?php include ('inc/footer.inc.php') ?>
