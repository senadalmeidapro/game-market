<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Support;

use App\Models\Commerce\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SupportTicket
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $order_id
 * @property int|null $assigned_to
 * @property string $subject
 * @property string $category
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User|null $user
 * @property Order|null $order
 * @property Collection|SupportMessage[] $support_messages
 */
class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected $casts = [
        'user_id' => 'int',
        'order_id' => 'int',
        'assigned_to' => 'int',
    ];

    protected $fillable = [
        'user_id',
        'order_id',
        'assigned_to',
        'subject',
        'category',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id');
    }
}
