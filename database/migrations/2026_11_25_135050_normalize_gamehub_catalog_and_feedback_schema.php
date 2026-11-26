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
        if (Schema::hasColumn('system_requirements', 'os')) {
            Schema::table('system_requirements', function (Blueprint $table): void {
                $table->renameColumn('os', 'os_version');
            });
        } elseif (! Schema::hasColumn('system_requirements', 'os_version')) {
            Schema::table('system_requirements', function (Blueprint $table): void {
                $table->string('os_version')->nullable();
            });
        }

        if (Schema::hasColumn('system_requirements', 'req')) {
            DB::statement('ALTER TABLE system_requirements DROP COLUMN req');
        }

        $this->normalizePlatformCodes();

        Schema::create('product_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('body')->nullable();
            $table->enum('status', ['pending', 'published', 'hidden'])->default('pending')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'user_id']);
            $table->index(['product_id', 'status', 'published_at']);
        });

        Schema::create('product_review_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('parent_comment_id')->nullable();
            $table->longText('body');
            $table->enum('status', ['pending', 'published', 'hidden'])->default('pending')->index();
            $table->timestamps();
            $table->unique(['id', 'product_review_id']);
            $table->foreign(['parent_comment_id', 'product_review_id'])
                ->references(['id', 'product_review_id'])
                ->on('product_review_comments')
                ->cascadeOnDelete();
            $table->index(['product_review_id', 'parent_comment_id', 'created_at']);
        });

        $this->addEnumCheck('products', 'type', ['game', 'photo', 'video']);
        $this->addEnumCheck('products', 'status', ['draft', 'published', 'unpublished', 'archived']);
        $this->addEnumCheck('publishers', 'contract_status', ['unsigned', 'signed']);
        $this->addEnumCheck('platforms', 'code', ['windows', 'linux', 'other']);
        $this->addEnumCheck('product_versions', 'status', ['draft', 'processing', 'distributable', 'withdrawn']);
        $this->addEnumCheck('product_files', 'kind', ['build', 'original', 'preview']);
        $this->addEnumCheck('product_files', 'status', ['uploading', 'uploaded', 'verified', 'failed']);
        $this->addEnumCheck('system_requirements', 'tier', ['minimum', 'recommended']);
        $this->addEnumCheck('users', 'status', ['active', 'suspended']);
        $this->addEnumCheck('promotions', 'type', ['percentage', 'fixed_amount']);
        $this->addEnumCheck('orders', 'status', ['pending', 'paid', 'expired', 'cancelled', 'refunded']);
        $this->addEnumCheck('orders', 'currency', ['XOF']);
        $this->addEnumCheck('payments', 'method', ['mobile_money', 'card']);
        $this->addEnumCheck('payments', 'status', ['initiated', 'pending', 'succeeded', 'failed', 'expired', 'flagged']);
        $this->addEnumCheck('payments', 'currency', ['XOF']);
        $this->addEnumCheck('refunds', 'status', ['requested', 'approved', 'manual_payout_pending', 'completed', 'rejected']);
        $this->addEnumCheck('ledger_entries', 'type', ['sale', 'refund', 'adjustment']);
        $this->addEnumCheck('ledger_entries', 'currency', ['XOF']);
        $this->addEnumCheck('licences', 'status', ['active', 'revoked']);
        $this->addEnumCheck('support_tickets', 'category', ['general', 'technical', 'refund']);
        $this->addEnumCheck('support_tickets', 'status', ['open', 'pending', 'resolved']);

        $this->addCheck('product_reviews', 'rating', '"rating" BETWEEN 1 AND 5');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_review_comments');
        Schema::dropIfExists('product_reviews');

        if (DB::getDriverName() === 'pgsql') {
            foreach ([
                'products' => ['type', 'status'],
                'publishers' => ['contract_status'],
                'platforms' => ['code'],
                'product_versions' => ['status'],
                'product_files' => ['kind', 'status'],
                'system_requirements' => ['tier'],
                'users' => ['status'],
                'promotions' => ['type'],
                'orders' => ['status', 'currency'],
                'payments' => ['method', 'status', 'currency'],
                'refunds' => ['status'],
                'ledger_entries' => ['type', 'currency'],
                'licences' => ['status'],
                'support_tickets' => ['category', 'status'],
            ] as $table => $columns) {
                foreach ($columns as $column) {
                    $constraint = $this->constraintName($table, $column);

                    if ($this->constraintExists($constraint)) {
                        DB::statement(sprintf('ALTER TABLE "%s" DROP CONSTRAINT "%s"', $table, $constraint));
                    }
                }
            }

            if ($this->constraintExists('gamehub_product_reviews_rating_check')) {
                DB::statement('ALTER TABLE product_reviews DROP CONSTRAINT gamehub_product_reviews_rating_check');
            }
        }

        if (Schema::hasColumn('system_requirements', 'os_version')) {
            Schema::table('system_requirements', function (Blueprint $table): void {
                $table->renameColumn('os_version', 'os');
            });
        }
    }

    private function normalizePlatformCodes(): void
    {
        $oldMacOsId = DB::table('platforms')->where('code', 'macos')->value('id');
        $otherId = DB::table('platforms')->where('code', 'other')->value('id');

        if ($oldMacOsId !== null && $otherId === null) {
            DB::table('platforms')->where('id', $oldMacOsId)->update([
                'code' => 'other',
                'name' => 'Autre',
            ]);
            $otherId = $oldMacOsId;
        } elseif ($oldMacOsId !== null) {
            DB::table('system_requirements as old_requirements')
                ->where('old_requirements.platform_id', $oldMacOsId)
                ->whereExists(function ($query) use ($otherId): void {
                    $query->selectRaw('1')
                        ->from('system_requirements as other_requirements')
                        ->whereColumn('other_requirements.product_id', 'old_requirements.product_id')
                        ->whereColumn('other_requirements.tier', 'old_requirements.tier')
                        ->where('other_requirements.platform_id', $otherId);
                })
                ->delete();

            DB::table('system_requirements')->where('platform_id', $oldMacOsId)->update(['platform_id' => $otherId]);
            DB::table('product_files')->where('platform_id', $oldMacOsId)->update(['platform_id' => $otherId]);
            DB::table('platforms')->where('id', $oldMacOsId)->delete();
        }

        foreach ([['windows', 'Windows'], ['linux', 'Linux'], ['other', 'Autre']] as [$code, $name]) {
            $exists = DB::table('platforms')->where('code', $code)->exists();

            if ($exists) {
                DB::table('platforms')->where('code', $code)->update(['name' => $name, 'updated_at' => now()]);
            } else {
                DB::table('platforms')->insert([
                    'code' => $code,
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function addEnumCheck(string $table, string $column, array $allowed): void
    {
        $values = implode(', ', array_map(fn (string $value): string => DB::getPdo()->quote($value), $allowed));

        $this->addCheck($table, $column, sprintf('"%s" IN (%s)', $column, $values));
    }

    private function addCheck(string $table, string $column, string $expression): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $constraint = $this->constraintName($table, $column);

        if ($this->constraintExists($constraint) || $this->columnAlreadyHasCheck($table, $column)) {
            return;
        }

        DB::statement(sprintf(
            'ALTER TABLE "%s" ADD CONSTRAINT "%s" CHECK (%s)',
            $table,
            $constraint,
            $expression,
        ));
    }

    private function constraintName(string $table, string $column): string
    {
        return "gamehub_{$table}_{$column}_check";
    }

    private function constraintExists(string $constraint): bool
    {
        return DB::table('pg_constraint')->where('conname', $constraint)->exists();
    }

    private function columnAlreadyHasCheck(string $table, string $column): bool
    {
        if (DB::getDriverName() !== 'pgsql') {
            return false;
        }

        $result = DB::selectOne(
            "SELECT EXISTS (
                SELECT 1
                FROM pg_constraint
                WHERE conrelid = ?::regclass
                    AND contype = 'c'
                    AND pg_get_constraintdef(oid) ILIKE ?
            ) AS present",
            [$table, '%'.$column.'%'],
        );

        return (bool) $result->present;
    }
};
