<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->char('primary_color', 7)->nullable()->after('status');
            $table->char('secondary_color', 7)->nullable()->after('primary_color');
            $table->boolean('colors_are_manual')->default(false)->after('secondary_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['primary_color', 'secondary_color', 'colors_are_manual']);
        });
    }
};
