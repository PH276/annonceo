<?php
require_once ('../inc/init.inc.php');
if (!userAdmin()){
    header('location:../connexion.php');
}

if (!empty($_POST)){
    debug($_POST);
    debug($_FILES);

    // renommer photo / ref_time_nom.ext
    // ctrl sur la photo
    // Enregistrer la photo
    //
    // ctrl sur les infos
    // requete insererr les infos ds la BD
    // redirection sur gestion_boutique


    $nom_photo = 'default.jpg';

    // en cas de modification, recuperation de l'ancienne photo  avant une éventuelle modif de la photo (traité ci-dessous)
    if (isset($_POST['photo_actuelle'])){
        $nom_photo = $_POST['photo_actuelle'];
    }




    if (!empty($_FILES['photo']['name'])){
        $nom_photo = $_POST['reference'].'_'.time().'_'.$_FILES['photo']['name']; // photo renommer pour éviter les doublons

        $chemin_photo = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'photo/' . $nom_photo;
        // chemin :
        // c:\\xampp/htdocs  PHP/site/    photo/    nom_photo

        // vérification si le fichier est de type image
        $ext = array('image/png', 'image/jpeg', 'image/gif');
        if (!in_array($_FILES['photo']['type'], $ext)){
            $msg .= '<div class="erreur">Images autorisées de type : PNG, JPG et GIF</div>';
        }

        // vérification de la taille maxi d'une image
        if ($_FILES['photo']['size'] > 2000000){
            $msg .= '<div class="erreur">taille maximum des photos : 2 Mo</div>';
        }

        if (empty($msg) && $_FILES['photo']['size']){
            copy($_FILES['photo']['tmp_name'], $chemin_photo);
        }
    }
    // insérer les infos du produit en BDD...
    // Au préalable, nous autions vérifier ts les ch (taille , caractères, no_empty, ...)

    if (empty($msg)){

        if (isset($_POST['Modifier'])) {
            $resultat = $pdo->prepare("UPDATE produit SET
                reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo = :photo, prix = :prix, stock = :stock WHERE id_produit = :id_produit");

                $resultat->bindParam(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
            }else {
                $resultat = $pdo->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES
                (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");
            }

            $resultat->bindParam(':reference', $_POST['reference'], PDO::PARAM_STR);
            $resultat->bindParam(':categorie', $_POST['categorie'], PDO::PARAM_STR);
            $resultat->bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
            $resultat->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
            $resultat->bindParam(':couleur', $_POST['couleur'], PDO::PARAM_STR);
            $resultat->bindParam(':taille', $_POST['taille'], PDO::PARAM_STR);
            $resultat->bindParam(':public', $_POST['public'], PDO::PARAM_STR);
            $resultat->bindParam(':photo', $nom_photo, PDO::PARAM_STR);
            $resultat->bindParam(':prix', $_POST['prix'], PDO::PARAM_STR);
            $resultat->bindParam(':stock', $_POST['stock'], PDO::PARAM_INT);

            if ($resultat->execute()){
                $pdt_insert = (isset($_POST['Modifier'])) ? $_POST['id_produit'] : $pdo -> lastInsertId();
                header('location:gestion_boutique.php?msg=validation&id='.$pdt_insert);
            }
        }

    }
    // fin !empty($_POST
    // traitement pour modifier un produit
    // 1/ oon récupère les infos duu produit
    // 2/ on insert les infos de ce produit ds le formulaire
    // 3/ gestion de la photo : si on modifie un autre champ, il faut renvoyer l'ancienne image. Si on la modifie, il faut pouvoir la récupérer
    // 4/ enregistrement des modifs

    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))    {
        // s'il y a un id ds l'url non vide et numérique
        $resultat = $pdo->prepare("SELECT * FROM produit WHERE id_produit = ?");
        $resultat->execute(array($_GET['id']));

        if ($resultat->rowCount() > 0){
            $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC);
            debug($produit_actuel);


        }
    }

    // Créons des variables pour chq elt à insérer ds le formulaire
    $id_produit = (isset($produit_actuel)) ? $produit_actuel['id_produit'] : '';

    $reference = (isset($produit_actuel)) ? $produit_actuel['reference'] : '';
    $categorie = (isset($produit_actuel)) ? $produit_actuel['categorie'] : '';
    $titre = (isset($produit_actuel)) ? $produit_actuel['titre'] : '';
    $description = (isset($produit_actuel)) ? $produit_actuel['description'] : '';
    $couleur = (isset($produit_actuel)) ? $produit_actuel['couleur'] : '';
    $taille = (isset($produit_actuel)) ? $produit_actuel['taille'] : '';
    $public = (isset($produit_actuel)) ? $produit_actuel['public'] : '';
    $prix = (isset($produit_actuel)) ? $produit_actuel['prix'] : '';
    $stock = (isset($produit_actuel)) ? $produit_actuel['stock'] : '';
    $photo = (isset($produit_actuel)) ? $produit_actuel['photo'] : '';

    $action = (isset($produit_actuel)) ? 'Modifier' : 'Ajouter';


    $page='gboutique';

    // Traitement pour l'iscription
    //  --> vérif form activé
    //  --> print_r
    //  --> contrôle des ch pseudo et mdp
    // --> Enregistrer l'utilisateur
    //     --> pseudo dispo ? / email dispo
    //     --> redirection vers la connexion


    require_once ('../inc/header.inc.php');
    ?>
    <!--  contenu HTML  -->
    <h1><?= $action ?> un produit</h1>
    <!-- <?php extract($_POST); ?> -->
    <form method="post" action="" enctype="multipart/form-data">
        <?= $msg  ?>

        <input type="text" name="id_produit" value="<?= $id_produit ?>" hidden>

        <label for="ref">Référence</label>
        <input type="text" id="ref" name="reference" value="<?= $reference ?>">

        <label for="cat">Catégorie</label>
        <input type="text" id="cat" name="categorie" value="<?= $categorie ?>">

        <label for="titre">Titre</label>
        <input  type="text" name="titre" value="<?= $titre ?>">

        <label for="description">Description</label>
        <textarea name="description" rows="8" cols="80"><?= $description ?></textarea>

        <label for="couleur">Couleur</label>
        <input type="text" id="couleur" name="couleur" value="<?= $couleur ?>">

        <label for="taille">Taille</label>
        <input type="text" id="taille" name="taille" value="<?= $taille ?>">

        <label for="public">Public</label>
        <select name="public" id="public">
            <option <?= $public=='m'?' selected ':'' ?>value="m">Homme</option>
            <option <?= $public=='f'?' selected ':'' ?>value="f">Femme</option>
            <option <?= $public=='mixte'?' selected ':'' ?>value="mixte">Mixte</option>
        </select>

        <?php if (isset($produit_actuel)) : ?>
            <img src="<?= RACINE_SITE ?>photo/<?= $photo ?>" alt="" height="100">
            <input type="hidden" name="photo_actuelle" value="<?= $photo ?>">
        <?php endif; ?>

        <label for="photo">Photo</label>
        <input type="file" id="photo" name="photo">

        <label for="prix">Prix</label>
        <input type="text" id="prix" name="prix" value="<?= $prix ?>">

        <label for="stock">Stock</label>
        <input type="text" id="stock" name="stock" value="<?= $stock ?>">


        <input type="submit" name="<?= $action ?>" value="<?= $action ?>">


    </form>
