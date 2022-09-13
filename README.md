# Wordpress template

## What is included

## Installation

```bash
$ lando start
```



###Demande d'authentification lors du composer install 

#### Identifiant

licensekey
####password (Clé ACF)
https://codex.adeliom.com/books/wordpress/page/plugins-achet%C3%A9s
Répondre y

#### Identifiant

licensekey
####password (Clé GF)
https://codex.adeliom.com/books/wordpress/page/plugins-achet%C3%A9s

Si le .env et le .env.local n'est pas généré, il faut relancer un composer install :

```bash
$ lando composer install
```

## Infos utiles sur lando

```bash
$ lando start
```

## WP CLI

```bash
$ https://kinsta.com/fr/blog/wp-cli/
```

## Base de données

- Ajouter phpMyAdmin dans le fichier `.lando.yml` dans la partie service

```
  pma:
  type: phpmyadmin
```

- Utiliser le logiciel TablePlus
- Utiliser l'interface de phpMyAdmin
