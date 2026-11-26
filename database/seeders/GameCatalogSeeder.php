<?php

namespace Database\Seeders;

use App\Models\Catalog\Category;
use App\Models\Catalog\Genre;
use App\Models\Catalog\Publisher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GameCatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->genres() as $genreName => $subgenres) {
            $genre = Genre::updateOrCreate(
                ['slug' => Str::slug($genreName)],
                ['name' => $genreName, 'parent_id' => null],
            );

            foreach ($subgenres as $subgenreName) {
                Genre::updateOrCreate(
                    ['slug' => Str::slug($subgenreName)],
                    ['name' => $subgenreName, 'parent_id' => $genre->id],
                );
            }
        }

        foreach ($this->categories() as $groupName => $categoryNames) {
            $group = Category::updateOrCreate(
                ['slug' => Str::slug($groupName)],
                ['name' => $groupName, 'parent_id' => null],
            );

            foreach ($categoryNames as $name) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name, 'parent_id' => $group->id],
                );
            }
        }

        foreach ($this->publishers() as $publisherData) {
            $publisher = Publisher::firstOrNew(['name' => $publisherData['name']]);

            if (blank($publisher->country)) {
                $publisher->country = $publisherData['country'];
            }

            if (blank($publisher->contact_email) && filled($publisherData['contact_email'])) {
                $publisher->contact_email = $publisherData['contact_email'];
            }

            if (blank($publisher->description)) {
                $publisher->description = $publisherData['description'];
            }

            if ($publisher->isDirty()) {
                $publisher->save();
            }
        }
    }

    /** @return array<string, list<string>> */
    private function genres(): array
    {
        return [
            'Action' => [
                'Action-aventure', 'Arcade', 'Beat’em up', 'Bullet hell', 'Combat',
                'Combat en arène', 'Hack and slash', 'Run and gun', 'Shoot’em up', 'Infiltration',
                'Jeu de tir', 'Jeu de tir à la première personne', 'Jeu de tir à la troisième personne',
                'Hero shooter', 'Extraction shooter', 'Tactical shooter', 'Battle royale',
            ],
            'Aventure' => [
                'Jeu narratif', 'Aventure graphique', 'Jeu d’enquête', 'Jeu de détective',
                'Visual novel', 'Roman interactif', 'Jeu de drague',
            ],
            'Jeu de plateforme' => ['Metroidvania'],
            'RPG' => [
                'Action-RPG', 'Aventure-RPG', 'RPG japonais', 'RPG tactique', 'RPG occidental',
                'MMORPG', 'Jeu de rôle en ligne', 'Dungeon crawler', 'Soulslike',
            ],
            'Roguelike' => ['Roguelite'],
            'Survie' => ['Horreur', 'Horreur et survie'],
            'Simulation' => [
                'Simulation de vie', 'Simulation de ferme', 'Simulation de construction',
                'Simulation de gestion', 'Simulation de véhicule', 'Simulation de vol',
                'Simulation spatiale', 'Simulation de sport', 'Jeu de chasse', 'Jeu de pêche',
            ],
            'Jeu de gestion' => ['City-builder'],
            'Jeu de construction' => [],
            'Jeu de bac à sable' => [],
            'Stratégie' => [
                'Stratégie en temps réel', 'Stratégie au tour par tour', 'Stratégie 4X',
                'Grande stratégie', 'Tactique en temps réel', 'Tactique au tour par tour',
                'Tower defense', 'MOBA',
            ],
            'Jeu de puzzle' => ['Jeu de réflexion', 'Match-3', 'Objets cachés', 'Jeu de mots'],
            'Jeu de cartes' => ['Deck-building', 'Jeu de cartes à collectionner'],
            'Jeu de plateau' => ['Jeu de société'],
            'Jeu de rythme' => ['Jeu musical'],
            'Jeu de course' => [
                'Course arcade', 'Course de kart', 'Course de simulation',
                'Jeu de course automobile', 'Jeu de course de moto',
            ],
            'Sport' => [
                'Football', 'Basketball', 'Sports de combat', 'Sports extrêmes',
                'Jeu de golf', 'Jeu de tennis',
            ],
            'Party game' => ['Quiz et trivia'],
            'Jeu éducatif' => [],
            'Jeu idle' => ['Jeu incrémental'],
        ];
    }

    /** @return array<string, list<string>> */
    private function categories(): array
    {
        return [
            'Modes de jeu' => [
                'Solo', 'Multijoueur', 'Multijoueur en ligne', 'Multijoueur local',
                'Coopération', 'Coopération en ligne', 'Coopération locale',
                'Joueur contre joueur (PvP)', 'Joueur contre environnement (PvE)',
                'Massivement multijoueur', 'Écran partagé', 'Jeu multiplateforme', 'Asynchrone',
            ],
            'Thèmes et univers' => [
                'Fantasy', 'Dark fantasy', 'Science-fiction', 'Cyberpunk', 'Post-apocalyptique',
                'Dystopie', 'Historique', 'Antiquité', 'Médiéval', 'Mythologie', 'Western',
                'Militaire', 'Première Guerre mondiale', 'Seconde Guerre mondiale',
                'Guerre moderne', 'Espionnage', 'Crime', 'Enquête', 'Mystère', 'Horreur',
                'Surnaturel', 'Zombies', 'Vampires', 'Lovecraftien', 'Espace', 'Extraterrestres',
                'Robots', 'Dinosaures', 'Nature', 'Sous-marin', 'Pirates', 'Samouraïs',
                'Ninjas', 'Super-héros', 'Anime', 'École', 'Romance', 'Familial', 'Urbain',
                'Animaux', 'Cuisine', 'Musique', 'Sports',
            ],
            'Expérience de jeu' => [
                'Monde ouvert', 'Monde semi-ouvert', 'Niveaux linéaires', 'Bac à sable',
                'Axé sur le récit', 'Choix et conséquences', 'Exploration', 'Artisanat',
                'Construction de base', 'Génération procédurale', 'Mort permanente', 'Difficile',
                'Détente', 'Compétitif', 'Casual', 'En accès anticipé', 'Free-to-play',
                'Indépendant', 'Jeu de courte durée', 'Rejouabilité élevée',
            ],
            'Perspective et style visuel' => [
                'Vue à la première personne', 'Vue à la troisième personne', 'Vue isométrique',
                'Vue de dessus', 'Défilement horizontal', '2D', '3D', 'Pixel art', 'Rétro',
                'Graphismes réalistes', 'Style stylisé', 'Dessiné à la main', 'Low-poly',
                'Voxel', 'Anime et manga', 'Coloré', 'Sombre et atmosphérique',
            ],
            'Public et accessibilité' => [
                'Tout public', 'Familial', 'Pour adultes', 'Prise en charge de la manette',
                'Commandes personnalisables', 'Sous-titres', 'Accessibilité renforcée',
            ],
        ];
    }

    /** @return list<array{name: string, country: string, contact_email: ?string, description: string}> */
    private function publishers(): array
    {
        return [
            ['name' => '2K', 'country' => 'US', 'contact_email' => 'pr@2k.com', 'description' => 'Éditeur américain de jeux vidéo, connu notamment pour NBA 2K, BioShock, Borderlands, Mafia et Civilization.'],
            ['name' => '505 Games', 'country' => 'IT', 'contact_email' => 'info@505games.com', 'description' => 'Éditeur italien de jeux sur consoles, PC et plateformes mobiles, avec un catalogue de productions internationales.'],
            ['name' => 'Activision', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur américain connu pour Call of Duty, Crash Bandicoot, Spyro et plusieurs franchises d’action.'],
            ['name' => 'Amazon Games', 'country' => 'US', 'contact_email' => null, 'description' => 'Branche jeux vidéo d’Amazon, qui développe et publie des jeux pour PC et consoles.'],
            ['name' => 'Annapurna Interactive', 'country' => 'US', 'contact_email' => 'contact@annapurnainteractive.com', 'description' => 'Éditeur indépendant américain qui accompagne des jeux créatifs et narratifs, dont Stray, Outer Wilds et What Remains of Edith Finch.'],
            ['name' => 'Atari', 'country' => 'FR', 'contact_email' => null, 'description' => 'Éditeur franco-américain et marque historique du jeu vidéo, actif dans les jeux rétro, les licences classiques et de nouveaux projets.'],
            ['name' => 'Bandai Namco Entertainment', 'country' => 'JP', 'contact_email' => 'pr@bnea.com', 'description' => 'Éditeur japonais de jeux et de licences de divertissement, connu pour PAC-MAN, Tekken, Ace Combat et Elden Ring.'],
            ['name' => 'Bethesda Softworks', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur américain de jeux de rôle et d’action, associé notamment aux séries The Elder Scrolls, Fallout et DOOM.'],
            ['name' => 'Capcom', 'country' => 'JP', 'contact_email' => null, 'description' => 'Éditeur japonais de jeux d’action et d’arcade, connu pour Resident Evil, Monster Hunter, Street Fighter et Mega Man.'],
            ['name' => 'CD Projekt', 'country' => 'PL', 'contact_email' => null, 'description' => 'Entreprise polonaise de jeux vidéo, éditrice des franchises The Witcher et Cyberpunk.'],
            ['name' => 'Blizzard Entertainment', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur américain de jeux en ligne et de franchises comme Warcraft, Diablo, Overwatch et StarCraft.'],
            ['name' => 'Bungie', 'country' => 'US', 'contact_email' => null, 'description' => 'Studio et éditeur américain connu pour les séries Destiny et Halo.'],
            ['name' => 'Devolver Digital', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur indépendant américain publiant des jeux de studios indépendants aux styles variés.'],
            ['name' => 'Disney', 'country' => 'US', 'contact_email' => null, 'description' => 'Groupe américain de divertissement qui édite ou concède des licences de jeux inspirés de ses univers.'],
            ['name' => 'Dotemu', 'country' => 'FR', 'contact_email' => null, 'description' => 'Éditeur et développeur français spécialisé dans les jeux indépendants et les retours de classiques, dont Streets of Rage 4.'],
            ['name' => 'Electronic Arts', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur américain de jeux de sport, de simulation et d’action, avec EA SPORTS FC, The Sims, Battlefield et Apex Legends.'],
            ['name' => 'Embracer Group', 'country' => 'SE', 'contact_email' => null, 'description' => 'Groupe suédois de jeux et de divertissement qui réunit plusieurs studios et maisons d’édition.'],
            ['name' => 'Epic Games', 'country' => 'US', 'contact_email' => null, 'description' => 'Entreprise américaine de jeux et de technologies, connue pour Fortnite, Unreal Engine et Epic Games Store.'],
            ['name' => 'Focus Entertainment', 'country' => 'FR', 'contact_email' => null, 'description' => 'Éditeur français de jeux sur PC et consoles, notamment de productions d’action, de stratégie et de simulation.'],
            ['name' => 'Gearbox Publishing', 'country' => 'US', 'contact_email' => null, 'description' => 'Maison d’édition américaine associée à Gearbox et à des franchises comme Borderlands.'],
            ['name' => 'Humble Games', 'country' => 'US', 'contact_email' => null, 'description' => 'Label américain d’édition de jeux indépendants, lié à la marque Humble.'],
            ['name' => 'Kalypso Media', 'country' => 'DE', 'contact_email' => null, 'description' => 'Éditeur allemand spécialisé dans les jeux de stratégie, de gestion et de simulation, dont Tropico.'],
            ['name' => 'Koei Tecmo', 'country' => 'JP', 'contact_email' => null, 'description' => 'Éditeur japonais connu pour Dynasty Warriors, Dead or Alive, Nioh et Atelier.'],
            ['name' => 'Konami', 'country' => 'JP', 'contact_email' => null, 'description' => 'Éditeur japonais de jeux et de divertissement, connu pour Metal Gear, Castlevania, Silent Hill et eFootball.'],
            ['name' => 'Microids', 'country' => 'FR', 'contact_email' => null, 'description' => 'Éditeur français de jeux d’aventure, de licences et de productions familiales.'],
            ['name' => 'Mojang Studios', 'country' => 'SE', 'contact_email' => null, 'description' => 'Studio suédois derrière Minecraft, qui développe et publie des jeux liés à cet univers.'],
            ['name' => 'Nacon', 'country' => 'FR', 'contact_email' => null, 'description' => 'Entreprise française de jeux et d’accessoires gaming, éditrice de jeux de sport, d’aventure et de simulation.'],
            ['name' => 'NetEase Games', 'country' => 'CN', 'contact_email' => null, 'description' => 'Éditeur chinois de jeux mobiles, PC et en ligne, actif à l’international.'],
            ['name' => 'Netflix Games', 'country' => 'US', 'contact_email' => null, 'description' => 'Offre de jeux vidéo de Netflix, comprenant des jeux mobiles et des productions associées à son catalogue.'],
            ['name' => 'Nintendo', 'country' => 'JP', 'contact_email' => null, 'description' => 'Éditeur et constructeur japonais, créateur de franchises comme Mario, The Legend of Zelda, Pokémon et Animal Crossing.'],
            ['name' => 'Paradox Interactive', 'country' => 'SE', 'contact_email' => 'pr@paradoxinteractive.com', 'description' => 'Éditeur suédois spécialisé dans la stratégie et la simulation, avec Crusader Kings, Europa Universalis, Hearts of Iron et Stellaris.'],
            ['name' => 'PlayStation Studios', 'country' => 'US', 'contact_email' => null, 'description' => 'Label de Sony Interactive Entertainment regroupant des studios et des jeux destinés aux plateformes PlayStation et au PC.'],
            ['name' => 'Raw Fury', 'country' => 'SE', 'contact_email' => null, 'description' => 'Éditeur suédois indépendant accompagnant des jeux originaux de studios du monde entier.'],
            ['name' => 'Riot Games', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur américain de jeux compétitifs et en ligne, connu pour League of Legends, VALORANT et Teamfight Tactics.'],
            ['name' => 'Rockstar Games', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur américain de jeux d’action et de monde ouvert, notamment Grand Theft Auto, Red Dead Redemption et Max Payne.'],
            ['name' => 'Saber Interactive', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur et développeur de jeux multiplateformes, dont World War Z et plusieurs adaptations de licences.'],
            ['name' => 'SEGA', 'country' => 'JP', 'contact_email' => null, 'description' => 'Éditeur japonais de jeux d’action, de rôle et de stratégie, connu pour Sonic, Like a Dragon et Total War.'],
            ['name' => 'Square Enix', 'country' => 'JP', 'contact_email' => null, 'description' => 'Éditeur japonais de jeux de rôle et d’action, avec Final Fantasy, Dragon Quest et Kingdom Hearts.'],
            ['name' => 'Take-Two Interactive', 'country' => 'US', 'contact_email' => null, 'description' => 'Groupe américain de jeux vidéo qui édite des titres par l’intermédiaire de labels comme 2K et Rockstar Games.'],
            ['name' => 'Sony Interactive Entertainment', 'country' => 'US', 'contact_email' => null, 'description' => 'Entreprise de jeux vidéo de Sony, responsable des consoles PlayStation, de ses services et de l’édition de jeux.'],
            ['name' => 'Team17', 'country' => 'GB', 'contact_email' => null, 'description' => 'Éditeur britannique de jeux indépendants et créateur de la série Worms.'],
            ['name' => 'Tencent Games', 'country' => 'CN', 'contact_email' => null, 'description' => 'Branche jeux vidéo du groupe chinois Tencent, investissant et publiant des jeux sur plusieurs marchés.'],
            ['name' => 'THQ Nordic', 'country' => 'AT', 'contact_email' => null, 'description' => 'Éditeur autrichien de jeux sur PC et consoles, avec un catalogue de franchises établies et de nouvelles productions.'],
            ['name' => 'Ubisoft', 'country' => 'FR', 'contact_email' => 'CorporatePRTeam@ubisoft.com', 'description' => 'Éditeur français de jeux d’aventure et d’action, connu pour Assassin’s Creed, Far Cry, Just Dance et Rainbow Six.'],
            ['name' => 'Valve', 'country' => 'US', 'contact_email' => null, 'description' => 'Entreprise américaine de jeux et de distribution numérique, créatrice de Steam et de jeux comme Half-Life, Portal et Dota 2.'],
            ['name' => 'Warner Bros. Games', 'country' => 'US', 'contact_email' => null, 'description' => 'Éditeur américain de jeux inspirés notamment des univers Warner Bros., DC, Harry Potter et Mortal Kombat.'],
            ['name' => 'Xbox Game Studios', 'country' => 'US', 'contact_email' => null, 'description' => 'Label de Microsoft qui édite des jeux pour Xbox, Windows et d’autres plateformes, dont Halo, Forza et Age of Empires.'],
        ];
    }
}
