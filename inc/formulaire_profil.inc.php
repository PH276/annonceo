<div class="">
    <form method="post" action="">
        <label >Pseudo</label>
        <input type="text" name="pseudo" value="<?= $pseudo ?>" placeholder="Pseudo"/>

        <?php if (!userAdmin() || isset($tatut) && $tatut == '1' && $id_membre == $id) : ?>
            <label >Mot de passe</label>
            <input type="password" name="mdp" placeholder="Mot de passe"/>
        <?php endif; ?>

        <label >Nom</label>
        <input type="text" name="nom" value="<?= $nom ?>" placeholder="Nom"/>

        <label >Prénom</label>
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

        <?php if(userAdmin()) : ?>
            <label >Statut</label>
            <select name="statut">
                <option value="0" selected >Membre</option>
                <option value="1" <?= ($statut == '1') ? 'selected' : '' ?> >Admin</option>
            </select>
        <?php endif; ?>

        <input type="submit" value="Enregistrer">
    </form>
</div>
