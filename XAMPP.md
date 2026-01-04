# 🚀 Utiliser BondoMVC avec XAMPP (comme Laravel)

BondoMVC inclut une **commande `artisan`** comme Laravel pour faciliter le développement!

## Installation avec XAMPP

### 1. Téléchargez le framework

**Option A: Avec Git**
```bash
cd C:\xampp\htdocs
git clone https://github.com/Josuebondo/mvc.git mon-site
cd mon-site
```

**Option B: ZIP**
1. Téléchargez: https://github.com/Josuebondo/mvc/archive/refs/heads/main.zip
2. Décompressez dans `C:\xampp\htdocs`
3. Renommez le dossier en `mon-site`

### 2. Configurez le projet

```bash
cd C:\xampp\htdocs\mon-site
php setup.php
```

### 3. Éditez `.env`

```env
APP_NAME=MonSite
URLROOT=/mon-site/
DB_HOST=localhost
DB_NAME=ma_base
DB_USER=root
DB_PASS=
```

## Commandes disponibles

### Démarrer le serveur de développement

```bash
php artisan serve          # Port 8000
php artisan serve 3000     # Port 3000
```

Accédez à: **http://localhost:8000**

### Créer un contrôleur

```bash
php artisan make:controller ProductController
```

Crée: `app/controllers/ProductController.php`

### Créer un modèle

```bash
php artisan make:model Product
```

Crée: `app/models/Product.php`

### Créer un middleware

```bash
php artisan make:middleware AdminMiddleware
```

Crée: `app/middleware/AdminMiddleware.php`

### Shell interactif

```bash
php artisan tinker
```

Testez du code PHP interactivement!

## Workflow complet (exemple)

```bash
# 1. Aller au projet
cd C:\xampp\htdocs\mon-site

# 2. Créer les fichiers
php artisan make:controller BlogController
php artisan make:model Post

# 3. Démarrer le serveur
php artisan serve

# 4. Accédez à http://localhost:8000/blog
```

## Structure du projet

```
mon-site/
├── app/
│   ├── controllers/     ← php artisan make:controller ici
│   ├── models/         ← php artisan make:model ici
│   ├── middleware/     ← php artisan make:middleware ici
│   └── views/          ← Vos templates HTML
├── config/
├── core/               ← Framework core (ne pas modifier)
├── public/
├── storage/
├── .env                ← Configuration
├── artisan             ← Le CLI (comme Laravel!)
├── artisan.bat         ← Pour Windows
└── index.php           ← Point d'entrée
```

## Sur Windows (XAMPP GUI)

Vous pouvez aussi:

1. **Ouvrir le terminal** dans le dossier du projet
2. Taper: `artisan serve`
3. Accéder à: http://localhost:8000

Les fichiers `.bat` permettent d'utiliser `artisan` directement!

## Créer votre première page

### Étape 1: Créer le contrôleur
```bash
php artisan make:controller HomeController
```

### Étape 2: Éditez `app/controllers/HomeController.php`
```php
<?php
namespace App\Controllers;
use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home/index', [
            'title' => 'Bienvenue!'
        ]);
    }
}
```

### Étape 3: Créez la vue `app/views/home/index.php`
```php
<h1><?php echo $title; ?></h1>
<p>Bienvenue sur BondoMVC avec XAMPP!</p>
```

### Étape 4: Démarrez le serveur
```bash
php artisan serve
```

### Étape 5: Accédez à
```
http://localhost:8000/home
```

## Comparaison Laravel vs BondoMVC

| Fonctionnalité | Laravel | BondoMVC |
|---|---|---|
| `artisan serve` | ✅ Oui | ✅ Oui |
| `make:controller` | ✅ Oui | ✅ Oui |
| `make:model` | ✅ Oui | ✅ Oui |
| `make:middleware` | ✅ Oui | ✅ Oui |
| Dépendances | ❌ Beaucoup | ✅ Aucune! |
| Léger | ❌ 50MB+ | ✅ <1MB |
| Apprentissage | ❌ Complexe | ✅ Simple |

## Commandes rapides

```bash
# Créer une app complète
php artisan make:controller ProductController
php artisan make:model Product
php artisan make:middleware ProductMiddleware
php artisan serve

# À partir de là, modifiez:
# app/controllers/ProductController.php
# app/models/Product.php
# app/views/product/...
```

## Besoin d'aide?

- 📖 Lire [README.md](README.md)
- 🔧 Lire [SANS_COMPOSER.md](SANS_COMPOSER.md)
- 📚 Lire [INSTALLATION.md](INSTALLATION.md)
- 💬 GitHub: https://github.com/Josuebondo/mvc

## Exemple complet

Créons un gestionnaire de posts:

```bash
# Créer les fichiers
php artisan make:controller PostController
php artisan make:model Post
php artisan make:middleware AuthMiddleware

# Créer une base de données (phpMyAdmin ou MySQL)
# CREATE TABLE posts (
#   id INT PRIMARY KEY AUTO_INCREMENT,
#   title VARCHAR(255),
#   content TEXT,
#   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
# );

# Éditer le contrôleur
# Éditer la vue
# Démarrer le serveur
php artisan serve

# Accéder à http://localhost:8000/post
```

**Voilà! Vous avez un site avec BondoMVC, aussi simple que Laravel! 🎉**
