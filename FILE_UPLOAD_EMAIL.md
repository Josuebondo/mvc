# 📤 File Upload & Email Guide

## File Upload Handling

### Configuration

Dans votre `.env`:

```env
UPLOAD_MAX_SIZE=5242880
UPLOAD_ALLOWED_EXTENSIONS=jpg,jpeg,png,gif,pdf,doc,docx,xlsx,zip,txt,csv
```

### Usage

Validation et upload simple:

```php
$upload = new \Core\FileUpload($_FILES['avatar'], 'avatars');

if ($upload->isValid()) {
    $path = $upload->save();
    echo "Fichier uploadé: $path";
} else {
    $errors = $upload->getErrors();
    foreach ($errors as $error) {
        echo $error;
    }
}
```

### Options avancées

Personnaliser la taille maximum:

```php
$upload = new \Core\FileUpload($_FILES['document'], 'documents');
$upload->setMaxSize(10 * 1024 * 1024); // 10MB
```

Autoriser uniquement certains types:

```php
$upload->setAllowedTypes([
    'pdf' => 'application/pdf',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
]);
```

### Sécurité

La classe FileUpload protège contre:

- ✅ Les fichiers malveillants (vérification MIME type)
- ✅ Les noms de fichiers avec caractères spéciaux
- ✅ Les débordements de taille
- ✅ Les accès via `is_uploaded_file()`
- ✅ Log automatique des uploads

### Exemple complet dans un contrôleur

```php
<?php

namespace App\Controllers;

use Core\Controller;
use Core\FileUpload;

class ProfileController extends Controller
{
    public function uploadAvatar()
    {
        if (!$_FILES || !isset($_FILES['avatar'])) {
            return $this->json(['error' => 'No file provided'], 400);
        }

        $upload = new FileUpload($_FILES['avatar'], 'avatars');

        if (!$upload->isValid()) {
            return $this->json(['errors' => $upload->getErrors()], 422);
        }

        $path = $upload->save();

        // Update user avatar in database
        $user = auth()->user();
        // $userModel->update($user['id'], ['avatar' => $path]);

        return $this->json([
            'message' => 'Avatar uploadé avec succès',
            'path' => $path,
            'filename' => $upload->getFilename()
        ]);
    }
}
```

---

## Email Service

### Configuration

Dans votre `.env`:

```env
MAIL_DRIVER=php
MAIL_FROM=noreply@bondomvc.local
MAIL_FROM_NAME=BondoMVC
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
```

### Usage Simple

Email HTML:

```php
use Core\Email;

$email = new Email();
$email->to('user@example.com')
      ->subject('Bienvenue')
      ->html('<h1>Bonjour!</h1><p>Merci de vous inscrire.</p>')
      ->send();
```

Email texte:

```php
$email = new Email();
$email->to('user@example.com')
      ->subject('Bienvenue')
      ->text('Merci de vous inscrire.')
      ->send();
```

### Avec Template

```php
$email = new Email();
$email->to('user@example.com')
      ->subject('Bienvenue sur BondoMVC')
      ->template('welcome', ['name' => 'Jean'])
      ->send();
```

Templates disponibles dans `app/views/emails/`:

- `welcome.php` - Email de bienvenue
- `reset-password.php` - Réinitialisation mot de passe

### Ajouter des destinataires

```php
$email = new Email();
$email->to('user@example.com', 'Jean Dupont')
      ->cc('manager@example.com')
      ->bcc('admin@example.com')
      ->subject('Rapport')
      ->html('...')
      ->send();
```

### Joindre des fichiers

```php
$email = new Email();
$email->to('user@example.com')
      ->subject('Rapport PDF')
      ->html('Veuillez trouver le rapport en pièce jointe.')
      ->attach('/path/to/rapport.pdf', 'rapport.pdf')
      ->send();
```

### Personnaliser l'expéditeur

```php
$email = new Email();
$email->from('support@example.com', 'Support')
      ->to('user@example.com')
      ->subject('Aide')
      ->html('Comment puis-je vous aider?')
      ->send();
```

### Exemple: Email de réinitialisation mot de passe

```php
<?php

namespace App\Controllers;

use Core\Controller;
use Core\Email;

class PasswordController extends Controller
{
    public function resetPassword()
    {
        $email = request()->input('email');

        // Générer un token de réinitialisation
        $resetToken = bin2hex(random_bytes(32));
        $resetLink = url('/reset-password?token=' . $resetToken);

        // Envoyer l'email
        $mail = new Email();
        $mail->to($email)
             ->subject('Réinitialiser votre mot de passe')
             ->template('reset-password', [
                 'resetLink' => $resetLink,
                 'expiresIn' => '24 heures'
             ])
             ->send();

        return $this->json(['message' => 'Email envoyé']);
    }
}
```

### Mode Test

Tester votre configuration email:

```php
if (Email::test('votre-email@example.com')) {
    echo "Email de test envoyé avec succès!";
} else {
    echo "Erreur lors de l'envoi du test.";
}
```

### Créer votre propre template

Créez un fichier `app/views/emails/ma-template.php`:

```php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Template</title>
</head>
<body>
    <h1>Bonjour <?php echo htmlspecialchars($name ?? 'User'); ?>!</h1>
    <p><?php echo htmlspecialchars($message ?? ''); ?></p>
</body>
</html>
```

Utiliser le template:

```php
$email = new Email();
$email->to('user@example.com')
      ->subject('Mon Template')
      ->template('ma-template', [
          'name' => 'Jean',
          'message' => 'Ceci est un message personnalisé'
      ])
      ->send();
```

---

## Logging

Les uploads et emails sont **loggés automatiquement** dans `storage/logs/`:

```
[14:32:15] [INFO] File uploaded: avatar_1704348735.jpg
[14:33:22] [INFO] Email sent to user@example.com - Bienvenue
[14:35:10] [ERROR] Email failed to invalid@example.com - Newsletter
```

Consulter les logs:

```php
use Core\Logger;

$logs = Logger::getLogs('2024-01-04');
$files = Logger::getLogFiles();
```

---

## Sécurité

### File Upload

- ✅ Vérification MIME type stricte
- ✅ Sanitization des noms de fichiers
- ✅ Protection contre inclusion de code
- ✅ Limite de taille configurable
- ✅ Répertoire uploads hors `public/`

### Email

- ✅ Validation format email
- ✅ Échappement HTML dans templates
- ✅ Headers injectés échappés
- ✅ Support SMTP sécurisé (TODO)
- ✅ Logging automatique

---

## Troubleshooting

### File upload "Permission denied"

```php
// Vérifier les permissions du dossier storage/uploads
chmod('storage/uploads', 0755);
```

### Email ne s'envoie pas

```php
// Vérifier logs
$logs = Logger::getLogs();

// Tester configuration
Email::test('test@example.com');

// Vérifier .env
Config::get('MAIL_FROM'); // doit avoir une valeur
```

### Upload timeout

```php
// Dans .env, augmenter la limite
UPLOAD_MAX_SIZE=52428800  // 50MB
```
