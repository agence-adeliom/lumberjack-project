# Projet example

## 🧐 À propos

ÉCRIRE QUELQUES LIGNES A PROPOS DU PROJET

**Date de création** : dd/mm/yyyy

## 📄 Documents

ℹ️ [Fiche projet Miro](https://miro.com/app/board/[BOARD_ID]/)

🖌 [Figma](https://www.figma.com/file/[FILE_ID])


## ⚠️ Avant de commencer :

- [**Docker Desktop pour Mac**](https://docs.docker.com/desktop/install/mac-install/)
- Téléchargez et installer [**Ddev**](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/)
- Lisez la documentation de [**Ddev**](https://ddev.readthedocs.io/en/stable/)

🚨 **_Si ce n'est pas deja fait ajoutez l'authentification pour Gravity Forms et ACF_**
<details>

* Gravity Forms [(doc)](https://github.com/arnaud-ritti/gravityforms-composer-bridge/blob/main/dependabot_usage.md) : `composer config --global http-basic.gf-composer-proxy.arnaud-ritti.workers.dev licensekey [YOUR_GRAVITYFORMS_KEY]`
* ACF Pro [(doc)](https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/) : `composer config --global http-basic.connect.advancedcustomfields.com [YOUR_ACFPRO_KEY] [PRODUCTION_URL]`

_Vou pouvez trouver les clés sur le [codex](https://codex.adeliom.com/books/wordpress/page/plugins-achetés)._

</details>

## 💡Technos utilisées :

- PHP : **8.2**
- MariaDB : **10.4**
- Environnement de développement : **Ddev**
- Stack : **Wordpress - Lumberjack - Bedrock**
- Thème : **Tailwind + Twig + Webpack Encore**


## 🏠 Hébergement

**Nom de domaine** : [example.com]

**Hébergement** : [Hebérgeur]

## 🌐 Liens :

- **Local** : [https://example.ddev.site/](https://example.ddev.site/)
- **Préprod** : [https://dev.example.com/](https://dev.example.com/)
- **Prod** : [http://www.example.com/](https://www.example.com/)

## 📦 Installation :

**1.** **Cloner ce dépôt** **:**

```console
git clone git@github.com:agence-adeliom/example.git
```

**2.** **Démarrer le projet** **:**

Dans le fichier .ddev/config.yaml, renommez le nom du projet "lumberjack" par le nom de votre projet. C'est ce qui génèrera l'URL de votre projet.

`ddev start`

⚠️ Si vous faites des changements dans le `.ddev/config.yaml`, faites un `ddev restart`

***Installation d'ACF Pro*** ***:***

Lors du composer install vous devrez renseigner les identifiants d'ACF Pro (clé disponible sur Bitwarden) :
    
```shell
Authentication required (connect.advancedcustomfields.com):
Username: [YOUR_ACFPRO_KEY]
Password: [PRODUCTION_URL]
```

***Installation de FontAwesome*** ***:***

⚠️ Sur ce projet, nous utilisons FontAwesome pour la gestion des icônes.
Pour pouvoir installer le package, remplacer dans le fichier '.npmrc' à la racine du thème 'VOTRE_CLE' par le Package Manager Token à cette url : https://fontawesome.com/account (compte Adeliom dans Bitwarden).
Une fois l'installation passée, merci de retirer la clé du fichier '.npmrc' et de la stocker dans le fichier '.env' de manière à ce qu'elle ne soit pas commit.

Ddev s'occupe de tout :
- création des différents conteneurs docker : appserver, phpmyadmin, node, mailhog
- `ddev auth ssh`
- `ddev composer install`
- `ddev theme:install`
- `ddev theme:dev`

Une fois les containers créés, un message apparaît et vous donne toutes les infos nécessaires :

```shell
Successfully started lumberjack 
Project can be reached at https://lumberjack.ddev.site https://127.0.0.1:51905 
```
**Compilation des assets et autres** **:**
```shell
ddev theme:watch
```

## 📡 Déploiement :
Déploiement via Deployer

Prérequis : avoir ajouté sa clé publique `ed25519` dans la liste des `authorized_keys` sur le serveur. Pour cela se connecter en FTP depuis Infomaniak et se rendre dans le dossier `.ssh`à la racine du serveur.

Ensuite depuis la racine de votre projet en local :

```shell
# Préproduction (branche develop)
ddev deployer deploy staging

# Production (branche main)
ddev deployer deploy producation
```


## 🎩 Bonus

<details>

<summary>Afficher les bonus</summary>

### Comment faire ?

Pour :
* Créer un PostType ([doc/how-to/create-posttype.md](./doc/how-to/create-posttype.md))
* Modifier les colonnes d'un PostType ([doc/how-to/edit-posttype-columns.md](./doc/how-to/edit-posttype-columns.md))
* Créer une Taxonomy ([doc/how-to/create-taxonomy.md](./doc/how-to/create-taxonomy.md))
* Créer une Extension Twig ([doc/how-to/create-twig_extensions.md](./doc/how-to/create-twig_extensions.md))
* Créer des champs ACF ([vendor/agence-adeliom/lumberjack-admin/src/Fields/README.md](./vendor/agence-adeliom/lumberjack-admin/src/Fields/README.md))

### Accéder à la base de données

#### PhpMyAdmin

```shell
ddev launch --phpmyadmin
```

#### TablePlus

```shell
ddev tableplus
```

#### Sequel Pro

```shell
ddev sequelpro
```

#### Sequel Ace

```shell
ddev sequelace
```

### Accéder à MailHog

```shell
ddev launch --mailhog
```

### Xdebug

```shell
# Activer
ddev xdebug

# Désactiver
ddev xdebug off
```

### Avoir un HTTPS valide en local

```shell
mkcert -install
```

### Faire fonctionner Husky avec SourceTree

```shell
echo 'export PATH="/usr/local/bin:$PATH"' > ~/.huskyrc
```

### Activer Mutagen

Pour améliorer les performances de l'environnement local, il peut être intéressant d'activer Mutagen

Pour l'activer : 
- accéder au fichier `.ddev/config.yaml`
- passer la ligne `performance_mode` à `mutagen`
- `ddev restart`

Par défaut, les dossiers des vendors (composer) et des node_modules (npm) sont exclus.

</details>

