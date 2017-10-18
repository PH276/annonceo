<?php
require_once ('inc/init.inc.php');
debug($_SESSION);
$id_membre = $_SESSION['membre']['id_membre'];
$id = $id_membre;

$resultat = $pdo->query('SELECT * FROM membre ');
// $resultat = $pdo->query('SELECT * FROM membre WHERE id_membre=:id_membre');
$resultat -> bindParam(':id_membre', $id_membre, PDO::PARAM_INT);
$membre = $resultat -> fetch(PDO::FETCH_ASSOC);

$page = 'Profil - ';
require_once ('inc/header.inc.php');
?>

<!--  contenu HTML  -->
<?php extract($_SESSION['membre']); ?>

<h1>Profil de <?= $prenom; ?></h1>

<?php require_once ('inc/formulaire_profil.inc.php'); ?>
<?php require_once ('inc/footer.inc.php'); ?>
