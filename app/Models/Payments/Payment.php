<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Payments;

use App\Models\Commerce\Order;
use App\Models\Finance\LedgerEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Payment
 *
 * @property int $id
 * @property int $order_id
 * @property string $provider
 * @property string $method
 * @property string|null $provider_reference
 * @property int $amount
 * @property string $currency
 * @property string $status
 * @property Carbon|null $paid_at
 * @property string|null $failure_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Order $order
 * @property Collection|PaymentEvent[] $payment_events
 * @property Collection|Refund[] $refunds
 * @property Collection|LedgerEntry[] $ledger_entries
 */
class Payment extends Model
{
    protected $table = 'payments';

    protected $casts = [
        'order_id' => 'int',
        'amount' => 'int',
        'paid_at' => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'provider',
        'method',
        'provider_reference',
        'amount',
        'currency',
        'status',
        'paid_at',
        'failure_reason',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(PaymentEvent::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }
}
