<?php

namespace App\Filament\Resources\Catalog\Products\Schemas;

use App\Enums\Catalog\CoverFormat;
use App\Enums\Catalog\ProductFileKind;
use App\Enums\Catalog\ProductFileStatus;
use App\Enums\Catalog\ProductVersionStatus;
use App\Enums\Catalog\RequirementTier;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProductCreateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...ProductForm::basicComponents(),
                Tabs::make('Jeu')
                    ->extraAttributes(['class' => 'gamehub-centered-tabs'])
                    ->tabs([
                        Tab::make('Jaquettes')
                            ->schema([
                                Repeater::make('cover_variants')
                                    ->label('Formats derives de la jaquette principale')
                                    ->schema([
                                        Select::make('format')
                                            ->label('Format de jaquette')
                                            ->options(CoverFormat::variantOptions())
                                            ->live()
                                            ->required(),
                                        FileUpload::make('file')
                                            ->label('Image de jaquette derivee')
                                            ->image()
                                            ->acceptedFileTypes(fn (Get $get): array => CoverFormat::tryFrom((string) $get('format'))?->acceptedMimeTypes() ?? ['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                                            ->rules(fn (Get $get): array => CoverFormat::tryFrom((string) $get('format'))?->validationRules() ?? [])
                                            ->validationMessages(['dimensions' => 'L’image ne respecte pas les dimensions du format selectionne.'])
                                            ->disk('local')
                                            ->directory('product-media/cover-variants')
                                            ->visibility('private')
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->collapsed()
                                    ->addActionLabel('Ajouter un format derive')
                                    ->dehydrated(false)
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('A propos')
                            ->schema(ProductForm::aboutComponents())
                            ->columns(2),
                        Tab::make('Configurations')
                            ->schema([
                                Repeater::make('systemRequirements')
                                    ->label('Configurations requises')
                                    ->relationship()
                                    ->schema([
                                        Select::make('platform_id')
                                            ->label('Systeme')
                                            ->relationship('platform', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Select::make('tier')
                                            ->label('Niveau')
                                            ->options(RequirementTier::class)
                                            ->required(),
                                        TextInput::make('os_version')
                                            ->label('Version du systeme'),
                                        TextInput::make('cpu')
                                            ->label('Processeur'),
                                        TextInput::make('ram')
                                            ->label('Memoire vive'),
                                        TextInput::make('gpu')
                                            ->label('Carte graphique'),
                                        TextInput::make('storage')
                                            ->label('Espace disque'),
                                    ])
                                    ->columns(2)
                                    ->collapsed()
                                    ->addActionLabel('Ajouter une configuration')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Medias')
                            ->schema([
                                Repeater::make('media_uploads')
                                    ->label('Medias du jeu')
                                    ->schema([
                                        Select::make('collection_name')
                                            ->label('Type de media')
                                            ->options([
                                                'trailers' => 'Trailer',
                                                'gameplay' => 'Gameplay',
                                                'short_plays' => 'Short play',
                                                'screenshots' => 'Capture d’ecran',
                                            ])
                                            ->live()
                                            ->required(),
                                        TextInput::make('name')
                                            ->label('Nom affiche')
                                            ->required(),
                                        FileUpload::make('file')
                                            ->label('Fichier media')
                                            ->disk('local')
                                            ->directory('product-media')
                                            ->visibility('private')
                                            ->acceptedFileTypes(fn (Get $get): array => $get('collection_name') === 'screenshots'
                                                ? ['image/jpeg', 'image/png', 'image/webp', 'image/avif']
                                                : ['video/mp4', 'video/webm', 'video/quicktime'])
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->collapsed()
                                    ->addActionLabel('Ajouter un media')
                                    ->dehydrated(false)
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Fichiers')
                            ->schema([
                                Repeater::make('versions')
                                    ->label('Versions du jeu')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('version_number')
                                            ->label('Numero de version')
                                            ->required(),
                                        Select::make('status')
                                            ->label('Statut')
                                            ->options(ProductVersionStatus::class)
                                            ->default('draft')
                                            ->required(),
                                        DateTimePicker::make('released_at')
                                            ->label('Date de sortie'),
                                        Toggle::make('is_latest')
                                            ->label('Version actuelle')
                                            ->default(false),
                                        RichEditor::make('release_notes')
                                            ->label('Notes de version')
                                            ->columnSpanFull(),
                                        Repeater::make('files')
                                            ->label('Fichiers de cette version')
                                            ->relationship()
                                            ->schema([
                                                FileUpload::make('storage_key')
                                                    ->label('Fichier du jeu')
                                                    ->disk('local')
                                                    ->directory('game-builds')
                                                    ->visibility('private')
                                                    ->storeFileNamesIn('filename')
                                                    ->required(),
                                                Select::make('platform_id')
                                                    ->label('Systeme')
                                                    ->relationship('platform', 'name')
                                                    ->searchable()
                                                    ->preload(),
                                                Select::make('kind')
                                                    ->label('Type de fichier')
                                                    ->options(ProductFileKind::class)
                                                    ->required(),
                                                Select::make('status')
                                                    ->label('Statut du fichier')
                                                    ->options(ProductFileStatus::class)
                                                    ->default(ProductFileStatus::Uploaded->value)
                                                    ->required(),
                                                TextInput::make('sha256')
                                                    ->label('Empreinte SHA-256')
                                                    ->maxLength(64),
                                            ])
                                            ->columns(2)
                                            ->collapsed()
                                            ->addActionLabel('Ajouter un fichier')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->collapsed()
                                    ->addActionLabel('Ajouter une version')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
