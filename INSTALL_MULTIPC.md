# 🚀 Installation Multi-PC Guide

Ce guide assure que BondoMVC fonctionne sur **n'importe quel PC** (Windows, Mac, Linux).

## ✅ Vérifications Pre-Installation

Assurez-vous d'avoir:

- ✅ **PHP 8.0+** - `php -v`
- ✅ **Composer** - `composer -v`
- ✅ **MySQL/MariaDB** - `mysql --version`
- ✅ **Git** (optionnel) - `git --version`

## 📥 Installation

### Option 1: Via Composer (Recommandée) 🌟

```bash
composer create-project bondomvc/mvc mon-app
cd mon-app
php artisan serve
```

Puis visiter `http://localhost:8000`

### Option 2: Via Git

```bash
git clone https://github.com/Josuebondo/mvc.git mon-app
cd mon-app
composer install
cp .env.example .env
php artisan serve
```

### Option 3: Manual ZIP

1. Télécharger: https://github.com/Josuebondo/mvc/archive/refs/heads/main.zip
2. Décompresser
3. `cd` dans le dossier
4. `composer install`
5. `cp .env.example .env`
6. `php artisan serve`

## ⚙️ Configuration Post-Installation

### 1️⃣ Configurer `.env`

```bash
nano .env  # Linux/Mac
notepad .env  # Windows
```

Modifier **uniquement ces valeurs** (le reste est optionnel):

```env
# Obligatoire
APP_NAME=MonApp
APP_ENV=development
APP_DEBUG=true

# Base de données
DB_HOST=localhost
DB_NAME=ma_base
DB_USER=root
DB_PASS=votre_mot_de_passe

# Optionnel (gardez les valeurs par défaut)
MAIL_FROM=noreply@example.com
UPLOAD_MAX_SIZE=5242880
```

### 2️⃣ Créer la Base de Données

Via MySQL:

```bash
mysql -u root -p
CREATE DATABASE ma_base;
EXIT;
```

Ou via PHPMyAdmin:

1. Aller à `http://localhost/phpmyadmin`
2. Créer nouvelle BD: `ma_base`

### 3️⃣ Vérifier les Permissions

**Linux/Mac** (rendre writable les répertoires de stockage):

```bash
chmod -R 755 storage
chmod -R 755 public
chmod -R 755 app/views
```

**Windows** (généralement ok automatiquement)

### 4️⃣ Lancer les Migrations

```bash
php artisan migrate
```

Ou créer manuellement les tables:

```bash
mysql -u root -p ma_base < database/migrations/001_create_users_table.sql
```

## 🧪 Vérifier l'Installation

### Teste 1: Page d'accueil

```bash
php artisan serve
```

Visiter `http://localhost:8000` → Vous devriez voir la page BondoMVC

### Test 2: Authentification

1. Aller à `/auth/register`
2. Créer un compte
3. Vérifier dans la BD que l'utilisateur existe

### Test 3: Tests automatisés

```bash
vendor/bin/phpunit
```

Tous les tests doivent passer ✅

### Test 4: Tests des commandes

```bash
php artisan make:controller TestController
php artisan make:model TestModel
php artisan tinker
```

## 🆘 Troubleshooting Commun

### ❌ "PHP version too old"

```bash
# Vérifier version
php -v

# Télécharger PHP 8.0+
# https://www.php.net/downloads
```

### ❌ "Composer command not found"

```bash
# Installer Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### ❌ "Database connection failed"

Vérifier `.env`:

```bash
# Vérifier les credentials
echo $DB_HOST  # doit être localhost
echo $DB_NAME  # doit être votre BD
echo $DB_USER  # doit être root ou votre user

# Vérifier MySQL est lancé
mysql -u root -p

# Si erreur, vérifier le port (3306 par défaut)
```

### ❌ "Permission denied" (storage/logs)

```bash
# Linux/Mac
chmod -R 777 storage
chmod -R 777 app/views

