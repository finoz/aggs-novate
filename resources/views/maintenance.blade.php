<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — In costruzione</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
            background: #f5f5f0;
            color: #1a1a1a;
        }

        .maintenance {
            text-align: center;
            padding: 2rem;
            max-width: 480px;
        }

        .maintenance__title {
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .maintenance__message {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="maintenance">
        <p class="maintenance__title">{{ config('app.name') }}</p>
        <p class="maintenance__message">Il sito è in costruzione.<br>Torna a visitarci presto.</p>
    </div>
</body>
</html>