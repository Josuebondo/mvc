# 📖 Documentation BondoMVC

Bienvenue dans la documentation complète de **BondoMVC** - un framework PHP MVC léger et performant!

## Table des matières

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Routing](#routing)
4. [Contrôleurs](#contrôleurs)
5. [Modèles](#modèles)
6. [Vues](#vues)
7. [Validation](#validation)
8. [Authentification](#authentification)
9. [Middleware](#middleware)
10. [CLI Artisan](#cli-artisan)
11. [Migrations](#migrations)
12. [Tests](#tests)

---

## Installation

### Avec Composer

```bash
composer create-project bondomvc/mvc mon-projet
cd mon-projet
php artisan serve
```

### Sans Composer

```bash
git clone https://github.com/Josuebondo/mvc.git mon-projet
cd mon-projet
php setup.php
php artisan serve
```

### Configuration

Éditez `.env`:

```env
APP_NAME=MonApp
APP_ENV=development
URLROOT=/mon-projet/

DB_HOST=localhost
DB_NAME=ma_base
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
```

---

## Routing

Les routes sont **automatiques** basées sur les contrôleurs!

### Structure

```
URL: http://localhost:8000/controller/method/param
↓
Appelle: App\Controllers\ControllerController::methodAction($param)
```

### Exemples

| URL            | Contrôleur      | Méthode  |
| -------------- | --------------- | -------- |
| `/`            | HomeController  | index    |
| `/blog`        | BlogController  | index    |
| `/blog/show/5` | BlogController  | show(5)  |
| `/admin/users` | AdminController | users    |
| `/api/posts/1` | ApiController   | posts(1) |

### Conventions

- `camelCase` pour les noms de méthodes
- Les traits d'union (`-`) deviennent des underscores (`_`)
- `/blog-post` → `blog_post()` ou `/blog/post` → `post()`

---

## Contrôleurs

Les contrôleurs gèrent la logique de votre application.

### Créer un contrôleur

```bash
php artisan make:controller ProductController
```

Crée: `app/controllers/ProductController.php`

### Structure de base

```php
<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    private $product;

    public function __construct() {
        $this->product = new Product();
    }

    public function index() {
        $products = $this->product->getAll();
        $this->view('products/index', [
            'products' => $products
        ]);
    }

    public function show($id) {
        $product = $this->product->getById($id);

        if (!$product) {
            abort(404);
        }

        $this->view('products/show', ['product' => $product]);
    }

    public function create() {
        $this->view('products/create');
    }

    public function store() {
        $name = getInput('name');
        $price = getInput('price');

        // Valider
        $validator = new Validator([...], [...]);

        if ($validator->hasErrors()) {
            setSession('errors', $validator->errors());
            redirect('/product/create');
        }

        // Créer
        $this->product->create([
            'name' => $name,
            'price' => $price
        ]);

        redirect('/product');
    }
}
```

### Helpers de contrôleur

```php
// Rendu d'une vue
$this->view('products/index', $data);

// Redirection
$this->redirect('/products');
abort(404);
```

---

## Modèles

Les modèles gèrent l'accès aux données.

### Créer un modèle

```bash
php artisan make:model Product
```

### Structure de base

```php
<?php

namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected string $table = 'products';

    // Récupérer tous
    public function getAll() {
        return $this->db()->fetchAll("SELECT * FROM {$this->table}");
    }

    // Récupérer par ID
    public function getById($id) {
        return $this->db()->fetch(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );
    }

    // Créer
    public function create(array $data) {
        return $this->db()->insert($this->table, $data);
    }

    // Mettre à jour
    public function update($id, array $data) {
        return $this->db()->update($this->table, $data, ['id' => $id]);
    }

    // Supprimer
    public function delete($id) {
        return $this->db()->delete($this->table, ['id' => $id]);
    }

    // Méthodes personnalisées
    public function getActive() {
        return $this->db()->fetchAll(
            "SELECT * FROM {$this->table} WHERE active = 1"
        );
    }

    public function search($query) {
        return $this->db()->fetchAll(
            "SELECT * FROM {$this->table} WHERE name LIKE ?",
            ["%$query%"]
        );
    }
}
```

### Utilisation dans un contrôleur

```php
$product = new Product();
$all = $product->getAll();
$one = $product->getById(1);
$new = $product->create(['name' => 'Test']);
$product->update(1, ['name' => 'Updated']);
$product->delete(1);
```

---

## Vues

Les vues sont des fichiers PHP simples.

### Structure

```
app/views/
├── products/
│   ├── index.php
│   ├── show.php
│   ├── create.php
│   └── edit.php
├── layouts/
│   ├── header.php
│   └── footer.php
└── errors/
    └── 404.php
```

### Afficher une vue

```php
// Dans un contrôleur
$this->view('products/index', [
    'products' => $products,
    'title' => 'Produits'
]);
```

### Fichier de vue (app/views/products/index.php)

```php
<h1><?php echo $title; ?></h1>

<table>
    <tr>
        <th>Nom</th>
        <th>Prix</th>
    </tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo escape($product['name']); ?></td>
            <td><?php echo $product['price']; ?> €</td>
        </tr>
    <?php endforeach; ?>
</table>
```

### Helpers de vue

```php
<?php
// Échapper le HTML (XSS protection)
echo escape($data);

// URL absolue
echo url('/products');

// Asset (CSS, JS, images)
echo asset('/css/style.css');

// Afficher les erreurs
if (hasSessionErrors('name')): ?>
    <span class="error"><?php echo getSessionError('name'); ?></span>
<?php endif; ?>
```

---

## Validation

Validez les données entrantes facilement.

### Règles disponibles

| Règle                | Description                        |
| -------------------- | ---------------------------------- |
| `required`           | Champ obligatoire                  |
| `email`              | Format email                       |
| `min:6`              | Longueur minimale                  |
| `max:255`            | Longueur maximale                  |
| `numeric`            | Uniquement des chiffres            |
| `confirmed:password` | Confirmation (ex: 2x mot de passe) |
| `unique:table`       | Valeur unique en BD                |
| `match:other`        | Correspond à un autre champ        |

### Utilisation

```php
use Core\Validator;

$validator = new Validator([
    'name' => getInput('name'),
    'email' => getInput('email'),
    'password' => getInput('password'),
    'password_confirm' => getInput('password_confirm')
], [
    'name' => 'required|min:2|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:6',
    'password_confirm' => 'required|confirmed:password'
]);

if ($validator->hasErrors()) {
    setSession('errors', $validator->errors());
    redirect('/register');
}

// Data est valide, continuer...
```

---

## Authentification

Gérez les utilisateurs facilement.

### Connexion

```php
use Core\Auth;

Auth::init();

if (Auth::attempt($email, $password)) {
    // Connecté!
    redirect('/dashboard');
} else {
    setSession('error', 'Invalid credentials');
    redirect('/login');
}
```

### Inscription

```php
if (Auth::register($name, $email, $password)) {
    // Inscrit et connecté!
    redirect('/dashboard');
}
```

### Vérifier l'authentification

```php
Auth::init();

if (Auth::check()) {
    $user = Auth::user();
    echo "Connecté en tant que " . $user['name'];
} else {
    redirect('/auth/login');
}
```

Voir [AUTH.md](AUTH.md) pour plus de détails.

---

## Middleware

Filtrez les requêtes avant qu'elles n'atteignent le contrôleur.

### Créer un middleware

```bash
php artisan make:middleware AdminMiddleware
```

### Exemple

```php
<?php

namespace App\Middleware;

use Core\Middleware;
use Core\Auth;

class AdminMiddleware extends Middleware
{
    public function handle(): bool
    {
        Auth::init();

        if (Auth::user()['role'] !== 'admin') {
            $this->redirect('/');
            return false;
        }

        return true; // Continuer
    }
}
```

### Utiliser un middleware

```php
class AdminController extends Controller
{
    public function __construct() {
        $middleware = new AdminMiddleware();
        if (!$middleware->handle()) {
            exit;
        }
    }
}
```

---

## CLI Artisan

Utilisez le CLI comme Laravel!

### Commandes disponibles

```bash
# Serveur de développement
php artisan serve
php artisan serve 3000

# Générer du code
php artisan make:controller NomController
php artisan make:model NomModel
php artisan make:middleware NomMiddleware
php artisan make:migration CreateTableName

# Migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh

# Interactif
php artisan tinker

# Aide
php artisan help
```

---

## Migrations

Versionnez votre schéma de base de données.

### Créer une migration

```bash
php artisan make:migration CreateUsersTable
```

### Écrire une migration

```php
<?php

namespace Database\Migrations;

use Core\Database;

class CreateUsersTable extends Migration
{
    public function up(Database $db)
    {
        $sql = "CREATE TABLE users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $db->getConnection()->exec($sql);
    }

    public function down(Database $db)
    {
        $db->getConnection()->exec("DROP TABLE IF EXISTS users");
    }
}
```

### Exécuter les migrations

```bash
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh
```

Voir [Migrations](database/MIGRATIONS.md) pour plus.

---

## Tests

Testez votre code avec PHPUnit.

### Écrire un test

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;

class CalculatorTest extends TestCase
{
    /** @test */
    public function test_addition()
    {
        $this->assertEquals(2, 1 + 1);
    }

    /** @test */
    public function test_something_else()
    {
        $this->assertTrue(true);
    }
}
```

### Exécuter les tests

```bash
./vendor/bin/phpunit
./vendor/bin/phpunit tests/Unit
./vendor/bin/phpunit --coverage-html coverage
```

Voir [TESTS.md](TESTS.md) pour plus.

---

## Helpers globaux

Utilisez ces fonctions n'importe où:

```php
// Debugging
dd($data);           // Dump & die
dump($data);         // Afficher

// URL & Assets
url('/path');        // URL absolue
asset('/css/app');   // Chemin asset

// Requête
isPost();            // POST?
isGet();             // GET?
isAjax();            // AJAX?
getInput('name');    // $_POST['name']

// Session
session('key');      // Récupérer
setSession('key', $value); // Définir
unsetSession('key'); // Supprimer
hasSession('key');   // Existe?

// Auth
auth();              // Utilisateur courant
isAuth();            // Connecté?

// Security
escape($string);     // Échapper HTML (XSS)
generateCsrfToken(); // Token CSRF
verifyCsrfToken($token); // Vérifier token

// Autre
abort(404);          // Lancer une erreur
redirect('/path');   // Redirection
```

---

## Structure complète

```
mon-projet/
├── app/
│   ├── controllers/    ← Vos contrôleurs
│   ├── models/         ← Vos modèles
│   ├── middleware/     ← Vos middlewares
│   └── views/          ← Vos vues
├── core/
│   ├── App.php         ← Router
│   ├── Controller.php  ← Base controller
│   ├── Model.php       ← Base model
│   ├── Database.php    ← ORM
│   ├── Validator.php   ← Validation
│   ├── Auth.php        ← Authentification
│   ├── Middleware.php  ← Base middleware
│   └── Helpers.php     ← Fonctions globales
├── config/
│   └── config.php      ← Configuration
├── database/
│   └── migrations/     ← Vos migrations
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── storage/
│   └── uploads/        ← Fichiers uploads
├── .env                ← Configuration locale
├── artisan             ← CLI Tool
├── index.php           ← Point d'entrée
├── composer.json       ← Dépendances
└── README.md           ← Ce fichier
```

---

## Ressources

- 🔐 [Authentification](AUTH.md)
- 📚 [Exemples](EXAMPLES.md)
- 📊 [Migrations](database/MIGRATIONS.md)
- 🧪 [Tests](TESTS.md)
- 🚀 [XAMPP Guide](XAMPP.md)
- 📦 [Sans Composer](SANS_COMPOSER.md)

---

## Support

- 💬 [GitHub Issues](https://github.com/Josuebondo/mvc/issues)
- 📧 dev@bondomvc.com
- 🌐 https://github.com/Josuebondo/mvc

---

**Bonne chance avec BondoMVC!** 🎉
