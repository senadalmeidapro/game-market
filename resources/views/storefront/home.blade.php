<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>GAMEHUB</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased">
        <header class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6">
            <a href="{{ route('home') }}" class="text-xl font-black tracking-[0.2em] text-amber-400">GAMEHUB</a>

            <nav class="flex items-center gap-5 text-sm">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-zinc-300 hover:text-white">Mon compte</a>
                @else
                    <a href="{{ route('login') }}" class="text-zinc-300 hover:text-white">Connexion</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-amber-400 px-4 py-2 font-semibold text-zinc-950 hover:bg-amber-300">Créer un compte</a>
                @endauth
            </nav>
        </header>

        <main class="mx-auto flex min-h-[70vh] max-w-6xl flex-col justify-center px-6 py-20">
            <p class="mb-5 text-sm font-semibold uppercase tracking-[0.28em] text-amber-400">La boutique numérique</p>
            <h1 class="max-w-3xl text-5xl font-black leading-tight tracking-tight sm:text-7xl">Vos prochaines aventures commencent ici.</h1>
            <p class="mt-7 max-w-xl text-lg leading-8 text-zinc-400">GAMEHUB prépare son catalogue de jeux, photos et vidéos à télécharger. La boutique arrive bientôt.</p>
        </main>
    </body>
</html>
