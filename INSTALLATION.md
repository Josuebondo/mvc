# 📦 Installation avec Composer

## Créer un Nouveau Projet BondoMVC

### Méthode 1: Via Composer Create-Project (Recommandée)

```bash
composer create-project bondomvc/framework MonProjet
```

**Advantages:**

- ✅ Installation automatique
- ✅ Structure complète
- ✅ Dépendances installées
- ✅ `.env` auto-généré
- ✅ HomeController créé automatiquement

### Méthode 2: Via Git Clone

```bash
git clone https://github.com/bondomvc/framework.git MonProjet
cd MonProjet
composer install
cp .env.example .env
```

### Méthode 3: Manuel (Téléchargement ZIP)

1. Télécharger: https://github.com/bondomvc/framework/archive/refs/heads/main.zip
2. Décompresser dans `htdocs`
3. Exécuter: `composer install`
4. Copier: `cp .env.example .env`

## Configuration Initiale

### 1. Configurer .env

```bash
nano .env
```

```env
APP_NAME=MonProjet
URLROOT=http://localhost/MonProjet
DB_HOST=localhost
DB_NAME=mon_projet
DB_USER=root
DB_PASS=
```

### 2. Créer la Base de Données

```bash
# Option 1: Exécuter seed.sql
mysql -u root < database/seed.sql

# Option 2: Créer manuellement
mysql -u root -e "CREATE DATABASE mon_projet;"
```

### 3. Générer Autoload

```bash
composer dump-autoload
```

## 🚀 Démarrer le Développement

### Avec XAMPP

1. Copier le dossier dans `C:\xampp\htdocs\`
2. Démarrer Apache
3. Accéder à `http://localhost/MonProjet`

### Avec PHP Built-in Server

```bash
cd MonProjet
php -S localhost:8000
```

Puis visiter `http://localhost:8000`

## 📚 Utiliser le CLI

### Créer un Contrôleur

```bash
php console make:controller ProductController
```

Crée `app/controllers/ProductController.php`

### Créer un Modèle

```bash
php console make:model Product
```

Crée `app/models/Product.php` avec CRUD automatique

### Créer un Middleware

```bash
php console make:middleware AuthMiddleware
```

Crée `app/middleware/AuthMiddleware.php`

## 📖 Utiliser la Documentation

### Documentation Complète

```bash
# Lire le README
cat README.md

# Guide pour nouveaux projets
cat GUIDE_NOUVEAU_PROJET.md

# Guide déploiement
cat GUIDE_DEPLOIEMENT.md
```

## ✅ Checklist Post-Installation

- [ ] `.env` configuré
- [ ] BD créée
- [ ] `composer install` exécuté
- [ ] Accès à `http://localhost/MonProjet`
- [ ] Lire la documentation
- [ ] Créer premiers contrôleurs/modèles
- [ ] Vérifier les routes automatiques

## 🔗 Ressources

- **GitHub**: https://github.com/bondomvc/framework
- **Documentation**: Voir [README.md](README.md)
- **Guides**:
  - [Nouveau Projet](GUIDE_NOUVEAU_PROJET.md)
  - [Déploiement](GUIDE_DEPLOIEMENT.md)
- **Support**: Issues sur GitHub

## 🆘 Troubleshooting

**Erreur: "command not found: composer"**

```bash
# Installer Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

**Erreur: "PHP version too old"**

```bash
# Mettre à jour PHP à minimum 8.0
# https://www.php.net/downloads
```

**Erreur: "Database connection"**

```bash
# Vérifier les credentials dans .env
# Assurez-vous que MySQL est démarré
```

**Erreur: "404 on routes"**

```bash
# Vérifier que mod_rewrite est activé
# Ou utiliser php -S localhost:8000
```

---

**Installation réussie!** 🎉 Commencez à développer! 🚀
