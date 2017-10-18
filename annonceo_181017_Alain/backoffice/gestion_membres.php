<?php
require_once ('../inc/init.inc.php');

if (isset($_GET['msg']) && $_GET['msg'] == 'validation' && isset($_GET['id'])){
    $msg .= '<div class="validation">Le membre n° '.$_GET['id'].' a été correctement enregistré !</div>';
}

if (isset($_GET['msg']) && $_GET['msg'] == 'suppr' && isset($_GET['id'])){
    $msg .= '<div class="validation">Le membre n° '.$_GET['id'].' a été correctement supprimé !</div>';
}

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
    $contenu .= '<a href="../inscription.php?id='.$val['id_membre'].'"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> ';
    $contenu .= ' <a href="supprimer_membre.php?id='.$val['id_membre'].'"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
    $contenu .= '</td>';
    $contenu .= '</tr>';
}
$contenu .= '</table>';


$page = 'gestion membres - ';
require_once ('../inc/header.inc.php');
?>

<!--  contenu HTML  -->
<h1>Gestion des membres</h1>
<div class="">

    <?= $contenu ?>
</div>

<div class="">

    <?php
    $pseudo = 		(isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
    $nom = 			(isset($_POST['nom'])) ? $_POST['nom'] : '';
    $prenom = 		(isset($_POST['prenom'])) ? $_POST['prenom'] : '';
    $email = 		(isset($_POST['email'])) ? $_POST['email'] : '';
    $telephone = 		(isset($_POST['telephone'])) ? $_POST['telephone'] : '';
    $civilite = 	(isset($_POST['civilite'])) ? $_POST['civilite'] : '';
    $statut = 	(isset($_POST['statut'])) ? $_POST['statut'] : '';
    ?>



    <form method="post" action="">
        <label >Pseudo</label>
        <input type="text" name="pseudo" value="<?= $pseudo ?>" placeholder="Pseudo"/>

        <label >Mot de passe</label>
        <input type="password" name="mdp" placeholder="Mot de passe"/>

        <label >Nom</label>
        <input type="text" name="nom" value="<?= $nom ?>" placeholder="Nom"/>

        <label >Prenom</label>
        <input type="text" name="prenom" value="<?= $prenom ?>" placeholder="Prénom"/>

        <label >Email</label>
        <input type="text" name="email" value="<?= $email ?>" placeholder="Email"/>

        <label >Téléphone</label>
        <input type="text" name="telephone" value="<?= $telephone ?>" placeholder="Téléphone"/>

        <label >Civilité</label>

        <select name="civilite">
            <option value="m" selected >Homme</option>
            <option value="f" <?= ($civilite == 'f') ? 'selected' : '' ?> >Femme</option>
        </select>

        <label >Statut</label>
        <select name="civilite">

            <option value="0" selected >Membre</option>
            <option value="1" <?= ($statut == '1') ? 'selected' : '' ?> >Admin</option>
        </select>

        <input type="submit" value="Enregistrer">
    </form>
</div>

<?php require_once ('../inc/footer.inc.php'); ?>
