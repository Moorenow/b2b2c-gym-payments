<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @php($hasVite = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @if ($hasVite)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                :root {
                    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                    color: #e2e8f0;
                    background-color: #020617;
                }

                body {
                    margin: 0;
                    min-height: 100vh;
                    display: grid;
                    place-items: center;
                    background: radial-gradient(circle at top, rgba(59, 130, 246, 0.3), transparent 60%), #020617;
                }

                main {
                    text-align: center;
                    padding: 2rem;
                    max-width: 40rem;
                }

                .stack {
                    letter-spacing: 0.35em;
                    text-transform: uppercase;
                    color: #94a3b8;
                    font-size: 0.75rem;
                }
            </style>
        @endif
    </head>
    <body class="antialiased bg-slate-950 text-slate-50">
        @if ($hasVite)
            <noscript>
                Necesitas activar JavaScript para ver esta aplicación Vue.
            </noscript>
            <div id="app"></div>
        @else
            <main>
                <p class="stack">Laravel · Vue · Vite</p>
                <h1>Ejecuta <code>pnpm run dev</code> o <code>pnpm run build</code> para inicializar el frontend.</h1>
                <p>Mientras tanto, este placeholder evita errores en entornos como testing o CI.</p>
            </main>
        @endif
    </body>
</html>
