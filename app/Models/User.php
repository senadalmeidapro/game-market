<?php

namespace App\Models;

use App\Models\Catalog\ProductPriceHistory;
use App\Models\Commerce\Cart;
use App\Models\Commerce\Order;
use App\Models\Commerce\PromotionUsage;
use App\Models\Commerce\Wishlist;
use App\Models\Feedback\ProductReview;
use App\Models\Feedback\ProductReviewComment;
use App\Models\Identity\Passkey;
use App\Models\Identity\SocialAccount;
use App\Models\Library\DownloadGrant;
use App\Models\Library\Licence;
use App\Models\Payments\Refund;
use App\Models\Platform\AuditLog;
use App\Models\Support\SupportMessage;
use App\Models\Support\SupportTicket;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'phone'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    public function passkeys(): HasMany
    {
        return $this->hasMany(Passkey::class);
    }

    public function productPriceHistories(): HasMany
    {
        return $this->hasMany(ProductPriceHistory::class, 'changed_by');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlist(): HasOne
    {
        return $this->hasOne(Wishlist::class);
    }

    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function productReviewComments(): HasMany
    {
        return $this->hasMany(ProductReviewComment::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function promotionUsages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }

    public function requestedRefunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'requested_by');
    }

    public function approvedRefunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'approved_by');
    }

    public function licences(): HasMany
    {
        return $this->hasMany(Licence::class);
    }

    public function downloadGrants(): HasMany
    {
        return $this->hasMany(DownloadGrant::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function assignedSupportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'author_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'actor_id');
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }
}
