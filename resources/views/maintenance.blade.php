<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — In costruzione</title>
    @vite(['resources/scss/app.scss', 'resources/ts/app.ts'])
</head>
<body>
    <section class="maintenance">
        <p>
            {{ config('app.name') }}
        </p>
        <p>
            Il sito è in costruzione.<br>Torna a visitarci presto.
        </p>
    </section>
</body>
</html>
