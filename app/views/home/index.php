<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        header {
            background: white;
            padding: 40px 20px;
            border-radius: 10px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        header h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 2.5em;
        }

        header p {
            color: #666;
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .version {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-right: 10px;
        }

        .badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            margin-right: 10px;
        }

        /* Features Grid */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .feature {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .feature h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .feature p {
            color: #666;
            margin-bottom: 10px;
        }

        .feature ul {
            list-style: none;
            padding-left: 0;
        }

        .feature li {
            padding: 5px 0;
            color: #666;
        }

        .feature li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-right: 8px;
        }

        /* Getting Started */
        .getting-started {
            background: white;
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .getting-started h2 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
        }

        .install-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .install-method {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .install-method h3 {
            color: #667eea;
            margin-bottom: 15px;
        }

        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            line-height: 1.4;
        }

        .code-block code {
            color: #66d9ef;
        }

        /* Buttons */
        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1em;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        /* Commands */
        .commands {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
        }

        .commands h2 {
            color: #66d9ef;
            margin-bottom: 20px;
            text-align: center;
        }

        .command-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .command-item {
            background: #383838;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #66d9ef;
        }

        .command-item code {
            color: #a1efe4;
            display: block;
            margin-bottom: 8px;
            word-break: break-all;
        }

        .command-item p {
            color: #999;
            font-size: 0.9em;
        }

        /* Documentation Links */
        .docs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .doc-link {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: #667eea;
            font-weight: bold;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .doc-link:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .stat {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            color: #667eea;
            font-weight: bold;
        }

        .stat-label {
            color: #666;
            margin-top: 10px;
        }

        /* Footer */
        footer {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            color: #666;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        footer a {
            color: #667eea;
            text-decoration: none;
            margin: 0 15px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header h1 {
                font-size: 1.8em;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        .highlight {
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }

        .highlight strong {
            color: #856404;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <header>
            <div style="margin-bottom: 20px;">
                <img src="<?php echo asset('images/logo.png'); ?>" alt="BondoMVC Logo" style="height: 100px; object-fit: contain;">
            </div>
            <h1>BondoMVC</h1>
            <p>Un framework PHP MVC léger, puissant et facile à utiliser</p>

            <div style="margin-top: 15px;">
                <span class="version">v<?php echo $version; ?></span>
                <span class="badge">Production Ready</span>
                <span class="badge">0 Dépendances</span>
            </div>

            <div class="buttons" style="margin-top: 30px;">
                <a href="#getting-started" class="btn btn-primary">🚀 Commencer</a>
                <a href="DOCS.md" class="btn btn-secondary">📖 Documentation</a>
                <a href="https://github.com/Josuebondo/mvc" target="_blank" class="btn btn-secondary">💻 GitHub</a>
            </div>
        </header>

        <!-- Stats -->
        <div class="stats">
            <div class="stat">
                <div class="stat-number">0KB</div>
                <div class="stat-label">Dépendances</div>
            </div>
            <div class="stat">
                <div class="stat-number">&lt;1MB</div>
                <div class="stat-label">Poids total</div>
            </div>
            <div class="stat">
                <div class="stat-number">8.0+</div>
                <div class="stat-label">PHP requis</div>
            </div>
            <div class="stat">
                <div class="stat-number">20+</div>
                <div class="stat-label">Helpers</div>
            </div>
        </div>

        <!-- Features -->
        <div class="features">
            <div class="feature">
                <h3>🎨 MVC Complet</h3>
                <p>Architecture Model-View-Controller complète et prête à l'emploi</p>
                <ul>
                    <li>Router automatique</li>
                    <li>Contrôleurs et modèles</li>
                    <li>Moteur de vues simple</li>
                </ul>
            </div>

            <div class="feature">
                <h3>🔐 Authentification</h3>
                <p>Système d'authentification complet et sécurisé</p>
                <ul>
                    <li>Login/Register/Logout</li>
                    <li>Gestion de sessions</li>
                    <li>Middleware de protection</li>
                </ul>
            </div>

            <div class="feature">
                <h3>✅ Validation</h3>
                <p>Validez facilement vos formulaires</p>
                <ul>
                    <li>8+ règles de validation</li>
                    <li>Messages d'erreur personnalisés</li>
                    <li>Validation côté serveur</li>
                </ul>
            </div>

            <div class="feature">
                <h3>💾 Database ORM</h3>
                <p>Requêtes faciles et sécurisées</p>
                <ul>
                    <li>Prepared statements</li>
                    <li>Protection XSS/SQL injection</li>
                    <li>CRUD simplifié</li>
                </ul>
            </div>

            <div class="feature">
                <h3>🔄 Migrations</h3>
                <p>Versionnez votre schéma de base de données</p>
                <ul>
                    <li>Système de migrations</li>
                    <li>Up/Down reversible</li>
                    <li>Comme Laravel</li>
                </ul>
            </div>

            <div class="feature">
                <h3>🔧 CLI Artisan</h3>
                <p>Générateur de code en ligne de commande</p>
                <ul>
                    <li>Créer contrôleurs/modèles</li>
                    <li>Serveur de développement</li>
                    <li>Shell interactif</li>
                </ul>
            </div>

            <div class="feature">
                <h3>🧪 Tests</h3>
                <p>PHPUnit intégré pour tester votre code</p>
                <ul>
                    <li>Tests unitaires</li>
                    <li>Tests fonctionnels</li>
                    <li>Couverture de code</li>
                </ul>
            </div>

            <div class="feature">
                <h3>⚡ Léger & Rapide</h3>
                <p>Pas de bloat, juste l'essentiel</p>
                <ul>
                    <li>0 dépendances en production</li>
                    <li>Moins de 1MB</li>
                    <li>Temps de réponse rapide</li>
                </ul>
            </div>
        </div>

        <!-- Getting Started -->
        <div class="getting-started" id="getting-started">
            <h2>🚀 Commencer en 30 secondes</h2>

            <div class="highlight">
                <strong>Besoin d'aide?</strong> Consultez la <a href="DOCS.md">documentation complète</a> ou les <a href="EXAMPLES.md">exemples pratiques</a>.
            </div>

            <div class="install-methods">
                <div class="install-method">
                    <h3>Avec Composer</h3>
                    <p>La méthode recommandée (easy & clean)</p>
                    <div class="code-block"><code>composer create-project bondomvc/mvc mon-app
                            cd mon-app
                            php artisan serve</code></div>
                </div>

                <div class="install-method">
                    <h3>Sans Composer</h3>
                    <p>Avec Git ou ZIP</p>
                    <div class="code-block"><code>git clone https://github.com/Josuebondo/mvc.git
                            cd mvc
                            php setup.php
                            php artisan serve</code></div>
                </div>

                <div class="install-method">
                    <h3>Avec XAMPP</h3>
                    <p>Sur un serveur local</p>
                    <div class="code-block"><code>cd C:\xampp\htdocs
                            git clone https://github.com/Josuebondo/mvc.git
                            php setup.php
                            php artisan serve</code></div>
                </div>
            </div>
        </div>

        <!-- Commands -->
        <div class="commands">
            <h2>⚙️ Commandes Artisan</h2>
            <div class="command-list">
                <div class="command-item">
                    <code>php artisan serve</code>
                    <p>Démarrer le serveur de développement</p>
                </div>

                <div class="command-item">
                    <code>php artisan make:controller Name</code>
                    <p>Créer un nouveau contrôleur</p>
                </div>

                <div class="command-item">
                    <code>php artisan make:model Name</code>
                    <p>Créer un nouveau modèle</p>
                </div>

                <div class="command-item">
                    <code>php artisan make:migration Name</code>
                    <p>Créer une nouvelle migration</p>
                </div>

                <div class="command-item">
                    <code>php artisan migrate</code>
                    <p>Exécuter les migrations</p>
                </div>

                <div class="command-item">
                    <code>php artisan tinker</code>
                    <p>Shell interactif PHP</p>
                </div>
            </div>
        </div>

        <!-- Documentation -->
        <div class="getting-started">
            <h2>📚 Documentation</h2>
            <div class="docs">
                <a href="DOCS.md" class="doc-link">📖 Guide complet</a>
                <a href="AUTH.md" class="doc-link">🔐 Authentification</a>
                <a href="EXAMPLES.md" class="doc-link">📚 Exemples</a>
                <a href="TESTS.md" class="doc-link">🧪 Tests</a>
                <a href="database/MIGRATIONS.md" class="doc-link">📊 Migrations</a>
                <a href="XAMPP.md" class="doc-link">⚙️ XAMPP</a>
                <a href="SANS_COMPOSER.md" class="doc-link">📦 Sans Composer</a>
                <a href="PACKAGIST.md" class="doc-link">🚀 Packagist</a>
            </div>
        </div>

        <!-- Example Project -->
        <div class="getting-started">
            <h2>💡 Créer votre première app</h2>
            <p style="text-align: center; margin-bottom: 20px;">Exemple: Un gestionnaire de posts simple</p>

            <div class="code-block" style="margin-bottom: 20px;">
                <code>
                    # 1. Créer les fichiers
                    php artisan make:controller PostController
                    php artisan make:model Post
                    php artisan make:migration CreatePostsTable

                    # 2. Configurer la migration (edit database/migrations/...)
                    # CREATE TABLE posts (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255), content TEXT, ...)

                    # 3. Lancer les migrations
                    php artisan migrate

                    # 4. Éditer le contrôleur (app/controllers/PostController.php)
                    class PostController extends Controller {
                    public function index() {
                    $posts = (new Post())->getAll();
                    $this->view('posts/index', ['posts' => $posts]);
                    }
                    }

                    # 5. Créer la vue (app/views/posts/index.php)
                    &lt;h1&gt;Mes Posts&lt;/h1&gt;
                    &lt;?php foreach ($posts as $post): ?&gt;
                    &lt;h2&gt;&lt;?php echo $post['title']; ?&gt;&lt;/h2&gt;
                    &lt;p&gt;&lt;?php echo $post['content']; ?&gt;&lt;/p&gt;
                    &lt;?php endforeach; ?&gt;

                    # 6. Accéder à http://localhost:8000/post
                </code>
            </div>

            <p style="color: #666; text-align: center;">
                C'est tout! Vous avez une app CRUD fonctionnelle! 🎉
            </p>
        </div>

        <!-- Footer -->
        <footer>
            <p>🎯 <strong>BondoMVC</strong> - Framework PHP MVC Léger</p>
            <p style="margin-top: 15px;">
                <a href="https://github.com/Josuebondo/mvc" target="_blank">GitHub</a>
                <a href="https://packagist.org/packages/bondomvc/mvc" target="_blank">Packagist</a>
                <a href="DOCS.md">Documentation</a>
                <a href="https://github.com/Josuebondo/mvc/issues" target="_blank">Issues</a>
            </p>
            <p style="margin-top: 15px; color: #999;">
                Créé avec ❤️ pour les développeurs PHP
            </p>
        </footer>
    </div>
</body>

</html>