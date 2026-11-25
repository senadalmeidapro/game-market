<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DailyStat
 *
 * @property int $id
 * @property Carbon $date
 * @property int $orders
 * @property int $revenue_amount
 * @property int $discount_amount
 * @property int $refund_amount
 * @property int $new_customers
 * @property int $downloads
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class DailyStat extends Model
{
    protected $table = 'daily_stats';

    protected $casts = [
        'date' => 'datetime',
        'orders' => 'int',
        'revenue_amount' => 'int',
        'discount_amount' => 'int',
        'refund_amount' => 'int',
        'new_customers' => 'int',
        'downloads' => 'int',
    ];

    protected $fillable = [
        'date',
        'orders',
        'revenue_amount',
        'discount_amount',
        'refund_amount',
        'new_customers',
        'downloads',
    ];
}
