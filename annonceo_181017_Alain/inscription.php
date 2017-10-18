<?php
require_once('inc/init.inc.php');

// Traitement pour rediriger l'utilisateur s'il est déjà connecté
if(userConnecte()){
	header('location:index.php');
}



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

	if(empty($_POST['pseudo'])){
		$msg .= '<div class="erreur">Veuillez renseigner un pseudo.</div>';
	}

	// Verification Mot de passe :

	if(empty($_POST['nom'])){
		$msg .= '<div class="erreur">Veuillez renseigner votre nom.</div>';
	}

	if(empty($_POST['prenom'])){
		$msg .= '<div class="erreur">Veuillez renseigner votre prénom.</div>';
	}


	// verification Email :
	// $verif_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // Vérifie que le format de l'email est OK. Retourne TRUE (si OK) - FALSE (si pas OK).
	//
	// //yakine.hamida@gmail.com
	// $pos = strpos($_POST['email'], '@'); // la position de @
	// $ext = substr($_POST['email'], $pos +1); // 'gmail.com'
	//
	// $ext_non_autorisees = array('wimsg.com', 'yopmail.com', 'mailinator.com', 'tafmail.com', 'mvrht.net');

	if(empty($_POST['email'])){


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
			$resultat = $pdo -> prepare("INSERT INTO  membre ( pseudo ,  mdp ,  nom ,  prenom ,  telephone ,  email ,  civilite ,  statut ,  date_enregistrement ) VALUES ( :pseudo ,  :mdp ,  :nom ,  :prenom ,  :telephone ,  :email ,  :civilite ,  '0' ,  now() )");

			$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
			$resultat -> bindParam(':mdp', $mdp, PDO::PARAM_STR);
			$resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
			$resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
			$resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
			$resultat -> bindParam(':telephone', $_POST['telephone'], PDO::PARAM_STR);
			$resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);


			// redirection
			if($resultat -> execute()){ //Si la requete est OK!
				header('location:connexion.php');
			}
			else {
				$msg = 'erreur SQL(insertion)';
			}
		} // fin du else rowCount()
	} // fin du if !empty $msg
} // fin du if !empty $_POST


// On peut écrire le if/else ci-dessus de manière simplifiée ;
$pseudo = 		(isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
$nom = 			(isset($_POST['nom'])) ? $_POST['nom'] : '';
$prenom = 		(isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$email = 		(isset($_POST['email'])) ? $_POST['email'] : '';
$telephone = 		(isset($_POST['telephone'])) ? $_POST['telephone'] : '';
$civilite = 	(isset($_POST['civilite'])) ? $_POST['civilite'] : '';

$page = 'Inscription - ';
require_once('inc/header.inc.php');
?>
<!-- CONTENU HTML -->
<h1>S'inscrire</h1>
<div>
	<form method="post" action="">
		<?= $msg ?>
		<input type="text" name="pseudo" value="<?= $pseudo ?>" placeholder="Votre Pseudo"/>
		<input type="password" name="mdp" placeholder="Votre mot de passe"/>
		<input type="text" name="nom" value="<?= $nom ?>" placeholder="Votre nom"/>
		<input type="text" name="prenom" value="<?= $prenom ?>" placeholder="Votre prénom"/>
		<input type="text" name="email" value="<?= $email ?>" placeholder="Votre email"/>
		<input type="text" name="telephone" value="<?= $telephone ?>" placeholder="Votre téléphone"/>
		<select name="civilite">
			<option value="m" selected >Homme</option>
			<option value="f" <?= ($civilite == 'f') ? 'selected' : '' ?> >Femme</option>
		</select>
		<input type="submit" value="Inscription">
	</form>
</div>

<?php
require_once('inc/footer.inc.php');
?>
