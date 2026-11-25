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
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_email')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('contract_status')->default('unsigned')->index();
            $table->string('contract_storage_key')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 16)->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('price_amount');
            $table->string('status', 16)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'product_id']);
        });

        Schema::create('genre_product', function (Blueprint $table) {
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['genre_id', 'product_id']);
        });

        Schema::create('product_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('version_number', 64);
            $table->text('release_notes')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->boolean('is_latest')->default(false);
            $table->timestamps();
            $table->unique(['product_id', 'version_number']);
            $table->index(['product_id', 'is_latest']);
        });

        Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_version_id')->constrained()->restrictOnDelete();
            $table->foreignId('platform_id')->nullable()->constrained()->nullOnDelete();
            $table->string('kind', 16);
            $table->string('filename');
            $table->string('storage_key')->unique();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->char('sha256', 64)->nullable();
            $table->string('status', 16)->default('uploading')->index();
            $table->timestamps();
            $table->index(['product_version_id', 'platform_id']);
        });

        Schema::create('system_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('platform_id')->constrained()->restrictOnDelete();
            $table->string('tier', 16);
            $table->string('os')->nullable();
            $table->string('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('gpu')->nullable();
            $table->string('storage')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'platform_id', 'tier']);
        });

        Schema::create('product_price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('old_price_amount');
            $table->unsignedBigInteger('new_price_amount');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('changed_at')->useCurrent();
            $table->index(['product_id', 'changed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_histories');
        Schema::dropIfExists('system_requirements');
        Schema::dropIfExists('product_files');
        Schema::dropIfExists('product_versions');
        Schema::dropIfExists('genre_product');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('products');
        Schema::dropIfExists('platforms');
        Schema::dropIfExists('genres');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('publishers');
    }
};
