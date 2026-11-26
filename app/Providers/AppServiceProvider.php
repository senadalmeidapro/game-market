<?php

namespace App\Providers;

use App\Models\Catalog\Category;
use App\Models\Catalog\Genre;
use App\Models\Catalog\Platform;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductFile;
use App\Models\Catalog\ProductVersion;
use App\Models\Catalog\Publisher;
use App\Models\Commerce\Order;
use App\Models\Commerce\Promotion;
use App\Models\Finance\LedgerEntry;
use App\Models\Payments\Payment;
use App\Models\Payments\PaymentEvent;
use App\Models\Payments\Refund;
use App\Models\Platform\AuditLog;
use App\Models\Platform\LegalPage;
use App\Models\Platform\Setting;
use App\Models\Support\SupportMessage;
use App\Models\Support\SupportTicket;
use App\Models\User;
use App\Observers\OperationalAuditObserver;
use App\Observers\ProductFileObserver;
use App\Observers\ProductObserver;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        ProductFile::observe(ProductFileObserver::class);
        ProductFile::observe(OperationalAuditObserver::class);
        ProductVersion::observe(OperationalAuditObserver::class);
        Category::observe(OperationalAuditObserver::class);
        Genre::observe(OperationalAuditObserver::class);
        Platform::observe(OperationalAuditObserver::class);
        Order::observe(OperationalAuditObserver::class);
        SupportTicket::observe(OperationalAuditObserver::class);
        SupportMessage::observe(OperationalAuditObserver::class);
        Publisher::observe(OperationalAuditObserver::class);
        User::observe(OperationalAuditObserver::class);
        Payment::observe(OperationalAuditObserver::class);
        Refund::observe(OperationalAuditObserver::class);
        LedgerEntry::observe(OperationalAuditObserver::class);
        PaymentEvent::observe(OperationalAuditObserver::class);
        Promotion::observe(OperationalAuditObserver::class);
        LegalPage::observe(OperationalAuditObserver::class);
        Setting::observe(OperationalAuditObserver::class);
        Role::observe(OperationalAuditObserver::class);
        Permission::observe(OperationalAuditObserver::class);
        Event::listen(Login::class, function (Login $event): void {
            if ($event->user->status !== 'active'
                || $event->user->two_factor_confirmed_at === null
                || ! $event->user->hasAnyRole(['super_admin', 'catalog_manager', 'finance_manager', 'marketing_manager', 'support_agent'])) {
                return;
            }

            AuditLog::query()->create([
                'actor_id' => $event->user->getKey(), 'action' => 'admin.login',
                'subject_type' => User::class, 'subject_id' => $event->user->getKey(),
                'ip_address' => request()->ip(), 'created_at' => now(),
            ]);
        });
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
