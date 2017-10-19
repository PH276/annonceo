<?php
require_once ('../inc/init.inc.php');
if(!empty($_POST)){
    debug($_POST);

    if(empty($_POST['pseudo'])){
        $msg .= '<div class="erreur">Veuillez renseigner un pseudo.</div>';
    }

    if(empty($_POST['nom'])){
        $msg .= '<div class="erreur">Veuillez renseigner un nom.</div>';
    }

    if(empty($_POST['prenom'])){
        $msg .= '<div class="erreur">Veuillez renseigner un prénom.</div>';
    }

    if(empty($_POST['email'])){
        $msg .= '<div class="erreur">Veuillez renseigner un email.</div>';
    }

    if(empty($_POST['telephone'])){
        $msg .= '<div class="erreur">Veuillez renseigner un telephone.</div>';
    }

    if(empty($msg)){ // validation des champs, ok : enregistrement
        echo 'ok ';
        // enregistrement du nouvel utilisateur :

        $resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo ");
        $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $resultat -> execute();

        if($resultat -> rowCount() > 0){ // Signifie que le pseudo est déjà utilisé.
            $id_membre = $resultat -> fetch();
            // Nous aurions pu lui proposer 2/3 variantes de son pseudo, en ayant vérifié qu'ils sont disponibles.

            // requete INSERT
            $resultat = $pdo -> prepare("UPDATE FROM membre SET pseudo = :pseudo ,  nom=:nom ,  prenom=:prenom ,  telephone=:telephone ,  email=:email ,  civilite=:civilite ,  statut=:statut WHERE id_membre = $id_membre");

            $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
            $resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
            $resultat -> bindParam(':telephone', $_POST['telephone'], PDO::PARAM_INT);
            $resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);
            $resultat -> bindParam(':statut', $_POST['statut'], PDO::PARAM_STR);

            echo ($resultat -> execute());
        } // fin du else rowCount()
    } // fin du if !empty $msg
}

$id = (isset($_GET['id']) && !empty($_GET['id']))?$_GET['id']:'';

// if (isset($_GET['msg']) && $_GET['msg'] == 'validation' && isset($_GET['id'])){
//     $msg .= '<div class="validation">Le membre n° '.$_GET['id'].' a été correctement enregistré !</div>';
// }
//
// if (isset($_GET['msg']) && $_GET['msg'] == 'suppr' && isset($_GET['id'])){
//     $msg .= '<div class="validation">Le membre n° '.$_GET['id'].' a été correctement supprimé !</div>';
// }

$resultat = $pdo->query('SELECT * FROM membre');

$membres = $resultat -> fetchAll(PDO::FETCH_ASSOC);
// $contenu .=  '<br>Nombre de membres : '.$resultat->rowCount().'<br><hr>';

$contenu .= '<table border=1 class="table table-bordered">';
$contenu .= '<tr>';

for($i=0;$i < $resultat->columnCount();$i++){
    $colonne = $resultat->getColumnMeta($i);
    if ($colonne['name']!='mdp'){
        $contenu .=  '<th>';
        $contenu .=  $colonne['name'];
        $contenu .= '</th>';
    }
}
$contenu .=  '<th>';
$contenu .=  'Action';
$contenu .= '</th>';

$contenu .= '</tr>';
foreach ($membres as $val){
    $contenu .= '<tr>';
    foreach($val as $key => $val2){
        if ($key !='mdp'){
            $contenu .= '<td>';
            switch ($key) {
                case 'civilite' :
                $contenu .=  ($val2 == 'f')?'Femme':'Homme';
                break;

                case 'statut' :
                $contenu .=  ($val2 == '1')?'admin':'membre';
                break;

                default :
                $contenu .=  $val2;
            }


            $contenu .= '</td>';
        }
    }
    $contenu .= '<td>';
    $contenu .= '<a href="?id='.$val['id_membre'].'"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> ';
    $contenu .= ' <a href="supprimer_membre.php?id='.$val['id_membre'].'"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
    $contenu .= '</td>';
    $contenu .= '</tr>';
}
$contenu .= '</table>';

$page = 'gestion membres - ';
require_once ('../inc/header.inc.php');
debug($_POST);
$pseudo = 		(isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
$nom = 			(isset($_POST['nom'])) ? $_POST['nom'] : '';
$prenom = 		(isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$email = 		(isset($_POST['email'])) ? $_POST['email'] : '';
$telephone = 		(isset($_POST['telephone'])) ? $_POST['telephone'] : '';
$civilite = 	(isset($_POST['civilite'])) ? $_POST['civilite'] : '';
$statut = 	(isset($_POST['statut'])) ? $_POST['statut'] : '';
?>

<!--  contenu HTML  -->
<h1>Gestion des membres</h1>
<div class="">
    <?= $contenu ?>
</div>
<?= $msg  ?>
<?php require_once ('../inc/formulaire_profil.inc.php'); ?>
<?php require_once ('../inc/footer.inc.php'); ?>
