# 🎮 O'Play - Installation & Documentation

---

## 🚀 Prérequis

- PHP >= 8.1
- MySQL

---

## 🛠️ Installation

### 1. Installer les dépendances PHP

```bash
composer install
```

### 2. Créer la base de données

```bash
bin/console doctrine:database:create
```

### 3. Appliquer les fixtures

```bash
bin/console doctrine:fixtures:load
```

### 4. Configurer l'environnement

Créer un fichier `.env.local` en copiant `.env.example` :

```bash
cp .env.example .env.local
```

Modifier `.env.local` pour renseigner la base de données :

```env
DATABASE_URL="mysql://db_user:db_pass@127.0.0.1:3306/nom_bdd?serverVersion=8.0"
```

### 5. Générer la clé APP_SECRET

```bash
php -r "echo bin2hex(random_bytes(16));"
```

Ajouter la valeur dans `.env.local` :

```env
APP_SECRET=ta_cle_secrete
```

---

## 🔐 Authentification JWT

Ce projet utilise **LexikJWTAuthenticationBundle** pour sécuriser les endpoints.

### Générer les clés :

```bash
php bin/console lexik:jwt:generate-keypair
```

### Ajouter les variables dans `.env.local` :

```env
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=ta_passphrase
```

---

## 🚴‍♂️ Lancer le serveur

Avec Symfony CLI :

```bash
symfony serve
```

Ou avec le serveur PHP intégré :

```bash
php -S localhost:8000 -t public
```

---

## 📚 Liste des routes

### 🔧 Routes Backoffice

| Méthode | URL                          | Fonction         | Description                   |
|---------|------------------------------|------------------|-------------------------------|
| GET     | /back/                       | -                | Page d'accueil                |
| GET     | /back/game                   | gameList         | Liste des jeux                |
| POST    | /back/game/add               | gameAdd          | Ajout d’un jeu                |
| POST    | /back/game/edit/{id}         | gameEdit         | Édition d’un jeu              |
| POST    | /back/game/delete/{id}       | gameDelete       | Suppression d’un jeu          |
| GET     | /back/users                  | usersList        | Liste des utilisateurs        |
| POST    | /back/users/add              | usersAdd         | Ajout d’un utilisateur        |
| POST    | /back/users/edit/{id}        | usersEdit        | Édition d’un utilisateur      |
| POST    | /back/users/delete/{id}      | usersDelete      | Suppression d’un utilisateur  |
| GET     | /back/category               | categoryList     | Liste des catégories          |
| POST    | /back/category/add           | categoryAdd      | Ajout d’une catégorie         |
| POST    | /back/category/edit/{id}     | categoryEdit     | Édition d’une catégorie       |
| POST    | /back/category/delete/{id}   | categoryDelete   | Suppression d’une catégorie   |
| GET     | /back/theme                  | themeList        | Liste des thèmes              |
| POST    | /back/theme/add              | themeAdd         | Ajout d’un thème              |
| POST    | /back/theme/edit/{id}        | themeEdit        | Édition d’un thème            |
| POST    | /back/theme/delete/{id}      | themeDelete      | Suppression d’un thème        |
| GET     | /back/tag                    | tagList          | Liste des tags                |
| POST    | /back/tag/add                | tagAdd           | Ajout d’un tag                |
| POST    | /back/tag/edit/{id}          | tagEdit          | Édition d’un tag              |
| POST    | /back/tag/delete/{id}        | tagDelete        | Suppression d’un tag          |
| GET     | /back/order                  | orderList        | Liste des commandes           |
| POST    | /back/order/edit/{id}        | orderEdit        | Édition d’une commande        |
| POST    | /back/order/delete/{id}      | orderDelete      | Suppression d’une commande    |

---

### 📡 Routes API

| Méthode | URL                                         | Description                                      |
|---------|---------------------------------------------|--------------------------------------------------|
| POST    | /api/login                                  | Connexion                                        |
| POST    | /api/user/register                          | Inscription                                      |
| POST    | /api/order/add                              | Ajouter un jeu au panier                         |
| DELETE  | /api/order/remove                           | Supprimer un jeu du panier                       |
| DELETE  | /api/order/clear                            | Vider le panier                                  |
| POST    | /api/order/checkout                         | Finaliser la commande                            |
| POST    | /api/user/category                          | Choix de catégories                              |
| POST    | /api/user/tag                               | Choix de tags                                    |
| POST    | /api/user/theme                             | Choix du thème                                   |
| GET     | /api/category                               | Liste des catégories                             |
| GET     | /api/category/{id}                          | Détails d’une catégorie                          |
| GET     | /api/category/{id}/games                    | Jeux par catégorie                               |
| GET     | /api/tag                                    | Liste des tags                                   |
| GET     | /api/tag/{id}                               | Détails d’un tag                                 |
| GET     | /api/tag/{id}/games                         | Jeux par tag                                     |
| GET     | /api/game/{id}                              | Détail d’un jeu                                  |
| GET     | /api/user/{id}                              | Données utilisateur                              |
| PATCH   | /api/user/{id}                              | Modifier les données de l’utilisateur            |

---
