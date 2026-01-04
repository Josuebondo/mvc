# 🧪 Tests Unitaires

Exécutez les tests avec:

```bash
composer test
# ou
./vendor/bin/phpunit
```

## Structure des tests

```
tests/
├── Unit/              ← Tests individuels (Helpers, Validator, etc.)
├── Feature/           ← Tests d'intégration (Routes, etc.)
├── bootstrap.php      ← Configuration PHPUnit
└── TestCase.php       ← Classe de base
```

## Écrire un test

```php
namespace Tests\Unit;

use Tests\TestCase;

class MyTest extends TestCase
{
    /** @test */
    public function test_something()
    {
        $this->assertTrue(true);
    }
}
```

## Commandes utiles

```bash
# Tous les tests
./vendor/bin/phpunit

# Seulement Unit tests
./vendor/bin/phpunit tests/Unit

# Avec couverture de code
./vendor/bin/phpunit --coverage-html coverage

# Verbose
./vendor/bin/phpunit --verbose
```
