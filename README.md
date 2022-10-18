# Projet example

## 🧐 À propos
ECRIRE QUELQUES LIGNES A PROPOS DU PROJET

**Date de création** : dd/mm/yyyy

## 📄 Documents

ℹ️ [Fiche projet Miro](https://miro.com/app/board/[BOARD_ID]/)

🖌 [Figma](https://www.figma.com/file/[FILE_ID])


## ⚠️ Avant de commencer :

- [**Docker Desktop pour Mac**](https://docs.docker.com/desktop/install/mac-install/)
- Téléchargez et installer [**Lando**](https://github.com/lando/lando/releases)  ℹ️ **_Ne pas utilisé la version de Docker Desktop intégrée_**
- Le plugin [**lando-adeliom**](https://github.com/agence-adeliom/lando-adeliom)
- Lisez la documentation de [**Lando**](https://docs.lando.dev/)

🚨 **_Si ce n'est pas deja fait ajoutez l'authentification pour Gravity Forms et ACF_**
<details>

* Gravity Forms [(doc)](https://github.com/arnaud-ritti/gravityforms-composer-bridge/blob/main/dependabot_usage.md) : `composer config --global http-basic.gf-composer-proxy.arnaud-ritti.workers.dev licensekey [YOUR_GRAVITYFORMS_KEY]`
* ACF Pro [(doc)](https://github.com/pivvenit/acf-pro-installer/issues/222#issuecomment-890359373) : `composer config --global http-basic.auth-acf-composer-proxy.pivvenit.net licensekey [YOUR_ACFPRO_KEY]`

_Vou pouvez trouver les clés sur le [codex](https://codex.adeliom.com/books/wordpress/page/plugins-achetés)._

</details>

## 💡Technos utilisées :

- PHP : **8.1**
- Mysql : **5.7**
- Environnement de développement : **Lando x Lando-Adeliom**
- Stack : **Wordpress - Lumberjack - Bedrock**
- Thème : **Tailwind + Twig + Webpack Encore**


## 🏠 Hébergement

**Nom de domaine** : [example.com]

**Hébergement** : [Hebérgeur]

## 🌐 Liens :

- **Local** : [https://example.lndo.site/](https://example.lndo.site/)
- **Préprod** : [https://dev.example.com/](https://dev.example.com/)
- **Prod** : [http://www.example.com/](https://www.example.com/)

## 📦 Installation :

**1.** **Cloner ce dépôt** **:**

```console
git clone git@github.com:agence-adeliom/example.git
```

**2.** **Démarrer le projet** **:**

Dans le fichier .lando.yml, renommez le nom du projet "lumberjack" par le nom de votre projet. C'est ce qui génèrera l'URL de votre projet.

`lando start`

⚠️ Si vous faites des changement dans le `.lando.yml` ou dans `.lando.local.yml` faite un `lando rebuild -y`


Lando s'occupe de tout :
- création des différents conteneurs docker : appserver, phpmyadmin, node, mailhog
- `lando composer install`
- `lando npm install`

Une fois les containers créés, un message apparaît et vous donne toutes les infos nécessaires :

```console
Here are some vitals:

 NAME             example                                       
 LOCATION         /Users/adeliom/Projects/example
 SERVICES         appserver, database, node, phpmyadmin, mailhog 
 APPSERVER URLS   https://localhost:51109                        
                  http://localhost:51110                         
                  http://example.lndo.site/                     
                  https://example.lndo.site/                    
 PHPMYADMIN URLS  http://localhost:51137                         
 MAILHOG URLS     http://localhost:51116                         
```
**Compilation des assets et autres** **:**
```console
cd web/app/themes/adeliom

lando npm run watch
```

## 📡 Déploiement :
Déploiement via Deployer

Prérequis : avoir ajouté sa clé publique `ed25519` dans la liste des `authorized_keys` sur le serveur. Pour cela se connecter en FTP depuis Infomaniak et se rendre dans le dossier `.ssh`à la racine du serveur.

Ensuite depuis la racine de votre projet en local :

```
Préproduction (branche develop)
lando deployer deploy staging

Production (branche main)
lando deployer deploy producation
```



## 🎩 Bonus

<details>

### Comment faire ?

Pour :
* Créer un PostType ([doc/how-to/create-posttype.md](./doc/how-to/create-posttype.md))
* Modifier les colonnes d'un PostType ([doc/how-to/edit-posttype-columns.md](./doc/how-to/edit-posttype-columns.md))
* Créer une Taxonomy ([doc/how-to/create-taxonomy.md](./doc/how-to/create-taxonomy.md))
* Créer une Extension Twig ([doc/how-to/create-twig_extensions.md](./doc/how-to/create-twig_extensions.md))

### Forward le port de la base de donnée pour une utilisation locale _(TablePlus, SequelPro, MySQL Workbenchh ou PHPStorm)_

Dans le fichier `.lando.local.yaml` :

```yaml
services:
  database:
    portforward: [PORT]
```

### Activé Xdebug

Dans le fichier `.lando.local.yaml` :

```yaml
services:
  appserver:
    # See: https://xdebug.org/docs/all_settings#mode
    xdebug: "debug,develop"
```

### Avoir un HTTPS valide en local

```shell
sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain ~/.lando/certs/lndo.site.pem
```

### Faire fonctionner Husky avec SourceTree

```shell
echo 'export PATH="/usr/local/bin:$PATH"' > ~/.huskyrc
```

</details>

