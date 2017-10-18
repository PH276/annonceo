<!Doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <!-- Bootstrap -->
    <link href="<?= RACINE_ANNONCEO ?>css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= RACINE_ANNONCEO ?>css/style.css"/>
    <title><?= $page ?>Annonceo</title>
</head>
<body>
    <header>
        <!-- <div class="conteneur"> -->
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?= RACINE_ANNONCEO ?>index.php">Annonceo</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#">Qui sommes-nous ?<span class="sr-only">(current)</span></a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                        <form class="navbar-form navbar-left">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Recherche...">
                            </div>
                            <!-- <button type="submit" class="btn btn-default">Submit</button> -->
                        </form>
                        <ul class="nav navbar-nav navbar-right">
                            <?php if(userConnecte()) : ?>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Espace membre<span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a  class="<?=  ($page == 'Profil') ? 'active' : ''  ?>" href="<?= RACINE_ANNONCEO ?>profil.php">Profil</a></li>
                                        <?php if(userAdmin()) :	?>
                                            <li><a  class="<?=  ($page == 'Gestion Categories') ? 'active' : ''  ?>" href="<?= RACINE_ANNONCEO ?>backoffice/gestion_categories.php">Gestion Categories</a></li>
                                            <li><a  class="<?=  ($page == 'Gestion Membres') ? 'active' : ''  ?>" href="<?= RACINE_ANNONCEO ?>backoffice/gestion_membres.php">Gestion Membres</a></li>
                                            <li><a  class="<?=  ($page == 'Gestion Annonces') ? 'active' : ''  ?>"  href="<?= RACINE_ANNONCEO ?>backoffice/gestion_annonces.php">Gestion Annonces</a></li>
                                            <li><a  class="<?=  ($page == 'Gestion Commentaires') ? 'active' : ''  ?>"  href="<?= RACINE_ANNONCEO ?>backoffice/gestion_commentaires.php">Gestion Commentaires</a></li>
                                            <li><a  class="<?=  ($page == 'Gestion Notes') ? 'active' : ''  ?>"  href="<?= RACINE_ANNONCEO ?>backoffice/gestion_notes.php">Gestion Notes</a></li>
                                        <?php endif; ?>
                                        <li><a href="<?= RACINE_ANNONCEO ?>connexion.php?action=deconnexion">Déconnexion</a>
                                        </ul>
                                    </li>

                                <?php else :  ?>
                                    <li><a  class="<?=  ($page == 'Inscription') ? 'active' : ''  ?>" href="<?= RACINE_ANNONCEO ?>inscription.php">Inscription</a></li>
                                    <li><a  class="<?=  ($page == 'Connexion') ? 'active' : ''  ?>" href="<?= RACINE_ANNONCEO ?>connexion.php">Connexion</a></li>
                                <?php endif; ?>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
            <!-- </div> -->
        </header>
        <main>
            <div class="container-fluid">
