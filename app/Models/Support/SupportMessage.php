<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Support;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SupportMessage
 *
 * @property int $id
 * @property int $ticket_id
 * @property int|null $author_id
 * @property string $body
 * @property bool $is_internal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property SupportTicket $support_ticket
 * @property User|null $user
 */
class SupportMessage extends Model
{
    protected $table = 'support_messages';

    protected $casts = [
        'ticket_id' => 'int',
        'author_id' => 'int',
        'is_internal' => 'bool',
    ];

    protected $fillable = [
        'ticket_id',
        'author_id',
        'body',
        'is_internal',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
