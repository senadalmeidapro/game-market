<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Some existing databases already have Spatie's media table outside the
        // Laravel migration history. Keep those databases compatible with migrate.
        if (Schema::hasTable('media')) {
            $requiredColumns = [
                'id', 'model_type', 'model_id', 'uuid', 'collection_name', 'name', 'file_name',
                'mime_type', 'disk', 'conversions_disk', 'size', 'manipulations',
                'custom_properties', 'generated_conversions', 'responsive_images', 'order_column',
                'created_at', 'updated_at',
            ];

            if (! Schema::hasColumns('media', $requiredColumns)) {
                throw new RuntimeException('The existing media table does not match Spatie Media Library.');
            }

            return;
        }

        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->morphs('model');
            $table->uuid()->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();

            $table->nullableTimestamps();
        });
    }
};
