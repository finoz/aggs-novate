<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — In costruzione</title>
    @vite(['resources/scss/app.scss', 'resources/ts/app.ts'])
</head>
<body class="page-maintenance">
    <main class="sitemain">
        <figure class="splash-logo">
            <img src="{{ asset('images/splash-logo.svg') }}" alt="{{ config('app.name') }}">
        </figure>
        <section class="splash-content">
            <p>Qui il tuo gruppo scout preferito.<br>Il sito -come vedi- è in manutenzione: stiamo lavorando per voi.</p>
            <p>Nel frattempo: se hai bisogno di dare un'occhiata al calendario, 👉 <a href="{{ asset('attachments/temp-AGGS_calendario_v2.pdf') }}" target="_blank">eccolo qui!</a> 👈</p>
            <p>Per altre varie ed eventuali, scrivici!<br><a target="_blank" class="link-accent" href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a> </p>
            <p class="note">Vostrissimi,<br>capi e matusa</p>
        </section>
    </main>
</body>
</html>
