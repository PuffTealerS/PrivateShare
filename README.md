#PrivateShare

@PuffTealerS - 15/12/13



Suite à une version très intéressante de PrivateShare (merci à Seriesme) mais qui n'a plus été tenu à jour,
j'ai repris le projet existant pour continuer de le coder.
Pour l'instant, les nouveautés sont :

- Modification du CSS
- Ajout : Récupération pour les 1080p (qui ne sont pas dans des dossiers) de l'image de l'affiche (via allociné)
- Affichage des 5 affiches des films 1080p les plus récents sur l'index
- Mise en place d'un changelog dans la partie admin (Ajout de version/Modification de version)
- Ajout des avatars, il y en a un par défaut

_________________________________________________________________________________________________________________

/!\ A FAIRE AVANT INSTALLATION : /!\

Préparation de la bdd : Créer une bdd 'privateshare' puis importer PrivateShare.sql


Concernant l'installation, il faut le placer le dossier PrivateShare dans votre /var/www/ et d'éditer le fichier config.php

Un compte admin est déjà disponible:

 *login : admin
 *pwd   : admin


 Il est nécessaire de lancer run.php (logo refresh dans le menu) pour pouvoir update la bdd. 
 Si vous obtenez un index vide vérifiez votre config.php
__________________________________________________________________________________________________________________



D'autres nouveautés sont à venir ;).
