CREATE TABLE `annonce` (
id_annonce int(3),
titre varchar(255),
description_courte varchar(255),
description_longue text,
prix int(4),
photo varchar(200),
pays varchar(20),
ville varchar(20),
adresse varchar(50),
cp int(5),
membre_id int(3) ,
photo_id int(3) ,
categorie_id int(3) ,
date_enregistrement datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `annonce`
  ADD PRIMARY KEY (`id_annonce`);

ALTER TABLE `annonce`
  MODIFY `id_annonce` int(3) NOT NULL AUTO_INCREMENT;COMMIT;

CREATE TABLE `photo` (
id_photo int(3),
photo1 varchar(255),
photo2 varchar(255),
photo3 varchar(255),
photo4 varchar(255),
photo5 varchar(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `photo`
  ADD PRIMARY KEY (`id_photo`);

ALTER TABLE `photo`
  MODIFY `id_photo` int(3) NOT NULL AUTO_INCREMENT;COMMIT;

CREATE TABLE `categorie` (
id_categorie int(3),
titre varchar (255),
motscles text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

ALTER TABLE `categorie`
  MODIFY `id_categorie` int(3) NOT NULL AUTO_INCREMENT;COMMIT;

CREATE TABLE `commentaire` (
id_commentaire int (3),
membre_id int (3),
annonce_id int (3),
commentaire text,
date_enregistrement datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`);

ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(3) NOT NULL AUTO_INCREMENT;COMMIT;

CREATE TABLE `note` (
id_note int (3),
membre_id1 int (3),
membre_id2 int (3),
note int (3),
avis text,
date_enregistrement datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `note`
  ADD PRIMARY KEY (`id_note`);

ALTER TABLE `note`
  MODIFY `id_note` int(3) NOT NULL AUTO_INCREMENT;COMMIT;

CREATE TABLE `membre` (
id_membre int(3),
pseudo varchar(20),
mdp varchar(60),
nom varchar(20),
prenom varchar(20),
telephone varchar(20),
email varchar(50),
civilite enum('m','f'),
statut int(1),
date_enregistrement datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`);

ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT;COMMIT;
