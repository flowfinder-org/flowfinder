# Contribuer à FlowFinder

[Contributing guide in English](./CONTRIBUTING.md)

Merci de votre intérêt pour contribuer à FlowFinder ! Nous accueillons les contributions de la communauté et sommes enthousiastes à l'idée de collaborer pour améliorer ce projet.

## Comment contribuer

Nous en sommes encore aux premières étapes du développement, donc il existe plusieurs façons pour vous de nous aider !

### 1. **Rapports de bugs**
Si vous trouvez des bugs ou des problèmes, veuillez les signaler en créant un problème sur GitHub. Soyez aussi détaillé que possible en décrivant le problème.

### 2. **Suggestions de fonctionnalités**
Si vous avez une idée pour une nouvelle fonctionnalité (comme un système de plugins pour les sources d'authentification), n'hésitez pas à la suggérer ! Créez un problème avec le label "enhancement" et fournissez une description claire de la fonctionnalité.

### 3. **Pull Requests**
Nous accueillons les pull requests ! Voici quelques directives :
- **Fork** le dépôt et créez votre branche (`git checkout -b feature/your-feature`).
- **Commitez** vos modifications avec des messages clairs et concis expliquant ce que vous avez fait.
- **Poussez** vos modifications vers votre dépôt forké et créez une pull request vers la branche `main`.
- Assurez-vous que votre code respecte les conventions de style existantes.
- Ajoutez des tests si nécessaire et assurez-vous que tous les tests passent.

### 4. **Priorités de développement**
Nous nous concentrons actuellement sur les fonctionnalités suivantes :
- **Système de Plugins** : Nous prévoyons d'ajouter un système de plugins pour prendre en charge plusieurs sources d'authentification. Cela remplacera les identifiants `admin/admin` en dur.
- **Exclusion des données sensibles pour rrweb** : Nous créons une interface permettant aux utilisateurs d'exclure des données sensibles des enregistrements de sessions.

Si vous souhaitez aider à l'une de ces tâches, faites-le nous savoir !

### 5. **Configuration de l'environnement de développement**
- Forkez le dépôt et clonez-le localement.
- Installez les dépendances avec Composer (`composer install`).
- Configurez votre base de données à l'aide des fichiers SQL fournis (`_database/_create_schema_for_dev.sql` et `_database/schema_version_00001.sql`).
- Assurez-vous que Apache est configuré avec le bon `DocumentRoot` et le fichier `.htaccess`.

### 6. **Code de conduite**
Veuillez être respectueux et attentif aux autres. Nous valorisons un environnement positif et inclusif pour tous les contributeurs.

## Lien vers la version anglaise
