<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Payments;

use App\Models\Commerce\Order;
use App\Models\Finance\LedgerEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Refund
 *
 * @property int $id
 * @property int $order_id
 * @property int $payment_id
 * @property int $amount
 * @property string $reason
 * @property string $status
 * @property int|null $requested_by
 * @property int|null $approved_by
 * @property string|null $provider_refund_reference
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Order $order
 * @property Payment $payment
 * @property User|null $user
 * @property Collection|LedgerEntry[] $ledger_entries
 */
class Refund extends Model
{
    protected $table = 'refunds';

    protected $casts = [
        'order_id' => 'int',
        'payment_id' => 'int',
        'amount' => 'int',
        'requested_by' => 'int',
        'approved_by' => 'int',
        'completed_at' => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'payment_id',
        'amount',
        'reason',
        'status',
        'requested_by',
        'approved_by',
        'provider_refund_reference',
        'completed_at',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }
}
