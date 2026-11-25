<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Payments;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PaymentEvent
 *
 * @property int $id
 * @property int|null $payment_id
 * @property string $provider
 * @property string $provider_event_id
 * @property string $payload
 * @property bool $signature_valid
 * @property string $outcome
 * @property Carbon|null $processed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Payment|null $payment
 */
class PaymentEvent extends Model
{
    protected $table = 'payment_events';

    protected $casts = [
        'payment_id' => 'int',
        'signature_valid' => 'bool',
        'processed_at' => 'datetime',
        'payload' => 'array',
    ];

    protected $fillable = [
        'payment_id',
        'provider',
        'provider_event_id',
        'payload',
        'signature_valid',
        'outcome',
        'processed_at',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
