# Guide pour lancer le projet

## Base de données

La base de données (PostgreSQL) utilisée est celle de test sur notre VPS.

## **PHP** 7.3

On utilisera la version dernière version 7.3 de **PHP**. On peut la télécharger ici :

https://windows.php.net/download/
https://windows.php.net/downloads/releases/php-7.3.17-nts-Win32-VC15-x64.zip (7.3.17)

## Symfony et lancer serveur local

pour lancer un serveur local dans le répertoire du projet, le plus simple est de servir le dossier /public
ou bien d'installer l'outil symfony-cli <https://symfony.com/download>

puis de lancer dans le répertoire du projet la commande `symfony server:start --no-tls`

Une version live du projet est aussi disponible à l'adresse <https://edt.sachafroment.fr>

## Documentation

une documentation pour installer l'environnement est disponible dans `/docs/`

## Code

le code utile se trouve essentiellement dans les répertoires :

- `/src/`
- `/templates/`
- `/config/packages/easy_admin.yaml`
