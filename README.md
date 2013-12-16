#PrivateShare

@PuffTealerS - 16/12/13



Suite à une version très intéressante de PrivateShare (merci à Seriesme) mais qui n'a plus été tenu à jour,
j'ai repris le projet existant pour continuer de le coder.
Pour l'instant, les nouveautés sont :

- Modification du CSS
- Recupération via API AlloCiné Helper des affiches pour les 5 derniers fichiers 1080p&DVDRiP téléchargés
- Mise en place d'un changelog dans la partie admin (Ajout de version/Modification de version)
- Ajout des avatars (Ajout via profil)

Quelques screens : 
- <a href="http://www.zupmage.eu/i/6lr4y9Hwiv.png" targer="_blanlk">Login</a>
- <a href="http://www.zupmage.eu/i/Y4Pn2X1mRk.png" targer="_blanlk">Index</a>
- <a href="http://www.zupmage.eu/i/VeEVbEOsv3.png" targer="_blanlk">Stream</a>
- <a href="http://www.zupmage.eu/i/ER6LjKMwKe.png" target="_blank">Changelog</a>


___________________________________________________________________________________________________________________

/!\ A FAIRE AVANT INSTALLATION : /!\

Préparation de la bdd : Créer une bdd 'privateshare' puis importer PrivateShare.sql


Concernant l'installation, il faut le placer le dossier PrivateShare dans votre /var/www/ et d'éditer le fichier config.php

Un compte admin est déjà disponible:

- Login        : admin
- Mot de passe : admin


 Il est nécessaire de lancer run.php (logo refresh dans le menu) pour pouvoir update la bdd. 
 Si vous obtenez un index vide vérifiez votre config.php
__________________________________________________________________________________________________________________



D'autres nouveautés sont à venir ;).
