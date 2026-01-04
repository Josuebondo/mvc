# 📚 Exemples de Projets

Retrouvez ici des exemples complets pour démarrer rapidement avec BondoMVC.

## 1️⃣ Blog simple

Créez un blog avec posts et commentaires:

```bash
php artisan make:controller PostController
php artisan make:model Post
php artisan make:migration CreatePostsTable
```

**Migration** (database/migrations/TIMESTAMP_create_posts_table.php):
```php
public function up(Database $db)
{
    $sql = "CREATE TABLE posts (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        content LONGTEXT NOT NULL,
        author_id INT NOT NULL,
        published_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (author_id) REFERENCES users(id)
    )";
    
    $db->getConnection()->exec($sql);
}
```

**Contrôleur** (app/controllers/PostController.php):
```php
class PostController extends Controller
{
    private $post;
    
    public function __construct() {
        $this->post = new Post();
    }
    
    public function index() {
        $posts = $this->post->getAll();
        $this->view('posts/index', ['posts' => $posts]);
    }
    
    public function show($id) {
        $post = $this->post->getById($id);
        $this->view('posts/show', ['post' => $post]);
    }
}
```

**Route**: `/post` → `PostController@index`

---

## 2️⃣ E-commerce basique

Créez une boutique simple:

```bash
php artisan make:controller ProductController
php artisan make:controller CartController
php artisan make:model Product
php artisan make:migration CreateProductsTable
php artisan make:migration CreateOrdersTable
```

**ProductController**:
```php
class ProductController extends Controller
{
    public function index() {
        $products = (new Product())->getAll();
        $this->view('products/index', ['products' => $products]);
    }
    
    public function show($id) {
        $product = (new Product())->getById($id);
        $this->view('products/show', ['product' => $product]);
    }
}
```

**CartController**:
```php
class CartController extends Controller
{
    public function __construct() {
        Auth::init();
        if (!Auth::check()) {
            redirect('/auth/login');
        }
    }
    
    public function index() {
        $cart = session('cart') ?? [];
        $this->view('cart/index', ['cart' => $cart]);
    }
    
    public function addItem($productId) {
        $cart = session('cart') ?? [];
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;
        setSession('cart', $cart);
        redirect('/cart');
    }
}
```

---

## 3️⃣ Système d'événements

Créez un calendrier d'événements:

```bash
php artisan make:controller EventController
php artisan make:model Event
php artisan make:migration CreateEventsTable
```

**Exemple**:
```php
class EventController extends Controller
{
    private $event;
    
    public function __construct() {
        $this->event = new Event();
    }
    
    public function upcoming() {
        $events = $this->event->getUpcoming();
        $this->view('events/upcoming', ['events' => $events]);
    }
    
    public function calendar($month = null) {
        $month = $month ?? date('m');
        $year = date('Y');
        
        $events = $this->event->getByMonth($month, $year);
        $this->view('events/calendar', ['events' => $events]);
    }
}
```

---

## 4️⃣ API REST

Créez une API pour vos données:

```bash
php artisan make:controller Api/UserController
php artisan make:controller Api/PostController
```

**UserController API**:
```php
namespace App\Controllers\Api;

class UserController
{
    public function index() {
        $users = (new UserModel())->getAll();
        return $this->json(['data' => $users]);
    }
    
    public function show($id) {
        $user = (new UserModel())->getById($id);
        
        if (!$user) {
            return $this->json(['error' => 'Not found'], 404);
        }
        
        return $this->json(['data' => $user]);
    }
    
    private function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
```

**Routes**:
- `GET /api/users` → Liste
- `GET /api/users/1` → Détail
- `POST /api/users` → Créer
- `PUT /api/users/1` → Modifier
- `DELETE /api/users/1` → Supprimer

---

## 5️⃣ Système de permission

Protégez les routes:

```php
use App\Middleware\AuthMiddleware;

class AdminController extends Controller
{
    public function __construct() {
        $middleware = new AuthMiddleware();
        
        if (!$middleware->handle()) {
            exit;
        }
        
        // Vérifier admin
        if (Auth::user()['role'] !== 'admin') {
            abort(403);
        }
    }
}
```

---

## 6️⃣ Recherche et filtrage

Implémentez une recherche:

```php
class ProductController extends Controller
{
    public function search() {
        $q = getInput('q');
        
        if (!$q) {
            redirect('/products');
        }
        
        $products = (new Product())->search($q);
        $this->view('products/search', ['products' => $products, 'q' => $q]);
    }
}
```

**Model**:
```php
public function search($query) {
    return $this->db()->fetchAll(
        "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?",
        ["%$query%", "%$query%"]
    );
}
```

---

## 7️⃣ Dashboard d'administration

Gérez les données:

```bash
php artisan make:controller AdminController
php artisan make:middleware AdminMiddleware
```

**AdminController**:
```php
class AdminController extends Controller
{
    public function __construct() {
        Auth::init();
        if (!Auth::check() || Auth::user()['role'] !== 'admin') {
            redirect('/');
        }
    }
    
    public function dashboard() {
        $stats = [
            'users' => (new UserModel())->count(),
            'posts' => (new Post())->count(),
            'orders' => (new Order())->count()
        ];
        
        $this->view('admin/dashboard', $stats);
    }
}
```

---

## Démarrer un projet

```bash
# 1. Créer le projet
composer create-project bondomvc/mvc mon-blog

# 2. Configurer .env
cp .env.example .env
# Éditer DB_NAME, DB_USER, etc.

# 3. Créer la base de données
# CREATE DATABASE mon_blog;

# 4. Générer les fichiers
php artisan make:controller PostController
php artisan make:model Post

# 5. Lancer le serveur
php artisan serve

# 6. Accéder à http://localhost:8000/post
```

---

## Prochaines étapes

- 📖 Lire la [documentation complète](README.md)
- 🔐 Implémenter l'[authentification](AUTH.md)
- 📊 Ajouter les [migrations](database/MIGRATIONS.md)
- 🧪 Écrire les [tests](TESTS.md)

Amusez-vous! 🚀
