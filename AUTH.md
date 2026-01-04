# 🔐 Système d'Authentification

BondoMVC inclut un **système d'authentification complet** comme Laravel!

## Fonctionnalités

- ✅ Inscription (register)
- ✅ Connexion (login)
- ✅ Déconnexion (logout)
- ✅ Mot de passe oublié
- ✅ Changement de mot de passe
- ✅ Gestion des sessions
- ✅ Middleware de protection
- ✅ Rôles et permissions (TODO)

## Utilisation dans votre contrôleur

```php
use Core\Auth;

// Initialiser l'authentification
Auth::init();

// Vérifier si connecté
if (Auth::check()) {
    echo "Utilisateur connecté";
}

// Récupérer l'utilisateur
$user = Auth::user();
echo $user['email'];

// Obtenir l'ID
$id = Auth::id();

// Vérifier une permission
if (Auth::hasPermission('edit_posts')) {
    // Allowed
}

// Connecter un utilisateur
Auth::login($user);

// Déconnecter
Auth::logout();

// Connexion par credentials
if (Auth::attempt($email, $password)) {
    // Success
}

// S'inscrire
if (Auth::register($name, $email, $password)) {
    // Success
}

// Réinitialiser mot de passe
Auth::resetPassword($email, $newPassword);
```

## Routes disponibles

- `GET /auth/login` - Formulaire de connexion
- `POST /auth/do-login` - Traiter la connexion
- `GET /auth/register` - Formulaire d'inscription
- `POST /auth/do-register` - Traiter l'inscription
- `GET /auth/logout` - Déconnecter
- `GET /auth/forgot-password` - Mot de passe oublié
- `POST /auth/do-reset-password` - Traiter la réinitialisation
- `GET /dashboard` - Tableau de bord (protégé)
- `GET /dashboard/profile` - Profil (protégé)

## Protéger une route

```php
use App\Middleware\AuthMiddleware;

class YourController extends Controller
{
    public function __construct()
    {
        Auth::init();

        if (!Auth::check()) {
            redirect('/auth/login');
        }
    }
}
```

Ou utiliser le middleware:

```php
public function __construct()
{
    $middleware = new AuthMiddleware();
    if (!$middleware->handle()) {
        exit;
    }
}
```

## Créer des vues

Les vues Auth se trouvent dans `app/views/auth/`:

- `login.php` - Connexion
- `register.php` - Inscription
- `forgot-password.php` - Mot de passe oublié

Et les vues Dashboard dans `app/views/dashboard/`:

- `index.php` - Tableau de bord
- `profile.php` - Profil
- `edit-profile.php` - Éditer profil
- `change-password.php` - Changer mot de passe

## Base de données

Assurez-vous que votre table `users` existe:

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

Ou utiliser les migrations:

```bash
php artisan migrate
```

## Helpers globaux

Utilisez les helpers partout:

```php
// Vérifier l'authentification
if (isAuth()) {
    echo "Connecté";
}

// Récupérer l'utilisateur
$user = auth();
echo $user['name'];
```
