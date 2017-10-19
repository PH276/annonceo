<?php
require('inc/init.inc.php');
$page = 'Ajout annonce - ';
$resultat = $pdo->query('SELECT id_categorie, titre FROM categorie');

$categories = $resultat -> fetchAll(PDO::FETCH_ASSOC);

// traitement pour ajouter un produit dans la boutique :


if(!empty($_POST)){

	debug($_POST);
	debug($_FILES);

	// Renommer la photo / ref_time()_nom.ext
	// contrôles sur la photo
	// enregistrer la photo sur le serveur

	// Contrôles sur les infos du formulaire (pas vide, nbre de caractère etc...)
	// Requete pour insérer les infos dans la BDD.
	// redirection sur gestion_annonces.php

	//	$nom_photo = 'default.jpg';

	for ($i = 1 ; $i <= 5; $i++){
		if(isset($_POST['photo_actuelle'.$i])){
			$nom_photo[$i] = $_POST['photo_actuelle'.$i];
		}
	}
	// Si je suis dans le cadre d'une modification de produit, on récupère le nom de l'ancienne photo... mais il se peut que l'utilisateur souhaite changer la photo, c'est le code ci-dessous qui prend le relais.

	if(isset($_FILES['photo1']) && !empty($_FILES['photo1']['name'])){ // Si une photo est uploadée

		$nom_photo = $_POST['id_annonce'] . '_' . time() . '_' . $_FILES['photo1']['name'];
		// Si la photo est nommée tshirt.jpg, on la renomme : XX21_1543234454_tshirt.jpg pour aviter les doublons possibles sur le serveur (cf les noms des photos sur facebook par exemple).

		$chemin_photo = $_SERVER['DOCUMENT_ROOT'] . RACINE_ANNONCEO . 'photos/' . $nom_photo;
		// chemin: c://xampp/htdocs   /PHP/site/   photo/   XX21_1543234454_tshirt.jpg

		$ext = array('image/png', 'image/jpeg', 'image/gif');
		if(!in_array($_FILES['photo1']['type'], $ext)){
			$msg .= '<div class="erreur">Images autorisées : PNG, JPG, JPEG et GIF</div>';
			// Si le fichier uploadé ne correspond pas aux ext<a href="ajout_annonce%20bis.php">No Title</a>ensions autorisées (ici PNG, JPEG, JPG et GIF) alors on affiche un message d'erreur.
		}

		if($_FILES['photo1']['size'] > 2000000){
			$msg .= '<div class="erreur">Images : 2Mo maximum autorisé</div>';
			// Si la photo uploadées est trop valumineuse (ici 2Mo max), alors on met un message d'erreur.
			// Par defaut XAMPP autorise 2,5Mo. Voir dans php.ini, rechercher upload_max_filesize=2.5M
		}

		if(empty($msg) && $_FILES['photo1']['error'] == 0){

			copy($_FILES['photo1']['tmp_name'], $chemin_photo);
			// On enregistre la photo sur le serveur. Dans les fait, on la copier depuis son emplacement temporaire et on la colle dans son emplacement définitif.
		}
	}// fin du if isset($_FILES['photo']['name'])

	// Insérer les infos du produit en BDD...
	// Au préalable, nous aurions vérifié tous les champs (taille, caractères, no empty etc......)
	// <a href="ajout_annonce%20bis.php">No Title</a>
	if(empty($msg)){



		if(isset($_POST['Modifier'])){
			echo 'req modif<br>';
			$resultat = $pdo -> prepare("UPDATE annonce set titre= :titre, description_courte = :description_courte, description_longue = :description_longue, prix = :prix, photo = :photo, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, categorie_id = :categorie_id  WHERE id_annonce = :id_annonce");

			$resultat -> bindParam(':id_annonce', $_POST['id_annonce'], PDO::PARAM_INT);

		}
		else{
			$resultat = $pdo -> prepare("INSERT INTO annonce (titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, membre_id, categorie_id, date_enregistrement) VALUES (:titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :cp, :membre_id, :categorie_id, now())");

			$resultat -> bindParam(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
		}

		$resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
		$resultat -> bindParam(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
		$resultat -> bindParam(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
		$resultat -> bindParam(':prix', $_POST['prix'], PDO::PARAM_INT);
		$resultat -> bindParam(':photo', $nom_photo , PDO::PARAM_STR);
		$resultat -> bindParam(':pays', $_POST['pays'], PDO::PARAM_STR);
		$resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
		$resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
		$resultat -> bindParam(':cp', $_POST['cp'], PDO::PARAM_INT);
		$resultat -> bindParam(':categorie_id', $_POST['categorie_id'], PDO::PARAM_INT);

		if($resultat -> execute()){

			$pdt_insert = (isset($_POST['Modifier'])) ? $_POST['id_annonce'] : $pdo -> lastInsertId(); // Récupère l'ID du dernier enregistrement.
			// header('location:ajout_annonce.php');
			echo 'ok : ';
		}
	}
}// fin du if(!empty($_POST))

// traitement pour modifier une annonce
// 1/ On récupère les infos du produit actuel (en cours de modification)
// 2/ On insert les infos de ce produit dans le formulaire
// 3/ Gestion de la photo : Si on modifie simplement un texte, il faut renvoyer l'ancienne image. Si on modifie l'image, il faut pouvoir récupérer la nouvelle image.
// 4/ Enregistrement des modifications

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
	// Si j'ai un ID dans l'URL, non vide et de type INT, alors je suis dans le cadre de la modification d'une annonce.

	$resultat = $pdo -> prepare("SELECT * FROM annonce WHERE id_annonce = ?");
	$resultat -> execute(array($_GET['id']));

	if($resultat -> rowCount() > 0){ //Signifie que l'annonce existe, donc l'id passé en URL était conforme.
		$annonce_actuelle = $resultat -> fetch(PDO::FETCH_ASSOC);
		debug($annonce_actuelle);
	}
}

// Créons des variables pour chaque élément à insérer dans le formulaire :
$id_annonce = (isset($annonce_actuelle)) ? $annonce_actuelle['id_annonce'] : '';

$titre = (isset($annonce_actuelle)) ? $annonce_actuelle['titre'] : '';
$description_courte = (isset($annonce_actuelle)) ? $annonce_actuelle['description_courte'] : '';
$description_longue = (isset($annonce_actuelle)) ? $annonce_actuelle['description_longue'] : '';
$prix = (isset($annonce_actuelle)) ? $annonce_actuelle['prix'] : '';
$photo = (isset($annonce_actuelle)) ? $annonce_actuelle['photo'] : '';
$pays = (isset($annonce_actuelle)) ? $annonce_actuelle['pays'] : '';
$ville = (isset($annonce_actuelle)) ? $annonce_actuelle['ville'] : '';
$adresse = (isset($annonce_actuelle)) ? $annonce_actuelle['adresse'] : '';
$cp = (isset($annonce_actuelle)) ? $annonce_actuelle['cp'] : '';
$categorie = (isset($annonce_actuelle)) ? $annonce_actuelle['categorie_id'] : '';

$action = (isset($annonce_actuelle)) ? 'Modifier' : 'Ajouter';

$page = 'gestion_annonces';
require('inc/header.inc.php');
?>
<h1><?= $action ?> une annonce</h1>

<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id_annonce" value="<?= $id_annonce ?>"/>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Titre :</label>
				<input class="form-control" type="text" name="titre" value="<?= $titre ?>" placeholder="Titre de l'annonce"/>
			</div>

			<div class="form-group">
				<label>Description courte :</label>
				<textarea class="form-control" rows="2" name="description_courte" placeholder="Description courte de l'annonce"><?= $description_courte ?></textarea>
			</div>

			<div class="form-group">
				<label>Description longue :</label>
				<textarea class="form-control" rows="2" name="description_longue" placeholder="Description longue de l'annonce"><?= $description_longue ?></textarea>
			</div>

			<div class="form-group">
				<label>Prix :</label>
				<input class="form-control" type="text" name="prix" value="<?= $prix ?>" placeholder="Prix figurant dans l'annonce"/>
			</div>

			<div class="form-group">
				<label>Catégorie :</label>
				<select class="form-control" name="categorie_id" >
					<option value="0">Toutes les catégories</option>
					<?php foreach ($categories as $value)  : ?>
						<option <?= ($categorie == $value['id_categorie'])?' selected ':'' ?> value="<?= $value['id_categorie'] ?>"><?= $value['titre'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="col-md-6">
			<label>Photos :</label>
			<div class="row">


				<div class="col-md-1"></div>
				<?php for ($i = 1 ; $i <= 5 ; $i++)  : ?>
					<div class="col-md-2">
						<div class="form-group">
							<?php if(isset($annonce_actuelle) && !empty($photo)) :  ?>
								<label for="photo<?= $i ?>">Photo <?= $i ?>
									<img src="<?= RACINE_ANNONCEO ?>photos/<?= $photo ?>" height="100px"/>
								</label>
							<?php else : ?>
								<label for="photo<?= $i ?>">Photo <?= $i ?></label>
								<label for="photo<?= $i ?>">
									<span style="font-size:2em" class="glyphicon glyphicon-camera fa-3x" aria-hidden="true"></span>
								</label>
								<input id="photo<?= $i ?>" type="file" name="photo<?= $i ?>" hidden>
							<?php endif; ?>
							<input type="hidden" name="photo_actuelle<?= $i ?>" value="<?= $photo1 ?>" />
						</div>
					</div>
				<?php endfor; ?>
			</div>

			<div class="form-group">
				<label>Adresse :</label>
				<textarea  class="form-control" name="adresse" rows="2" placeholder=" figurant de l'annonce"><?= $adresse ?></textarea>
				<!-- <input type="text" name="adresse" value="" /> -->
			</div>

			<div class="form-group">
				<label>Code Postal :</label>
				<input class="form-control" type="text" name="cp" value="<?= $cp ?>" placeholder=" figurant de l'annonce"/>
			</div>

			<div class="form-group">
				<label>Ville :</label>
				<input class="form-control" type="text" name="ville" value="<?= $ville ?>" placeholder="de l'annonce"/>
			</div>

			<div class="form-group">
				<label>Pays :</label>
				<input class="form-control" type="text" name="pays" value="<?= $pays ?>" placeholder="de l'annonce"/>
			</div>

		</div>


	</div>
	<input class="btn btn-primary center-block" type="submit" name="<?= $action ?>" value="Enregistrer" />
</form>
<?php
require('inc/footer.inc.php');
?>
