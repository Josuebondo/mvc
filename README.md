# 🚀 BondoMVC Framework

Un framework PHP MVC léger, moderne et réutilisable comme Laravel, parfait pour des projets web rapides et scalables.

## ✨ Fonctionnalités

- ✅ **Routeur MVC** - Routes automatiques avec conversion tirets → camelCase
- ✅ **Validation** - Validation de formulaires complète
- ✅ **Authentification** - Login/Register avec hash bcrypt
- ✅ **Middleware** - Protéger les routes (auth_required)
- ✅ **API REST** - Endpoints JSON complètes
- ✅ **CRUD** - Gestion complète des données
- ✅ **ORM Simple** - Classe Database avec requêtes préparées
- ✅ **Helpers** - 20+ fonctions utilitaires
- ✅ **Configuration** - Support .env

## 📋 Structure du Projet

```
BondoMVC/
├── app/
│   ├── controllers/        # Contrôleurs (HomeController, AuthController, etc.)
│   ├── models/             # Modèles (User, Product, etc.)
│   ├── views/              # Vues (HTML/PHP)
│   └── middleware/         # Middlewares (AuthMiddleware, etc.)
├── core/                   # Noyau du framework
│   ├── App.php             # Routeur principal
│   ├── Controller.php       # Classe de base pour contrôleurs
│   ├── Model.php           # Classe de base pour modèles
│   ├── Database.php        # Gestion base de données
│   ├── Validator.php       # Validation
│   ├── Middleware.php      # Classe de base middleware
│   └── Helpers.php         # Fonctions utilitaires
├── config/
│   └── config.php          # Configuration (BD, URLs, etc.)
├── database/
│   └── seed.sql            # Données d'exemple
├── public/
│   ├── index.php           # Point d'entrée
│   ├── css/                # Fichiers CSS
│   ├── js/                 # Fichiers JavaScript
│   └── api-docs.html       # Documentation API
├── .env.example            # Modèle de configuration
├── .htaccess               # Réécriture d'URLs
└── composer.json           # Dépendances PHP
```

## 🚀 Installation

### 1. Cloner/Copier le framework

```bash
cd C:\xampp\htdocs
# ou copier le dossier BondoMVC
```

### 2. Installer les dépendances

```bash
composer install
composer dump-autoload
```

### 3. Configurer .env

```bash
cp .env.example .env
```

Puis éditer `.env`:

```env
APP_NAME=MonApp
URLROOT=http://localhost/BondoMVC
DB_HOST=localhost
DB_NAME=ma_base
DB_USER=root
DB_PASS=
```

### 4. Créer la base de données

```bash
mysql -u root < database/seed.sql
```

### 5. Lancer le serveur

```bash
# Avec XAMPP
# Démarrer Apache dans XAMPP Control Panel

# Ou avec PHP built-in
php -S localhost:8000
```

Accédez à `http://localhost/BondoMVC`

## 📚 Guide d'Utilisation

### Créer un Contrôleur

```bash
php console make:controller ProductController
```

Ou créer manuellement dans `app/controllers/ProductController.php`:

```php
<?php

namespace App\Controllers;

use Core\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Produits'];
        $this->view('products/index', $data);
    }

    public function show($id)
    {
        $this->view('products/show', ['id' => $id]);
    }
}
```

### Créer un Modèle

```bash
php console make:model Product
```

Ou créer manuellement dans `app/models/Product.php`:

```php
<?php

namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected string $table = 'products';

    public function getAll()
    {
        return $this->db()->fetchAll("SELECT * FROM {$this->table}");
    }

    public function getById($id)
    {
        return $this->db()->fetch("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }
}
```

### Créer une Vue

Fichier `app/views/products/index.php`:

```php
<h1><?= escape($title) ?></h1>
<p><?= escape($description) ?></p>
```

### Routes

Les routes se font automatiquement!

```
GET  /product → ProductController@index()
GET  /product/show/1 → ProductController@show(1)
POST /product/do-create → ProductController@doCreate()
```

Conversion automatique:

- `/do-create` → `doCreate()`
- `/my-action` → `myAction()`

### Validation

