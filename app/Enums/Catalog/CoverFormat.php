<?php

namespace App\Enums\Catalog;

enum CoverFormat: string
{
    case LibraryCapsule = 'library_capsule';
    case HeaderCapsule = 'header_capsule';
    case SmallCapsule = 'small_capsule';
    case MainCapsule = 'main_capsule';
    case VerticalCapsule = 'vertical_capsule';
    case LibraryHero = 'library_hero';

    public function label(): string
    {
        return match ($this) {
            self::LibraryCapsule => 'Jaquette principale - Library Capsule (600 x 900 min.)',
            self::HeaderCapsule => 'Header Capsule (920 x 430 min.)',
            self::SmallCapsule => 'Small Capsule (462 x 174 min.)',
            self::MainCapsule => 'Main Capsule (1232 x 706 min.)',
            self::VerticalCapsule => 'Vertical Capsule (748 x 896 min.)',
            self::LibraryHero => 'Library Hero (3840 x 1240 min.)',
        };
    }

    public function minWidth(): int
    {
        return match ($this) {
            self::LibraryCapsule => 600,
            self::HeaderCapsule => 920,
            self::SmallCapsule => 462,
            self::MainCapsule => 1232,
            self::VerticalCapsule => 748,
            self::LibraryHero => 3840,
        };
    }

    public function minHeight(): int
    {
        return match ($this) {
            self::LibraryCapsule => 900,
            self::HeaderCapsule => 430,
            self::SmallCapsule => 174,
            self::MainCapsule => 706,
            self::VerticalCapsule => 896,
            self::LibraryHero => 1240,
        };
    }

    public function ratio(): string
    {
        return match ($this) {
            self::LibraryCapsule => '2/3',
            self::HeaderCapsule => '92/43',
            self::SmallCapsule => '77/29',
            self::MainCapsule => '616/353',
            self::VerticalCapsule => '187/224',
            self::LibraryHero => '96/31',
        };
    }

    /** @return array<string> */
    public function acceptedMimeTypes(): array
    {
        return $this === self::LibraryHero
            ? ['image/png']
            : ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
    }

    /** @return array<int, string> */
    public function validationRules(): array
    {
        return [
            sprintf(
                'dimensions:min_width=%d,min_height=%d,ratio=%s',
                $this->minWidth(),
                $this->minHeight(),
                $this->ratio(),
            ),
        ];
    }

    /** @return array<string, string> */
    public static function variantOptions(): array
    {
        $options = [];

        foreach (self::cases() as $format) {
            if ($format === self::LibraryCapsule) {
                continue;
            }

            $options[$format->value] = $format->label();
        }

        return $options;
    }
}
