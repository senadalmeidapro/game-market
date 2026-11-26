<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('products')
            ->whereIn('type', ['photo', 'video'])
            ->update(['type' => 'game']);

        if (DB::getDriverName() === 'pgsql') {
            $this->dropProductTypeChecks();

            DB::statement("ALTER TABLE products ALTER COLUMN type SET DEFAULT 'game'");
            DB::statement("ALTER TABLE products ADD CONSTRAINT gamehub_products_type_check CHECK (type = 'game')");

            return;
        }

        Schema::table('products', function (Blueprint $table): void {
            $table->enum('type', ['game'])->default('game')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            $this->dropProductTypeChecks();

            DB::statement('ALTER TABLE products ALTER COLUMN type DROP DEFAULT');
            DB::statement("ALTER TABLE products ADD CONSTRAINT gamehub_products_type_check CHECK (type IN ('game', 'photo', 'video'))");

            return;
        }

        Schema::table('products', function (Blueprint $table): void {
            $table->enum('type', ['game', 'photo', 'video'])->default(null)->change();
        });
    }

    private function dropProductTypeChecks(): void
    {
        $constraints = DB::select("SELECT conname FROM pg_constraint WHERE conrelid = 'products'::regclass AND contype = 'c' AND pg_get_constraintdef(oid) ILIKE '%type%'");

        foreach ($constraints as $constraint) {
            $name = str_replace('"', '""', $constraint->conname);

            DB::statement('ALTER TABLE products DROP CONSTRAINT "'.$name.'"');
        }
    }
};
