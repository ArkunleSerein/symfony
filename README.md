# Symfony

Ce répo contient une application de gestion de formation.
un projet pédagogique pour la promo 11.

## Pré-requis

- Linux, MacOS ou Windows
- Bash
- PHP 8
- Composer
- Symfony-cli
- MariaDB 10
- Docker (optionnel)

## Installation

```
git clone https://github.com/ArkunLeSerein/symfony
cd symfony
composer install
```

Créez une base de données et un utilisateur dédié pour cette base de données.

## Configuration

Créer un fichier `.env.local` à la racine du projet : 

```
APP_ENV=dev
APP_DEBUG=true
APP_SECRET=f575aea9844ab15b18648ee6c7ee2823
DATABASE_URL="mysql://symfony:123@127.0.0.1:3306/symfony?serverVersion=mariadb-10.6.12&charset=utf8mb4"
```
Pensez à adapter la variable `APP_SECRET` et les codes d'accès dans la variable `DATABASE_URL`.

**ATTENTION : `APP_SECRET` doit être une chaîne de caractère de 32 caractères en hexadécimal.**

## Migration et fixtures

Pour que l'application soit utilisable, vous devez créer le schéma de base de données et charger des données :

```
bin/dofilo.sh
```

## Utilisation

Lancez le serveur web de développement :

```
symfony serve
```

Puis ouvrez la page suivante : [https://localhost:8000](https://localhost:8000)


## Mention légales

Ce projet est sous license MIT.

La licence est disponible ici [MIT LICENCE](LICENCE).



