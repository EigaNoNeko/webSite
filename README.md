# EigaNoNeko — Site web

Code source du site **[eiganoneko.com](https://eiganoneko.com)**.

Le site repose sur **[Omsy](https://github.com/floagg-Studio/Omsy)**, un CMS modulaire en PHP. Ce dépôt est un **fork d'Omsy** : il reprend le noyau du CMS et y ajoute le template et les plugins propres à EigaNoNeko.

> ⚠️ **Dépendance à Omsy** : le noyau (`sys/`), le panneau d'administration (`panel/`) et le schéma SQL viennent d'Omsy. Les correctifs et évolutions du noyau doivent être récupérés depuis le dépôt d'origine (voir [Synchroniser avec Omsy](#-synchroniser-avec-omsy)).

---

## 📚 Sommaire

- [Rapport avec Omsy](#-rapport-avec-omsy)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Structure du projet](#-structure-du-projet)
- [Base de données](#-base-de-données)
- [Synchroniser avec Omsy](#-synchroniser-avec-omsy)
- [Déploiement en production](#-déploiement-en-production)
- [Contribuer](#-contribuer)
- [Licence](#-licence)
- [Contact](#-contact)

---

## 🧩 Rapport avec Omsy

| | Omsy (projet parent) | EigaNoNeko (ce dépôt) |
|---|---|---|
| Rôle | CMS générique | Site eiganoneko.com |
| Dépôt | `floagg-Studio/Omsy` | `EigaNoNeko/webSite` |
| Template | — | `enn` |
| Plugins activés | — | `omsyPanel`, `omsyBlog`, `ennNode`, `EigaNoNeko` |

Le template et la liste des plugins sont définis dans la section `<template>` de la configuration (`default.Core.xml`).

---

## 🧾 Prérequis

| Élément | Version / détail |
|---|---|
| PHP | 7.4 minimum (développé avec PHP 8.2) |
| Extensions PHP | `simplexml`, sessions, `mysqli` ou `pdo_mysql` |
| Serveur web | Apache avec `mod_rewrite` et `AllowOverride All` |
| Base de données | MySQL 5.7+ / MariaDB 10.4+ |
| Redis | Optionnel (cache) |
| HTTPS | Requis en production (les cookies de session sont en mode `secure`) |

---

## ⚙️ Installation

1. **Cloner le dépôt**

   ```bash
   git clone https://github.com/EigaNoNeko/webSite.git
   cd webSite
   ```

2. **Créer la configuration**

   ```bash
   cp default.Core.xml Core.xml
   ```

   `index.php` charge `Core.xml` au démarrage ; sans ce fichier, le site affiche « Erreur de chargement du fichier XML ».

3. **Créer la base de données et importer le schéma**

   ```bash
   mysql -u <utilisateur> -p -e "CREATE DATABASE omsy_sql CHARACTER SET utf8mb4;"
   mysql -u <utilisateur> -p omsy_sql < omsy_sql.sql
   ```

4. **Renseigner `Core.xml`** : section `<database>` avec un utilisateur dédié (pas `root`), et `<template><title>` avec le titre du site.

5. **Configurer Apache** : `DocumentRoot` vers le dossier du projet, `mod_rewrite` activé, `.htaccess` autorisés.

6. **Tester** : ouvrir le site, puis l'administration sur `/panel`.

> ⚠️ Ne publiez jamais votre `Core.xml` : il contient les identifiants de base de données. Vérifiez qu'il est bien listé dans `.gitignore`.

---

## 🛠️ Configuration

Toute la configuration passe par `Core.xml`.

| Section | Rôle |
|---|---|
| `<omsy>` | Environnement, composants chargés, options PHP, accès à l'administration, logs |
| `<database>` | Connexion MySQL / MariaDB |
| `<redis>` | Connexion Redis (cache) |
| `<template>` | Template actif (`enn`), titre, plugins activés |
| `<security>` | Autorisation de `eval`, `exec`, `system`, `passthru` |
| `<server>` | Clés privée et de sel, serveur de mises à jour |

### Valeurs à changer avant la production

| Option | Valeur par défaut | À mettre |
|---|---|---|
| `<env>` | `local` | Valeur adaptée à la production |
| `display_errors` | `1` | `0` |
| `<database><username>` | `root` | Utilisateur dédié aux droits limités |
| `<database><password>` | vide | Mot de passe fort |
| `<security><exec>` | `true` | `false` sauf besoin avéré |
| `<server><privatekey>` / `<saltkey>` | vides | Valeurs longues et aléatoires |
| `memory_limit` | `800M` | Valeur réaliste pour votre hébergement |

---

## 🗂️ Structure du projet

```
webSite/
├── index.php          # Point d'entrée : charge Core.xml puis instancie le noyau Omsy
├── default.Core.xml   # Modèle de configuration (à copier en Core.xml)
├── .htaccess          # Réécriture d'URL et protection de Core.xml (Apache)
├── manifest.json      # Manifeste PWA
├── omsy_sql.sql       # Schéma de la base de données
├── sql_install.json   # Données d'installation SQL
├── panel/             # Panneau d'administration (Omsy)
├── sys/               # Noyau Omsy
├── usr/               # Contenus et plugins propres au site
└── share/             # Ressources partagées (logs : share/_)
```

---

## 🗄️ Base de données

Le schéma `omsy_sql.sql` crée les tables suivantes :

| Table | Contenu |
|---|---|
| `omsy_account` | Comptes (identifiant, mot de passe, rôle, date d'inscription) |
| `omsy_account_login` | Jetons de connexion |
| `omsy_account_obj` | Objets rattachés à un compte |
| `omsy_object` | Objets génériques (type, nom, contenu) |
| `omsy_session` | Sessions et dernière activité |
| `omsy_viewpage` | Journal de pages vues (user-agent, IP, date) |
| `omsyreport_report` | Signalements |

> **RGPD** : `omsy_viewpage` enregistre des adresses IP, qui sont des données personnelles. Le site doit informer ses visiteurs (mentions légales / politique de confidentialité) et limiter la durée de conservation de ces données.

---

## 🔄 Synchroniser avec Omsy

Pour récupérer les mises à jour du noyau depuis le projet d'origine :

```bash
git remote add upstream https://github.com/floagg-Studio/Omsy.git
git fetch upstream
git merge upstream/main
```

Les conflits les plus probables concernent `default.Core.xml`, `index.php` et `.htaccess`. Pour limiter les conflits, gardez vos modifications dans `usr/` et dans `Core.xml`, et évitez de modifier `sys/` et `panel/`.

---

## 🚀 Déploiement en production

Checklist avant la mise en ligne :

- [ ] `Core.xml` créé, **hors dépôt Git**, et inaccessible depuis le navigateur (réponse 403)
- [ ] `display_errors` désactivé
- [ ] Utilisateur MySQL dédié, avec mot de passe fort
- [ ] HTTPS actif et redirection HTTP → HTTPS
- [ ] `privatekey` et `saltkey` renseignées
- [ ] `exec` désactivé si inutilisé
- [ ] Accès à `/panel` restreint (IP, authentification renforcée) et mot de passe administrateur fort
- [ ] Mentions légales et politique de confidentialité en ligne
- [ ] Sauvegardes automatiques de la base et du dossier `share/`

### Signaler une faille de sécurité

Merci de ne **pas** ouvrir d'issue publique pour une vulnérabilité : contactez directement l'équipe (voir [Contact](#-contact)).

---

## 🤝 Contribuer

1. Forkez le dépôt
2. Créez une branche : `git checkout -b feature/ma-fonctionnalite`
3. Commitez : `git commit -m "Ajout de ma fonctionnalité"`
4. Poussez : `git push origin feature/ma-fonctionnalite`
5. Ouvrez une Pull Request

Pour un problème lié au **noyau** (`sys/`, `panel/`), ouvrez plutôt une issue sur le dépôt [Omsy](https://github.com/floagg-Studio/Omsy/issues).

### Conventions de code

| Élément | Style | Exemple |
|---|---|---|
| Nom de fichier | `snake_case` | `ce_si_est_un_nom` |
| Variable | `camelCase` | `ceSiEstUnNom` |
| Classe | `PascalCase` | `CeSiEstUneClass` |
| Constante | `__snake_case__` | `__ce_si_est_une_constance__` |

---

## 📜 Licence

Aucune licence n'est définie pour le moment. Sans licence, le code reste protégé par défaut : sa réutilisation, sa modification et sa redistribution ne sont pas autorisées. Ajoutez un fichier `LICENSE` pour clarifier les droits. Le projet parent Omsy n'a pas non plus de licence : vérifiez auprès de ses auteurs avant de choisir.

---

## 🔗 Contact

- **Site** : <https://eiganoneko.com>
- **Dépôt** : <https://github.com/EigaNoNeko/webSite>
- **Projet parent (Omsy)** : <https://github.com/floagg-Studio/Omsy>