<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publishers', function (Blueprint $table): void {
            $table->dropIndex(['contract_status']);
            $table->dropColumn(['contract_status', 'contract_storage_key']);
        });
    }

    public function down(): void
    {
        Schema::table('publishers', function (Blueprint $table): void {
            $table->enum('contract_status', ['unsigned', 'signed'])->default('unsigned')->index();
            $table->string('contract_storage_key')->nullable();
        });
    }
};
