# 🚀 Utiliser BondoMVC sans Composer

BondoMVC n'a **aucune dépendance en production** - vous pouvez l'utiliser directement avec PHP!

## Installation manuelle

### Option 1: Télécharger le ZIP

1. **Téléchargez** le repo: https://github.com/Josuebondo/mvc/archive/refs/heads/main.zip
2. **Décompressez** dans votre dossier web
3. **Lancez le setup**:
   ```bash
   cd mvc-main
   php setup.php
   ```
4. **Configurez** le fichier `.env`
5. **Démarrez** le serveur:
   ```bash
   php -S localhost:8000
   ```

### Option 2: Cloner avec Git

```bash
git clone https://github.com/Josuebondo/mvc.git mon-site
cd mon-site
php setup.php
php -S localhost:8000
```

### Option 3: Installation manuelle (sans aucun outil)

1. Téléchargez les fichiers manuellement
2. Copiez-les dans votre dossier web (htdocs, public_html, etc.)
3. Créez le fichier `.env`:
   ```bash
   cp .env.example .env
   ```
4. Éditez `.env` avec vos paramètres
5. Accédez à: `http://localhost/mvc`

## Structure après installation

```
mvc/
├── app/
│   ├── controllers/
│   ├── models/
│   ├── middleware/
│   └── views/
├── core/
├── config/
├── public/
├── storage/
├── .env              ← À configurer!
├── index.php         ← Point d'entrée
└── console           ← Générateur de code
```

## Configuration (.env)

Éditez `.env`:

```env
APP_NAME=MonSite
APP_ENV=development
URLROOT=/mvc/                    # Changez selon votre dossier
DB_HOST=localhost
DB_NAME=ma_base
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
```

## Démarrer le serveur

**Avec PHP intégré:**
```bash
php -S localhost:8000
```

**Avec Apache:**
```
http://localhost/mvc/
```

**Avec Nginx:**
Configure votre serveur pour pointer vers `index.php`

## Utiliser le console (générateur)

Le framework inclut un **générateur de code CLI**:

```bash
# Créer un contrôleur
php console make:controller ProductController

# Créer un modèle
php console make:model Product

# Créer un middleware
php console make:middleware AdminMiddleware
```

## Créer une première page

### 1. Créer un contrôleur
```bash
php console make:controller BlogController
```

### 2. Éditez `app/controllers/BlogController.php`:
```php
<?php
namespace App\Controllers;
use Core\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $posts = [
            ['id' => 1, 'title' => 'Mon premier post'],
            ['id' => 2, 'title' => 'Mon second post'],
        ];
        
        $this->view('blog/index', ['posts' => $posts]);
    }
}
```

### 3. Créez la vue `app/views/blog/index.php`:
```php
<h1>Blog</h1>
<ul>
    <?php foreach ($posts as $post): ?>
        <li><?php echo $post['title']; ?></li>
    <?php endforeach; ?>
</ul>
```

### 4. Accédez à: `http://localhost:8000/blog`

## Fonctionnalités disponibles

- ✅ **Router automatique** - Les URLs deviennent des contrôleurs
- ✅ **MVC complet** - Modèles, Vues, Contrôleurs
- ✅ **Database ORM** - Requêtes faciles avec `$this->db()`
- ✅ **Validation** - Validez vos formulaires
- ✅ **Middleware** - Protégez vos routes
- ✅ **20+ Helpers** - Fonctions globales pratiques
- ✅ **Générateur CLI** - Créez du code en 1 commande
- ✅ **Gestion d'erreurs** - 404 personnalisé

## Besoin d'aide?

- 📖 Lire [README.md](README.md)
- 📚 Lire [INSTALLATION.md](INSTALLATION.md)
- 🔧 Vérifier [config/config.php](config/config.php)
- 💬 Visiter https://github.com/Josuebondo/mvc/issues

## Requise minimale

- **PHP** 8.0 ou supérieur
- **MySQL/MariaDB** (optionnel, pour la database)
- **Web server** (PHP intégré, Apache, Nginx, etc.)

C'est tout! Pas de Composer, pas de dépendances externes! 🎉
