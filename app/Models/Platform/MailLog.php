<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Platform;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MailLog
 *
 * @property int $id
 * @property string $recipient
 * @property string $template
 * @property string $status
 * @property string|null $provider_message_id
 * @property string|null $failure_reason
 * @property Carbon|null $sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class MailLog extends Model
{
    protected $table = 'mail_logs';

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    protected $fillable = [
        'recipient',
        'template',
        'status',
        'provider_message_id',
        'failure_reason',
        'sent_at',
    ];
}
