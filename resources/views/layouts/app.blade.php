<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        <header class="border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <a href="{{ route('home') }}" wire:navigate class="font-black tracking-[0.2em] text-amber-500">GAMEHUB</a>

                @auth
                    <div class="flex items-center gap-4 text-sm">
                        <a href="{{ route('profile.edit') }}" wire:navigate class="text-zinc-600 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Mon compte</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-zinc-600 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Déconnexion</button>
                        </form>
                    </div>
                @endauth
            </div>
        </header>

        <main>{{ $slot }}</main>

        @livewire('notifications')

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
