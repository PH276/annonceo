<?php
require_once ('../inc/init.inc.php');

// if (isset($_GET['msg']) && $_GET['msg'] == 'validation' && isset($_GET['id'])){
//     $msg .= '<div class="validation">Le membre n° '.$_GET['id'].' a été correctement enregistré !</div>';
// }
//
// if (isset($_GET['msg']) && $_GET['msg'] == 'suppr' && isset($_GET['id'])){
//     $msg .= '<div class="validation">Le membre n° '.$_GET['id'].' a été correctement supprimé !</div>';
// }
//
$resultat = $pdo->query('SELECT * FROM categorie');
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
            $contenu .=  $val2;
            $contenu .= '</td>';
        }
    }
    $contenu .= '<td>';
    $contenu .= '<a href="?id='.$val['id_categorie'].'"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> ';
    $contenu .= ' <a href="?id='.$val['id_categorie'].'"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
    $contenu .= '</td>';
    $contenu .= '</tr>';
}
$contenu .= '</table>';


$page = 'gestion catégories - ';
require_once ('../inc/header.inc.php');
?>

<!--  contenu HTML  -->
<h1>Gestion des catégories</h1>
<section class="">
    <?= $contenu ?>
</section>
<hr>
<section class="row">
    <div class="col-md-12">

        <?php
        $titre = 	(isset($_POST['titre'])) ? $_POST['titre'] : '';
        $motscles = 	(isset($_POST['motscles'])) ? $_POST['motscles'] : '';
        ?>

        <form method="post" action="">
            <label >Titre</label>
            <input type="text" name="titre" value="<?= $titre ?>" placeholder="Titre de la catégorie"/>

            <label >Mots clés de la catégorie</label>
            <textarea name="motscles" rows="3" cols="80" placeholder="Mots clés de la catégorie"><?= $motscles ?></textarea>

            <input type="submit" value="Enregistrer">
        </form>
    </div>
</section>

<?php require_once ('../inc/footer.inc.php'); ?>
