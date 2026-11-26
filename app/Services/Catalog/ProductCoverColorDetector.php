<?php

namespace App\Services\Catalog;

use GdImage;

class ProductCoverColorDetector
{
    /** @return array{primary_color: string, secondary_color: string}|null */
    public function detect(string $imagePath): ?array
    {
        if (! extension_loaded('gd') || ! is_file($imagePath)) {
            return null;
        }

        $contents = file_get_contents($imagePath);

        if ($contents === false) {
            return null;
        }

        $image = @imagecreatefromstring($contents);

        if (! $image instanceof GdImage) {
            return null;
        }

        $sample = null;

        try {
            $sample = $this->createSample($image);
            $bins = $this->buildColorBins($sample);

            if ($bins === []) {
                return null;
            }

            usort($bins, static fn (array $a, array $b): int => $b['count'] <=> $a['count']);
            $primary = $bins[0];
            $secondary = $this->chooseSecondary($primary, $bins);

            return [
                'primary_color' => $this->toHex($primary),
                'secondary_color' => $this->toHex($secondary),
            ];
        } finally {
            imagedestroy($image);

            if ($sample instanceof GdImage) {
                imagedestroy($sample);
            }
        }
    }

    private function createSample(GdImage $image): GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min(1, 64 / max($width, $height));
        $sample = imagecreatetruecolor(max(1, (int) round($width * $scale)), max(1, (int) round($height * $scale)));

        imagealphablending($sample, false);
        imagesavealpha($sample, true);
        imagecopyresampled($sample, $image, 0, 0, 0, 0, imagesx($sample), imagesy($sample), $width, $height);

        return $sample;
    }

    /** @return array<int, array{r: int, g: int, b: int, count: int}> */
    private function buildColorBins(GdImage $image): array
    {
        $bins = [];

        for ($y = 0; $y < imagesy($image); $y++) {
            for ($x = 0; $x < imagesx($image); $x++) {
                $pixel = imagecolorat($image, $x, $y);

                if ((($pixel >> 24) & 0x7F) > 100) {
                    continue;
                }

                $red = ($pixel >> 16) & 0xFF;
                $green = ($pixel >> 8) & 0xFF;
                $blue = $pixel & 0xFF;
                $key = sprintf('%x%x%x', $red >> 4, $green >> 4, $blue >> 4);
                $bins[$key] ??= ['r' => 0, 'g' => 0, 'b' => 0, 'count' => 0];
                $bins[$key]['r'] += $red;
                $bins[$key]['g'] += $green;
                $bins[$key]['b'] += $blue;
                $bins[$key]['count']++;
            }
        }

        foreach ($bins as &$bin) {
            $count = $bin['count'];
            $bin['r'] = (int) round($bin['r'] / $count);
            $bin['g'] = (int) round($bin['g'] / $count);
            $bin['b'] = (int) round($bin['b'] / $count);
        }
        unset($bin);

        return array_values($bins);
    }

    /** @param array{r: int, g: int, b: int, count: int} $primary
     *  @param array<int, array{r: int, g: int, b: int, count: int}> $bins
     *  @return array{r: int, g: int, b: int, count: int}
     */
    private function chooseSecondary(array $primary, array $bins): array
    {
        if (count($bins) === 1) {
            return $primary;
        }

        $candidates = array_slice($bins, 1);

        foreach ($candidates as $candidate) {
            if ($this->distance($primary, $candidate) >= 55) {
                return $candidate;
            }
        }

        return $candidates[0];
    }

    /** @param array{r: int, g: int, b: int} $a
     *  @param array{r: int, g: int, b: int} $b
     */
    private function distance(array $a, array $b): float
    {
        $redMean = ($a['r'] + $b['r']) / 2;
        $red = $a['r'] - $b['r'];
        $green = $a['g'] - $b['g'];
        $blue = $a['b'] - $b['b'];

        return sqrt((2 + $redMean / 256) * $red ** 2 + 4 * $green ** 2 + (2 + (255 - $redMean) / 256) * $blue ** 2);
    }

    /** @param array{r: int, g: int, b: int} $color */
    private function toHex(array $color): string
    {
        return sprintf('#%02X%02X%02X', $color['r'], $color['g'], $color['b']);
    }
}
