# FlowFinder

This page is also available in [English](README.md)

FlowFinder est une solution open-source pour enregistrer les sessions utilisateurs et collecter des retours via des formulaires de sondage. Ce projet auto-hébergé aide à analyser le comportement des utilisateurs sur les sites web et applications web.

Conçu à la fois pour les développeurs web et les équipes marketing, FlowFinder fournit des insights exploitables pour faire du A/B testing et de l’optimisation des taux de conversion (CRO), cela en fait un outil précieux pour améliorer l’expérience utilisateur digitale.

**Organisation** : [FlowFinder.org](https://flowfinder.org)  
**GitHub** : [flowfinder-org/flowfinder](https://github.com/flowfinder-org/flowfinder)  
**Licence** : AGPL 3.0

## Avertissements importants

- **Sécurité** : Le module d’authentification actuel est basique, avec un identifiant/mot de passe par défaut `admin/admin`. Ne l’installez pas en production ni ne le rendez accessible publiquement sans mettre en place une authentification plus sécurisée.
- **Conformité RGPD** : Cette version est conforme au RGPD.  
Les adresses IP des visiteurs sont temporairement stockées en clair afin d'en extraire la géolocalisation, puis elles sont **hachées avant toute première visualisation dans l'interface web**.
Il est possible d'activer l'enregistrement des sessions web via RRWEB. Dans ce cas, **vous êtes responsable de configurer RRWEB correctement** afin qu’il **n’enregistre pas de données sensibles**.
Vous pouvez activer ou désactiver l’enregistrement de certaines pages à l’aide d'expressions régulières, dans la section configuration.  
Par défaut, **l’enregistrement des sessions est désactivé**.

## Prérequis

- **PHP** : Version 8.2 ou supérieure.
- **MariaDB/MySQL** : La base de données a été testée avec MariaDB 10.6.
- **Extensions PHP requises** : PDO, mbstring, json, etc.
- **Serveur Web** : Apache avec le module mod_rewrite activé.

## Installation

[Le guide d'installation détaillé est disponible en anglais ici](./INSTALL.md)

1. **Base de données** :  
   Le schéma de la base de données se trouve dans le fichier `/_database/db_schema.sql`. Vous pouvez l’importer dans votre instance MariaDB ou MySQL.

2. **Configuration** :  
   Modifiez le fichier `Core/config.php` pour ajuster les paramètres de connexion à la base de données et les chemins de fichiers de session.

3. **Serveur Web** :  
   Définissez le dossier `/public` comme répertoire racine pour Apache. Le fichier `index.php` de ce dossier sert de point d’entrée de l’application.

4. **Sessions et enregistrements** :  
   Les enregistrements de sessions utilisateurs sont gérés via l’intégration de [rrweb](https://github.com/rrweb-io/rrweb), et les fichiers sont stockés dans le dossier `/_USER_FILES_PRIVATE/`. Vous pouvez configurer ce chemin et d’autres paramètres dans `Core/config.php`.

5. **Dépendances** :  
   Cette version utilise Composer pour gérer les dépendances comme `ua-parser/uap-php`. Lancez `composer install` à la racine du projet pour télécharger les bibliothèques nécessaires.

## Structure du projet

```
/Api -> Contrôleurs API 
/Controllers -> Contrôleurs Web (MVC) 
/Core -> Configuration et bibliothèques centrales (DB.php, config.php) 
/Helper -> Classes d’aide génériques 
/Models -> Modèles de base de données (PDO avec mappage manuel) 
/Views -> Vues (PHP comme moteur de template) 
/_database -> Fichier SQL du schéma de base de données 
/_USER_FILES_PRIVATE -> Fichiers des sessions rrweb 
/vendor -> Bibliothèques Composer 
/public -> Fichiers web publics (htdocs) avec .htaccess pour mod_rewrite 
/public/resources -> Ressources publiques (JS, CSS, IMG, JSON_EXEMPLE)
```

## Fonctionnalités

- **Replay de sessions** : Les sessions utilisateurs sont enregistrées via l’intégration de `rrweb`. Les replays sont consultables pour une analyse détaillée.
- **Formulaires de feedback/sondage** : Collecte de retours utilisateurs via des formulaires personnalisables.
- **Statistiques utilisateurs** : Statistiques de base, dont la durée moyenne des sessions et la provenance des utilisateurs, utiles pour l’A/B testing et l’analyse des performances marketing.
- **Auto-hébergement** : Ce projet est conçu pour être auto-hébergé avec une base MariaDB/MySQL et un serveur Apache.

## Documentation

La documentation complète est en cours de rédaction. Cette section sera mise à jour dès qu’elle sera disponible.

## Captures d’écran

Voici quelques captures d’écran pour vous donner un aperçu de l’application :

![Capture 1](public/ressources/github_assets/flowfinder_feedback_creation.jpg)  
*Création de sondages et formulaires de feedback facilitée avec un aperçu en direct du rendu, similaire à ce qui apparaîtra sur le site web cible.*

![Capture 2](public/ressources/github_assets/flowfinder_parametres_capture.jpg)  
*Vous pouvez définir sur quelles pages les enregistrements seront effectués et où les sondages et feedbacks seront affichés, grâce à des expressions régulières simplifiées.*

![Capture 3](public/ressources/github_assets/flowfinder_player.jpg)  
*Le lecteur web permet de rejouer les sessions des utilisateurs visitant votre site, utile pour le débogage, l’analyse UX et les actions de CRO (optimisation du taux de conversion).*

![Capture 4](public/ressources/github_assets/flowfinder_stats_visites.jpg)  
*Les statistiques sont affichées en temps réel. Vous pouvez filtrer par balises UTM ou par tags, ce qui permet de repérer immédiatement certains événements (ex. : augmentation des vues après une campagne marketing, baisse de la durée des sessions suite à un problème technique...).*

## Tester la version hébergée

Si vous souhaitez tester FlowFinder sans configurer d’instance auto-hébergée, vous pouvez essayer notre version cloud accessible sur [https://flowfinder.org](https://flowfinder.org).  
La version gratuite inclut toutes les fonctionnalités de cette version open-source, avec les limitations suivantes :

- **Limitation de débit** : Seul un nombre limité de sessions peut être enregistré par minute. Toutes les sessions ne sont pas garanties d’être stockées.
- **Rétention des données** : La durée de conservation des données peut varier selon la disponibilité et la capacité du serveur.

Cette option est idéale pour des tests rapides ou une utilisation à petite échelle sans configuration de serveur.

FlowFinder.org propose également des versions Cloud professionnelles ainsi que des services d’assistance technique.

## Comment contribuer

[Voir le guide de contribution ici](CONTRIBUTING.fr.md).
Il contient des informations sur les priorités du projet.

## Licence

FlowFinder est sous licence **AGPL 3.0**. Vous pouvez l’utiliser, le modifier et le redistribuer, à condition de rendre votre code source également disponible sous les mêmes conditions.