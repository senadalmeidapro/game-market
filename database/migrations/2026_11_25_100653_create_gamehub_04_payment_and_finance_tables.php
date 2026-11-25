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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->string('provider', 64);
            $table->enum('method', ['mobile_money', 'card']);
            $table->string('provider_reference')->nullable();
            $table->unsignedBigInteger('amount');
            $table->enum('currency', ['XOF'])->default('XOF');
            $table->enum('status', ['initiated', 'pending', 'succeeded', 'failed', 'expired', 'flagged'])->default('initiated')->index();
            $table->timestamp('paid_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'provider_reference']);
            $table->index(['order_id', 'created_at']);
        });

        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider', 64);
            $table->string('provider_event_id');
            $table->json('payload');
            $table->boolean('signature_valid')->default(false);
            $table->string('outcome', 32)->default('received');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'provider_event_id']);
            $table->index(['outcome', 'created_at']);
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('payment_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('amount');
            $table->text('reason');
            $table->enum('status', ['requested', 'approved', 'manual_payout_pending', 'completed', 'rejected'])->default('requested')->index();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('provider_refund_reference')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique('payment_id');
            $table->index(['order_id', 'status']);
        });

        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sale', 'refund', 'adjustment']);
            $table->foreignId('order_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('refund_id')->nullable()->constrained()->restrictOnDelete();
            $table->bigInteger('amount');
            $table->enum('currency', ['XOF'])->default('XOF');
            $table->timestamp('occurred_at')->useCurrent();
            $table->string('description');
            $table->foreignId('reverses_id')->nullable()->constrained('ledger_entries')->restrictOnDelete();
            $table->index(['occurred_at', 'type']);
            $table->index(['order_id', 'occurred_at']);
        });

        Schema::create('daily_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->unsignedBigInteger('orders')->default(0);
            $table->unsignedBigInteger('revenue_amount')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('refund_amount')->default(0);
            $table->unsignedBigInteger('new_customers')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_stats');
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payment_events');
        Schema::dropIfExists('payments');
    }
};
