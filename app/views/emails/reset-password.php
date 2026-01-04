<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser votre mot de passe</title>
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

        .warning {
            background: #fff3cd;
            padding: 10px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Réinitialiser votre mot de passe</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>

            <p>Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour continuer:</p>

            <center>
                <a href="<?php echo htmlspecialchars($resetLink ?? '#'); ?>" class="button">Réinitialiser mon mot de passe</a>
            </center>

            <p>Ou copiez ce lien dans votre navigateur:</p>
            <p style="word-break: break-all; background: #f5f5f5; padding: 10px;">
                <?php echo htmlspecialchars($resetLink ?? '#'); ?>
            </p>

            <div class="warning">
                <strong>⚠️ Important:</strong> Ce lien expire dans <?php echo $expiresIn ?? '24 heures'; ?>.
            </div>

            <p>Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet email.</p>

            <p>Cordialement,<br>L'équipe BondoMVC</p>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Veuillez ne pas y répondre.</p>
        </div>
    </div>
</body>

</html>