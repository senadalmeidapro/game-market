<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use App\Models\Commerce\Order;
use App\Models\Payments\Payment;
use App\Models\Payments\Refund;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class LedgerEntry
 *
 * @property int $id
 * @property string $type
 * @property int|null $order_id
 * @property int|null $payment_id
 * @property int|null $refund_id
 * @property int $amount
 * @property string $currency
 * @property Carbon $occurred_at
 * @property string $description
 * @property int|null $reverses_id
 * @property Order|null $order
 * @property Payment|null $payment
 * @property Refund|null $refund
 * @property LedgerEntry|null $ledger_entry
 * @property Collection|LedgerEntry[] $ledger_entries
 */
class LedgerEntry extends Model
{
    protected $table = 'ledger_entries';

    public $timestamps = false;

    protected $casts = [
        'order_id' => 'int',
        'payment_id' => 'int',
        'refund_id' => 'int',
        'amount' => 'int',
        'occurred_at' => 'datetime',
        'reverses_id' => 'int',
    ];

    protected $fillable = [
        'type',
        'order_id',
        'payment_id',
        'refund_id',
        'amount',
        'currency',
        'occurred_at',
        'description',
        'reverses_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

    public function reversalOf(): BelongsTo
    {
        return $this->belongsTo(LedgerEntry::class, 'reverses_id');
    }

    public function reversals(): HasMany
    {
        return $this->hasMany(LedgerEntry::class, 'reverses_id');
    }
}
