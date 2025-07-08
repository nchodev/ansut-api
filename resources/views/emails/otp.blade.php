<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Code de vérification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .panel {
            background: #f0f0f0;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            border-radius: 6px;
            color: #1a202c;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Code de vérification</h2>

        <p>Bonjour{{ isset($username) ? ' ' . $username : '' }},</p>

        <p>Votre code de vérification est :</p>

        <div class="panel">{{ $otp }}</div>

        <p>Ce code est valide pendant <strong>5 minutes</strong>.</p>

        <p>Si vous n’avez pas demandé ce code, vous pouvez ignorer cet email.</p>

        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
