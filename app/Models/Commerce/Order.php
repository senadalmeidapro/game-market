<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Commerce;

use App\Models\Finance\LedgerEntry;
use App\Models\Payments\Payment;
use App\Models\Payments\Refund;
use App\Models\Support\SupportTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Order
 *
 * @property int $id
 * @property string $reference
 * @property int $user_id
 * @property int|null $promotion_id
 * @property string $status
 * @property int $subtotal_amount
 * @property int $discount_amount
 * @property int $tax_amount
 * @property int $total_amount
 * @property string $currency
 * @property string|null $promotion_code_snapshot
 * @property Carbon|null $expires_at
 * @property Carbon|null $paid_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 * @property Promotion|null $promotion
 * @property Collection|OrderItem[] $order_items
 * @property Collection|PromotionUsage[] $promotion_usages
 * @property Collection|Payment[] $payments
 * @property Collection|Refund[] $refunds
 * @property Collection|LedgerEntry[] $ledger_entries
 * @property Collection|SupportTicket[] $support_tickets
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $casts = [
        'user_id' => 'int',
        'promotion_id' => 'int',
        'subtotal_amount' => 'int',
        'discount_amount' => 'int',
        'tax_amount' => 'int',
        'total_amount' => 'int',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected $fillable = [
        'reference',
        'user_id',
        'promotion_id',
        'status',
        'subtotal_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'currency',
        'promotion_code_snapshot',
        'expires_at',
        'paid_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function promotionUsages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }
}
