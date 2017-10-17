<?php 
require_once('inc/init.inc.php');

// Traitement pour rediriger l'utilisateur s'il est déjà connecté



// Traitement pour l'inscription
// -> Vérifie si le formulaire est activé
// -> affiche avec print_r()
// -> Controle sur les champs (pseudo & mdp & email)
// -> Enregistrer l'utilisateur
// 		--> Pseudo disponible ? / Email disponible ?
//      --> INSERT
//      --> Redirection vers la connexion

if(!empty($_POST)){

	debug($_POST);

	// Verification Pseudo :
	$verif_pseudo = preg_match('#^([a-zA-Z0-9._-]{3,20})$#', $_POST['pseudo']); // Cette fonction me permet de mettre une règle en place pour les caractère autorisés :
	// arg 1 : REGEX - EXPRESSIONS REGULIERES
	// arg 2 : La CC
	// Retour: TRUE (si OK) - FALSE (si pas OK)

	if(!empty($_POST['pseudo'])){
		if(!$verif_pseudo){ // Si verif_pseudo nous retourne false
			$msg .= '<div class="erreur">Pseudo : Caractères autorisés (A à Z, et de 0 à 9), minimum 3 caractères, maximum 20 caractères.</div>';
		}
	}
	else{
		$msg .= '<div class="erreur">Veuillez renseigner un pseudo.</div>';
	}

	// Verification Mot de passe :
	$verif_pwd = preg_match('#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$#', $_POST['mdp']);  // 8 caractere min, 20 max, au moins un chiffre, au moins une MAJ.

	if(!empty($_POST['mdp'])){
		if(!$verif_pwd){
				$msg .= '<div class="erreur">Mot de passe : Veuillez renseigner 8 caractère minimum (20 max), au moins une majuscule et un chiffre.</div>';
		}
	}
	else{
		$msg .= '<div class="erreur">Veuillez renseigner un mot de passe.</div>';
	}


	// verification Email :
	$verif_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // Vérifie que le format de l'email est OK. Retourne TRUE (si OK) - FALSE (si pas OK).

	//yakine.hamida@gmail.com
	$pos = strpos($_POST['email'], '@'); // la position de @
	$ext = substr($_POST['email'], $pos +1); // 'gmail.com'

	$ext_non_autorisees = array('wimsg.com', 'yopmail.com', 'mailinator.com', 'tafmail.com', 'mvrht.net');

	if(!empty($_POST['email'])){

		if(!$verif_email || in_array($ext, $ext_non_autorisees)){
			$msg .= '<div class="erreur">Veuillez saisir un email valide.</div>';
		}
	}
	else{
		$msg .= '<div class="erreur">Veuillez renseigner un email.</div>';
	}


	// A ce stade, si notre variable $msg est encore vide, cela signifie qu'il n'y a pas d'erreur au moins sur email, pseudo et MDP (Pensez à faire les vérifs des autres champs).

	if(empty($msg)){ // validation des champs, ok : enregistrement
		// enregistrement du nouvel utilisateur :

		// Attention, le pseudo et le mail est-il disponible ?
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo ");
		$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
		$resultat -> execute();

		if($resultat -> rowCount() > 0){ // Signifie que le pseudo est déjà utilisé.

			// Nous aurions pu lui proposer 2/3 variantes de son pseudo, en ayant vérifié qu'ils sont disponibles.

			$msg .= '<div class="erreur">Le pseudo ' . $_POST['pseudo'] . ' n\'est pas disponible, veuillez choisir un autre pseudo.</div>';
		}
		else{ // OK le pseudo est dispo on va enregistrer le membre dans la BDD... (attention, nous devrions également vérifier la disponibilité de l'email.

			// crypte le MDP
			$mdp = md5($_POST['mdp']); // md5() va crypté le mdp selon en hashage 64o

			// requete INSERT
			$resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut)   VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, '0')   ");

			$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
			$resultat -> bindParam(':mdp', $mdp, PDO::PARAM_STR);
			$resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
			$resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
			$resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
			$resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);


			// redirection
			if($resultat -> execute()){ //Si la requete est OK!
				header('location:connexion.php');
			}
		} // fin du else rowCount()
	} // fin du if !empty $msg
} // fin du if !empty $_POST




// Pour maintenir les infos dans le formulaire, en cas d'erreur, on doit définir une variable pour chaque champs.

// if(isset($_POST['pseudo'])){
	// $pseudo = $_POST['pseudo'];
// }
// else{
	// $pseudo = '';
// }

// On peut écrire le if/else ci-dessus de manière simplifiée ;
$pseudo = 		(isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
$nom = 			(isset($_POST['nom'])) ? $_POST['nom'] : '';
$prenom = 		(isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$email = 		(isset($_POST['email'])) ? $_POST['email'] : '';
$civilite = 	(isset($_POST['civilite'])) ? $_POST['civilite'] : '';
$adresse = 		(isset($_POST['adresse'])) ? $_POST['adresse'] : '';
$ville = 		(isset($_POST['ville'])) ? $_POST['ville'] : '';
$cp = 	(isset($_POST['cp'])) ? $_POST['cp'] : '';

$page = 'Inscription';
require_once('inc/header.inc.php');
?>
<!-- CONTENU HTML -->
<h1>Inscription</h1>

<form method="post" action="">
	<?= $msg ?>
	<label>Pseudo:</label>
	<input type="text" name="pseudo" value="<?= $pseudo ?>" />
	<label>Mot de passe:</label>
	<input type="text" name="mdp" />
	<label>Nom:</label>
	<input type="text" name="nom" value="<?= $nom ?>"/>
	<label>Prénom::</label>
	<input type="text" name="prenom" value="<?= $prenom ?>"/>
	<label>Email:</label>
	<input type="text" name="email" value="<?= $email ?>"/>
	<label>Civilité</label>
	<select name="civilite">
		<option value="m" selected >Homme</option>
		<option value="f" <?= ($civilite == 'f') ? 'selected' : '' ?> >Femme</option>
	</select>
	<label>Ville:</label>
	<input type="text" name="ville" value="<?= $ville ?>"/>
	<label>Code Postal:</label>
	<input type="text" name="cp" value="<?= $cp ?>"/>
	<label>Adresse:</label>
	<input type="text" name="adresse" value="<?= $adresse ?>"/>
	<input type="submit" value="Inscription" />
</form>

<?php
require_once('inc/footer.inc.php');
?>
