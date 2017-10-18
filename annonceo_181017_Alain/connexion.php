<?php
require_once('inc/init.inc.php');



// traitement pour la déconnexion :
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
// Si une action est demandée dans l'url... et que cette action est "déconnexion" alors on procède à la deconnexion.

	unset($_SESSION['membre']);
	header('location:connexion.php');
}

// Traitement pour rediriger l'utilisateur s'il est déjà connecté
if(userConnecte()){
	header('location:index.php');
}


// Formulaire activé ?
// Debug() pour vérifier
// On vérifie que les deux champs sont pas vides
// On connecte le membre en enregistrant ses infos dans la session
	//-> Le membre existe-t-il en BDD ?
	//-> Est-ce le MDP saisi correspond à celui en BDD
	//-> Enregistrement en SESSION
	//-> Redirection vers profil

if(!empty($_POST)){

	debug($_POST);

	if(!empty($_POST['pseudo']) && !empty($_POST['mdp'])){

		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
		$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
		$resultat -> execute();

		if($resultat -> rowCount() > 0){ // ok le pseudo existe bien
			$membre = $resultat -> fetch(PDO::FETCH_ASSOC); // On récupère toutes les infos du membre qui souhaite se connecter sous la forme sd'un ARRAY.

			if($membre['mdp'] == md5($_POST['mdp'])){
			//si(mdp_crypté_en_bdd == mdp saisi + crypté... ALORS TOUT EST OK)

				// TOUT EST OK on peut connecter l'utilisateur :

				foreach($membre as $indice => $valeur){
					if($indice != 'mdp'){
						$_SESSION['membre'][$indice] = $valeur;
					}
				}

				//debug($_SESSION)
				// redirection

				header('location:index.php');

			}
			else{
				$msg .= '<div class="erreur">Mot de passe erroné !</div>';
			}
		}
		else{
			$msg .= '<div class="erreur">Pseudo erroné !</div>';
		}
	}
	else{
		$msg .= '<div class="erreur">Veuillez renseigner un Pseudo et un Mot de passe.</div>';
	}
}

$page = 'Connexion';
require('inc/header.inc.php');
?>
<!-- CONTENU HTML -->
<h1>Connexion</h1>
<form method="post" action="">
	<?= $msg ?>
	<label>Pseudo :</label>
	<input type="text" name="pseudo"/>
	<label>Mot de passe :</label>
	<input type="password" name="mdp" />
	<input type="submit" value="Connexion" />
</form>
<?php
require_once('inc/footer.inc.php');
?>
