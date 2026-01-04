<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background: white;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }

        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Bienvenue sur BondoMVC!</h1>
        </div>
        <div class="content">
            <h2>Bonjour <?php echo htmlspecialchars($name ?? 'Utilisateur'); ?>!</h2>

            <p>Merci de vous être inscrit sur notre plateforme. Nous sommes ravi de vous accueillir!</p>

            <p>Vous pouvez maintenant accéder à votre compte et commencer à explorer toutes les fonctionnalités disponibles.</p>

            <div style="text-align: center;">
                <a href="<?php echo Config::getAppUrl(); ?>/dashboard" class="button">Accéder à mon compte</a>
            </div>

            <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>

            <p>Cordialement,<br>L'équipe BondoMVC</p>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Veuillez ne pas y répondre.</p>
        </div>
    </div>
</body>

</html>