# Windows (ouvrir Explorateur)
Clic droit storage → Properties → Security → Donner Full Control
```

### ❌ "Class not found: Config"

Régénérer l'autoload:

```bash
composer dump-autoload
```

### ❌ "Port 8000 déjà utilisé"

```bash
# Utiliser un port différent
php artisan serve --port=8001
```

## 🖥️ Installation sur XAMPP

### Windows

1. Placer le dossier dans `C:\xampp\htdocs\mon-app`
2. Lancer XAMPP (Apache + MySQL)
3. Accéder à `http://localhost/mon-app/public`

Ou via CLI:

```bash
cd C:\xampp\htdocs\mon-app
php artisan serve
```

### Linux (XAMPP)

```bash
cd /opt/lampp/htdocs/mon-app
php artisan serve
# ou
/opt/lampp/bin/php artisan serve
```

### Mac (XAMPP)

```bash
cd /Applications/XAMPP/htdocs/mon-app
php artisan serve
```

## 🌍 Déploiement Production

### Pré-déploiement

Avant de mettre en production:

```bash
# 1. Désactiver debug
APP_DEBUG=false

# 2. Changer l'environnement
APP_ENV=production

# 3. Mettre à jour la BD
php artisan migrate

# 4. Optimiser
composer install --optimize-autoloader --no-dev

# 5. Exécuter les tests
vendor/bin/phpunit
```

### Sur Serveur

Structure type:

```
/var/www/mon-app/
├── app/
├── config/
├── core/
├── database/
├── public/          ← Root web
├── storage/
├── vendor/
├── .env
├── .htaccess
├── index.php
└── artisan
```

Permissions:

```bash
chmod -R 755 /var/www/mon-app
chmod -R 777 /var/www/mon-app/storage
chmod 644 /var/www/mon-app/public/.htaccess
```

Vhost Apache:

```apache
<VirtualHost *:80>
    ServerName mon-app.com
    DocumentRoot /var/www/mon-app/public

    <Directory /var/www/mon-app/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## ✅ Checklist d'Installation

- [ ] PHP 8.0+ installé
- [ ] Composer installé
- [ ] MySQL/MariaDB installé et lancé
- [ ] Projet téléchargé
- [ ] `composer install` exécuté
- [ ] `.env` créé et configuré
- [ ] BD créée
- [ ] `php artisan serve` fonctionne
- [ ] Page d'accueil visible
- [ ] Authentification testée
- [ ] Tests passent (`vendor/bin/phpunit`)

## 🤔 Questions Fréquentes

**Q: Dois-je mettre `.env` en production?**
A: Non! `.env` est local. Sur serveur, définir les variables d'environnement via le panel d'hébergement.

**Q: Comment faire un HTTPS?**
A: Utiliser Let's Encrypt (gratuit) ou acheter un certificat SSL.

**Q: Comment déboguer en production?**
A: Garder `APP_DEBUG=false` et consulter les logs dans `storage/logs/`

**Q: Comment mettre à jour le framework?**
A: `composer update` (attention: peut break des dépendances)

**Q: Puis-je utiliser PostgreSQL au lieu de MySQL?**
A: Oui, modifier `DB_HOST`, `DB_DRIVER` dans `.env` et adapter les migrations.

## 🆘 Support

Si problème persiste:

1. ✅ Vérifier `.env` (pas d'espace, guillemets)
2. ✅ Consulter `storage/logs/` pour les erreurs
3. ✅ Vérifier permissions dossiers
4. ✅ Vérifier PHP version: `php -v`
5. ✅ Essayer `composer dump-autoload`
6. ✅ Vider cache: `rm -rf vendor/` puis `composer install`

**Repository**: https://github.com/Josuebondo/mvc
**Issues**: https://github.com/Josuebondo/mvc/issues
**Docs**: Voir [DOCS.md](DOCS.md)