```php
$validator = new Validator();
$validator->validate([
    'name' => 'required|min:3|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:6|confirmed'
]);

if ($validator->hasErrors()) {
    // Afficher erreurs
    $validator->displayErrors();
}
```

### Authentification

```php
// Login
if ($user = $userModel->getByEmail($email)) {
    if ($userModel->verifyPassword($password, $user['password'])) {
        setSession('user_id', $user['id']);
        redirect('/dashboard');
    }
}

// Récupérer l'utilisateur
$user = auth();

// Vérifier si connecté
if (isAuth()) {
    // Utilisateur connecté
}
```

### Middleware

```php
class AdminController extends Controller
{
    public function __construct()
    {
        $middleware = new AuthMiddleware();
        if (!$middleware->handle()) {
            exit;
        }
    }
}
```

### API REST

```php
class ApiController extends Controller
{
    public function articles()
    {
        $articles = /* récupérer */;
        $this->jsonResponse([
            'success' => true,
            'data' => $articles
        ], 200);
    }

    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
```

Accès: `GET /api/articles` → JSON

## 🛠️ CLI - Générateur de Code

### Créer un Contrôleur

```bash
php console make:controller NomController
```

### Créer un Modèle

```bash
php console make:model NomModel
```

### Créer un Middleware

```bash
php console make:middleware NomMiddleware
```

## 🚀 Déploiement en Production

### 1. Préparer l'environnement

```bash
# 1. Configurer .env pour production
DB_HOST=prod.db.server
DB_NAME=prod_database
APP_DEBUG=false

# 2. Générer autoload optimisé
composer install --optimize-autoloader --no-dev

# 3. Vérifier permissions
chmod -R 755 app/ core/ public/
chmod -R 777 storage/ # pour les uploads
```

### 2. Serveur Apache

Configuration `.htaccess` (déjà prête):

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

Activer mod_rewrite:

```bash
a2enmod rewrite
systemctl restart apache2
```

### 3. SSL/HTTPS

```bash
# Installer Let's Encrypt
certbot certonly --apache -d monsite.com

# Renouvellement automatique
certbot renew --dry-run
```

### 4. Base de données

```bash
# Exporter BD
mysqldump -u user -p base > backup.sql

# Importer en prod
mysql -u user -p prod_db < backup.sql
```

### 5. Monitoring

```bash
# Logs
tail -f /var/log/apache2/error.log

# Performance
htop
```

## 📊 Exemple Complet - Blog

### 1. Créer modèle

```bash
php console make:model Post
php console make:controller PostController
```

### 2. Migration BD

```sql
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Modèle

```php
class Post extends Model
{
    protected string $table = 'posts';

    public function getAll()
    {
        return $this->db()->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC"
        );
    }
}
```

### 4. Contrôleur

```php
class PostController extends Controller
{
    public function index()
    {
        $posts = (new Post())->getAll();
        $this->view('posts/index', ['posts' => $posts]);
    }
}
```

### 5. Vue

```php
<?php foreach ($posts as $post): ?>
    <article>
        <h2><?= escape($post['title']) ?></h2>
        <p><?= escape($post['content']) ?></p>
    </article>
<?php endforeach; ?>
```

## 🔒 Sécurité

- ✅ Requêtes préparées (prévention SQL injection)
- ✅ Hash bcrypt pour mots de passe
- ✅ Escape automatique (`escape()`)
- ✅ Validation des entrées
- ✅ CSRF tokens disponibles
- ✅ Middleware d'authentification

## 📞 Support

Erreurs courantes:

**404 Not Found**

- Vérifier l'URL (avec `/BondoMVC`)
- Vérifier que le contrôleur existe
- Vérifier la méthode du contrôleur

**Database error**

- Vérifier `.env`
- Vérifier que la BD existe
- Vérifier permissions utilisateur MySQL

**Erreur 500**

- Activer `APP_DEBUG=true` dans `.env`
- Vérifier les logs Apache

## 📄 Licence

MIT - Libre d'utilisation

## 🎓 Ressources

- [PHP Official](https://www.php.net)
- [PDO Documentation](https://www.php.net/manual/pdo)
- [Composer](https://getcomposer.org)

---

**Créé avec ❤️ - BondoMVC Framework**
