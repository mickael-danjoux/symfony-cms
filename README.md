# Projet de base

## Projet
Le projet est une base Symfony 5 utilisant des librairies js et webpack  

### Prérequis
Symfony: https://symfony.com/download  
Node/Npm (LTS): https://nodejs.org/fr/

### Installation: 
* Installer les dépandances PHP: ``` composer install ```
* Installer les dépendances JS: ```npm install```
* Lancer le projet en local: ```npm start```

### Initialisation
Il est possible de créer la BDD, un compte admin et la première page de référencement via cette commande.
 ```symfony console app:project:init```

### Référencement
Le référencement se fait via le module d’administration.  
Il est possible de surcharger le bloque ```referencing``` pour mettre des balises custom.

