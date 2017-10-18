<?php
require_once ('../inc/init.inc.php');

if (isset($_GET['msg']) && $_GET['msg'] == 'validation' && isset($_GET['id'])){
    $msg .= '<div class="validation">L\'annonce n° '.$_GET['id'].' a été correctement enregistrée !</div>';
}

if (isset($_GET['msg']) && $_GET['msg'] == 'suppr' && isset($_GET['id'])){
    $msg .= '<div class="validation">L\'annonce n° '.$_GET['id'].' a été correctement supprimée !</div>';
}

$resultat = $pdo->query('SELECT id_annonce,
    a.titre,
    description_courte,
    description_longue,
    prix,
    photo,
    pays,
    ville,
    adresse,
    cp,
    prenom membre,
    c.titre categorie,
    a.date_enregistrement
    FROM annonce a
    LEFT JOIN membre m ON a.membre_id=m.id_membre
    LEFT JOIN categorie c ON a.categorie_id=c.id_categorie');
    $membres = $resultat -> fetchAll(PDO::FETCH_ASSOC);
    // $contenu .=  '<br>Nombre de membres : '.$resultat->rowCount().'<br><hr>';

    $contenu .= '<table border=1 class="table table-bordered">';
    $contenu .= '<tr>';

    for($i=0;$i < $resultat->columnCount();$i++){
        $colonne = $resultat->getColumnMeta($i);
        $contenu .=  '<th>';
        $contenu .=  str_replace('_', '<br>', $colonne['name']);

        $contenu .= '</th>';
    }
    $contenu .=  '<th>';
    $contenu .=  'Action';
    $contenu .= '</th>';

    $contenu .= '</tr>';
    foreach ($membres as $val){
        $contenu .= '<tr>';
        foreach($val as $key => $val2){
            // if ($key !='membre_id'){
            $contenu .= '<td>';
            if ($key == 'photo'){
                if (file_exists('../photos/'.$val2)){
                    $contenu .=  '<img src="'.'../photos/'.$val2.'" alt="">';
                    $contenu .= '<p><a href="#">Voir les autres photos</a></p>';
                }
                else{
                    $contenu .=  '';
                }
            } else{
                $contenu .= $val2;
            }

            // }
            $contenu .= '</td>';
        }

        $contenu .= '<td>';
        $contenu .= '<a href="../inscription.php?id='.$val['id_annonce'].'"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> ';
        $contenu .= ' <a href="supprimer_membre.php?id='.$val['id_annonce'].'"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
        $contenu .= '</td>';
        $contenu .= '</tr>';
    }
    $contenu .= '</table>';

    $page = 'gestion annonces - ';
    require_once ('../inc/header.inc.php');
    ?>
    <!--  contenu HTML  -->
    <h1>Gestion des annonces</h1>
    <section class="row">
        <div class="col-md-4">
            <select>
                <option value="">Trier par catégorie</option>
                <option value="">Trier par membre</option>
                <option value="">Trier par date d'enregistrement</option>
            </select>
        </div>
    </section>
    <hr>
    <section class="row">
        <div class="col-md-12">
            <?= $contenu ?>
        </div>
    </section>


    <?php require_once ('../inc/footer.inc.php'); ?>
