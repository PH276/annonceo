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
$resultat = $pdo->query('SELECT * FROM commentaire');
$commentaires = $resultat -> fetchAll(PDO::FETCH_ASSOC);
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
foreach ($commentaires as $val){
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
<h1>Gestion des commentaires</h1>
<section class="">
    <?= $contenu ?>
</section>

<?php require_once ('../inc/footer.inc.php'); ?>
