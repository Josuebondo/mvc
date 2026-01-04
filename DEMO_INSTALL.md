# 🎥 Installation Demo - Nouveau PC

Ce document montre une installation complète du zéro sur **un nouveau PC** (ou une nouvelle machine virtuelle).

## Étape 1: Préparation (2 min)

### Installer les outils nécessaires

**Windows:**

1. Télécharger PHP 8.0+ depuis https://www.php.net/downloads
2. Télécharger Composer depuis https://getcomposer.org/download
3. Télécharger MySQL depuis https://dev.mysql.com/downloads/mysql/

Ou utiliser **XAMPP** (tout-en-un):

- https://www.apachefriends.org/index.html

**Mac:**

```bash
# Avec Homebrew
brew install php@8.1
brew install composer
brew install mysql
```

**Linux:**

```bash
# Ubuntu/Debian
sudo apt-get install php8.1 php8.1-mysql composer mysql-server

# Fedora
sudo dnf install php php-mysql composer mysql-server
```

### Vérifier l'installation

```bash
php -v       # PHP 8.0+
composer -v  # composer installed
mysql -v     # mysql installed
```

## Étape 2: Télécharger BondoMVC (1 min)

### Via Git (si vous avez git)

```bash
git clone https://github.com/Josuebondo/mvc.git mon-app
cd mon-app
```

### Via Composer (RECOMMANDÉ)

```bash
composer create-project bondomvc/mvc mon-app
cd mon-app
```

### Via ZIP

1. Télécharger https://github.com/Josuebondo/mvc/archive/refs/heads/main.zip
2. Décompresser
3. `cd mvc-main`

## Étape 3: Configuration (3 min)

### Créer le fichier `.env`

```bash
# Linux/Mac
cp .env.example .env

# Windows
copy .env.example .env
```

### Éditer `.env`

Éditer le fichier `.env`:

```env
APP_NAME=MonProjet
APP_ENV=development
APP_DEBUG=true

# ⚠️ IMPORTANT: Adapter à VOTRE système
DB_HOST=localhost
DB_NAME=mon_app_db
DB_USER=root
DB_PASS=votre_mot_de_passe_mysql
```

### Vérifier l'installation

```bash
php check-installation.php
```

Vous devriez voir:

```
✅ PHP 8.1.0
✅ All directories present
✅ Composer installed
✅ .env exists
✅ Database connected
✅ All directories writable
✅ All key files present
✅ All extensions loaded

📊 Summary
Checks passed: 8/8
✅ Everything looks good!
```

## Étape 4: Créer la Base de Données (2 min)

### Via MySQL CLI

```bash
mysql -u root -p
CREATE DATABASE mon_app_db;
EXIT;
```

### Via Migrations (optionnel)

```bash
php artisan migrate
```

## Étape 5: Lancer le serveur (1 min)

```bash
php artisan serve
```

Vous devriez voir:

```
Server running on http://localhost:8000
Press Ctrl+C to stop
```

## Étape 6: Tester dans le navigateur (1 min)

1. Ouvrir `http://localhost:8000`
2. Vous devriez voir la **page d'accueil BondoMVC** 🎨
3. Cliquer sur **Commencer** ou **Documentation**

## Étape 7: Test de fonctionnalités (5 min)

### Tester l'authentification

1. Aller à `http://localhost:8000/auth/register`
2. Créer un compte avec votre email
3. Vous êtes automatiquement connecté! ✅

### Tester le dashboard

1. Aller à `http://localhost:8000/dashboard`
2. Voir votre profil utilisateur ✅

### Tester les commandes Artisan

```bash
# Créer un contrôleur
php artisan make:controller BlogController

# Créer un modèle
php artisan make:model Post

# Voir tous les contrôleurs/modèles créés
ls app/controllers/
ls app/models/
```

### Tester les uploads de fichiers

```php
// Dans un contrôleur
$upload = new \Core\FileUpload($_FILES['file'], 'documents');
if ($upload->isValid()) {
    $path = $upload->save();
}
```

### Tester l'email

```php
// Envoyer un test
$email = new \Core\Email();
$email->to('votre-email@example.com')
      ->subject('Test')
      ->html('<h1>Test réussi!</h1>')
      ->send();
```

### Tester les migrations

```bash
# Créer une migration
php artisan make:migration CreatePostsTable

# Éditer database/migrations/xxxx_CreatePostsTable.php
# Ajouter votre schéma

# Exécuter
php artisan migrate
```

### Tester les logs

```bash
# Consulter les logs
tail storage/logs/2024-01-04.log

# Ou en PHP
$logs = \Core\Logger::getLogs('2024-01-04');
```

## Étape 8: Lancer les tests (2 min)

```bash
vendor/bin/phpunit
```

Tous les tests devraient **PASSER** ✅

## 🎉 Résumé

**Temps total d'installation: ~20 minutes**

Vous avez maintenant un framework **professionnel et prêt à l'emploi** avec:

✅ Routage automatique MVC
✅ Authentification complète
✅ Base de données ORM
✅ Upload fichiers sécurisé
✅ Service email
✅ Système de logging
✅ Tests automatisés
✅ CLI Artisan
✅ Migrations BD
✅ 0 dépendances de production

## 🚀 Prochaines étapes

1. **Créer votre premier contrôleur:**

   ```bash
   php artisan make:controller PostController
   ```

2. **Créer votre modèle:**

   ```bash
   php artisan make:model Post
   ```

3. **Créer une migration:**

   ```bash
   php artisan make:migration CreatePostsTable
   ```

4. **Commencer à coder!**
   ```php
   // app/controllers/PostController.php
   class PostController extends Controller {
       public function index() {
           $posts = (new Post())->getAll();
           $this->view('posts/index', ['posts' => $posts]);
       }
   }
   ```

## 📖 Documentation

- **Complète**: [DOCS.md](DOCS.md)
- **Auth**: [AUTH.md](AUTH.md)
- **Upload/Email**: [FILE_UPLOAD_EMAIL.md](FILE_UPLOAD_EMAIL.md)
- **Exemples**: [EXAMPLES.md](EXAMPLES.md)
- **Tests**: [TESTS.md](TESTS.md)
- **Migrations**: [database/MIGRATIONS.md](database/MIGRATIONS.md)

## 🆘 Besoin d'aide?

1. Vérifier `storage/logs/` pour les erreurs
2. Lancer `php check-installation.php`
3. Consulter [INSTALL_MULTIPC.md](INSTALL_MULTIPC.md)
4. Issues: https://github.com/Josuebondo/mvc/issues

---

**Bravo! 🎊 Vous êtes prêt à développer!**
