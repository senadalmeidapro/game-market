<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->rebuildPivot('category_product', 'category_id', 'categories', 'restrict');
            $this->rebuildPivot('genre_product', 'genre_id', 'genres', 'restrict');

            return;
        }

        Schema::table('category_product', function (Blueprint $table) {
            $table->dropForeign('category_product_category_id_foreign');
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
        });

        Schema::table('genre_product', function (Blueprint $table) {
            $table->dropForeign('genre_product_genre_id_foreign');
            $table->foreign('genre_id')->references('id')->on('genres')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->rebuildPivot('genre_product', 'genre_id', 'genres', 'cascade');
            $this->rebuildPivot('category_product', 'category_id', 'categories', 'cascade');

            return;
        }

        Schema::table('genre_product', function (Blueprint $table) {
            $table->dropForeign(['genre_id']);
            $table->foreign('genre_id')->references('id')->on('genres')->cascadeOnDelete();
        });

        Schema::table('category_product', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });
    }

    private function rebuildPivot(string $tableName, string $referenceColumn, string $referenceTable, string $deleteRule): void
    {
        $temporaryTable = $tableName.'_replacement';
        Schema::dropIfExists($temporaryTable);

        Schema::create($temporaryTable, function (Blueprint $table) use ($referenceColumn, $referenceTable, $deleteRule): void {
            $reference = $table->foreignId($referenceColumn)->constrained($referenceTable);
            if ($deleteRule === 'restrict') {
                $reference->restrictOnDelete();
            } else {
                $reference->cascadeOnDelete();
            }
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary([$referenceColumn, 'product_id']);
        });

        DB::table($temporaryTable)->insertUsing(
            [$referenceColumn, 'product_id'],
            DB::table($tableName)->select($referenceColumn, 'product_id'),
        );

        Schema::drop($tableName);
        Schema::rename($temporaryTable, $tableName);
    }
};
