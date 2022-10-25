# Projet de base

## Projet
Le projet est une base Symfony 5 utilisant des librairies js et webpack  

### Prérequis
Symfony: https://symfony.com/download  
Node/Npm (LTS): https://nodejs.org/fr/

### Installation:
* Installer les dépendances PHP: ```symfony composer install ```
* Installer les assets PHP: ```symfony assets:install ```
* Créer un fichier .env.local et créer la BDD local
* Installer les dépendances JS: ```npm install```
* Lancer le projet en local: ```npm start```

### Initialisation
Il est possible d'initialiser le projet en entrant cette commande
 ```symfony console app:project:init```  
(Initialisation de la BDD et création des données nécessaires au fonctionnement)  
La liste des pages par défaut se trouve dans ```src/Factories/Page/DefaultPagesArray```

### Pages et Référencement
La gestion du menu et des pages se fait via le module d’administration.  
Il est possible de gérer le référencement de ces pages.

### Logs Sentry
Le module de gestion des logs est installé par défaut.
Il faut changer la clé ```SENTRY_DSN``` dans le fichier .env
Il est possible de tester la commande :
```symfony app:sentry:test```  
Le log apparait dans la console et dans l'interface de Sentry (https://sentry.ab6net.net)

