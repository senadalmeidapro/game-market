<?php

use App\Enums\Catalog\ProductFileStatus;
use App\Enums\Catalog\ProductVersionStatus;
use App\Filament\Resources\Catalog\Products\Pages\ListProducts;
use App\Filament\Resources\Commerce\Orders\Pages\ListOrders;
use App\Filament\Resources\Identity\Users\Pages\CreateUser;
use App\Filament\Resources\Platform\AuditLogs\Pages\ListAuditLogs;
use App\Filament\Resources\Support\SupportTickets\Pages\ViewSupportTicket;
use App\Filament\Resources\Support\SupportTickets\RelationManagers\MessagesRelationManager;
use App\Filament\Resources\Support\SupportTickets\SupportTicketResource;
use App\Models\Catalog\Category;
use App\Models\Catalog\Platform;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductFile;
use App\Models\Catalog\ProductPriceHistory;
use App\Models\Catalog\ProductVersion;
use App\Models\Catalog\Publisher;
use App\Models\Catalog\SystemRequirement;
use App\Models\Commerce\Order;
use App\Models\Finance\LedgerEntry;
use App\Models\Payments\Payment;
use App\Models\Payments\Refund;
use App\Models\Platform\AuditLog;
use App\Models\Support\SupportMessage;
use App\Models\Support\SupportTicket;
use App\Models\User;
use App\Services\Catalog\PublishProduct;
use App\Services\Identity\ProtectLastSuperAdmin;
use App\Services\Identity\ProtectSuperAdminRole;
use Filament\Panel;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('blocks publication until the game has its catalog and distribution requirements', function () {
    $publisher = Publisher::query()->create(['name' => 'Studio Test']);
    $product = Product::query()->create([
        'publisher_id' => $publisher->id,
        'type' => 'game',
        'title' => 'Jeu test',
        'slug' => 'jeu-test',
        'price_amount' => 0,
        'status' => 'draft',
    ]);

    $blockers = app(PublishProduct::class)->blockers($product);

    expect($blockers)->toHaveCount(4)
        ->and($blockers)->toContain('Ajouter une jaquette principale.')
        ->and($blockers)->toContain('Le prix doit être supérieur à zéro.')
        ->and($blockers)->toContain('Renseigner au moins une configuration système minimale.');
});

it('records price changes in history and in the audit trail', function () {
    $product = Product::query()->create([
        'type' => 'game',
        'title' => 'Jeu tarifé',
        'slug' => 'jeu-tarife',
        'price_amount' => 1000,
        'status' => 'draft',
    ]);

    $product->update(['price_amount' => 1500]);

    expect(ProductPriceHistory::query()->where('product_id', $product->id)->first())
        ->old_price_amount->toBe(1000)
        ->new_price_amount->toBe(1500);
    expect(AuditLog::query()->where('action', 'product.updated')->where('subject_id', $product->id)->exists())->toBeTrue();
});

it('calculates the actual build hash and refuses publication after the file changes', function () {
    Storage::fake('local');
    Storage::disk('local')->put('game-builds/build.zip', 'original build bytes');

    $product = Product::query()->create([
        'type' => 'game', 'title' => 'Jeu fichier', 'slug' => 'jeu-fichier', 'price_amount' => 1000, 'status' => 'draft',
    ]);
    $version = ProductVersion::query()->create([
        'product_id' => $product->id, 'version_number' => '1.0.0', 'status' => ProductVersionStatus::Distributable,
    ]);
    $file = ProductFile::query()->create([
        'product_version_id' => $version->id, 'kind' => 'build', 'filename' => 'build.zip',
        'storage_key' => 'game-builds/build.zip', 'status' => ProductFileStatus::Uploaded,
    ]);

    expect($file->fresh()->sha256)->toBe(hash('sha256', 'original build bytes'))
        ->and($file->fresh()->size_bytes)->toBe(strlen('original build bytes'))
        ->and($file->fresh()->status)->toBe(ProductFileStatus::Verified);

    $product->addMedia(UploadedFile::fake()->image('cover.jpg'))->toMediaCollection('cover');
    $platform = Platform::query()->firstOrCreate(['code' => 'windows'], ['name' => 'Windows']);
    SystemRequirement::query()->create([
        'product_id' => $product->id, 'platform_id' => $platform->id, 'tier' => 'minimum', 'cpu' => 'x64',
    ]);
    expect(app(PublishProduct::class)->publish($product))->toBe([])
        ->and($product->fresh()->status->value)->toBe('published');

    Storage::disk('local')->put('game-builds/build.zip', 'tampered build bytes');
    expect(app(PublishProduct::class)->blockers($product))->toContain('Ajouter une version distribuable avec au moins un fichier présent et vérifié (SHA-256 valide).');
});

it('allows only active, verified, two-factor-confirmed staff into the admin panel', function () {
    $role = Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::query()->create([
        'name' => 'Admin Test', 'email' => 'admin@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $user->forceFill(['email_verified_at' => now(), 'two_factor_confirmed_at' => now()])->save();
    $user->assignRole($role);
    $panel = Panel::make()->id('admin');

    expect($user->canAccessPanel($panel))->toBeTrue();
    Event::dispatch(new Login('web', $user, false));
    expect(AuditLog::query()->where('action', 'admin.login')->where('subject_id', $user->id)->exists())->toBeTrue();
    $user->forceFill(['two_factor_confirmed_at' => null])->save();
    expect($user->canAccessPanel($panel))->toBeFalse();
    $user->forceFill(['two_factor_confirmed_at' => now()])->save();
    $user->update(['status' => 'suspended']);
    expect($user->canAccessPanel($panel))->toBeFalse();
});

it('audits payment, refund and ledger lifecycle changes', function () {
    $customer = User::query()->create([
        'name' => 'Acheteur', 'email' => 'acheteur@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $order = Order::query()->create([
        'reference' => 'GH-TEST-0001', 'user_id' => $customer->id, 'status' => 'pending',
        'subtotal_amount' => 2500, 'discount_amount' => 0, 'tax_amount' => 0,
        'total_amount' => 2500, 'currency' => 'XOF',
    ]);
    $payment = Payment::query()->create([
        'order_id' => $order->id, 'provider' => 'manual', 'method' => 'card', 'amount' => 2500,
        'currency' => 'XOF', 'status' => 'initiated',
    ]);
    $payment->update(['status' => 'failed', 'failure_reason' => 'Test']);
    $refund = Refund::query()->create([
        'order_id' => $order->id, 'payment_id' => $payment->id, 'amount' => 2500,
        'reason' => 'Test remboursement', 'status' => 'requested', 'requested_by' => $customer->id,
    ]);
    $entry = LedgerEntry::query()->create([
        'type' => 'refund', 'order_id' => $order->id, 'payment_id' => $payment->id,
        'refund_id' => $refund->id, 'amount' => -2500, 'currency' => 'XOF',
        'occurred_at' => now(), 'description' => 'Remboursement test',
    ]);

    expect(AuditLog::query()->where('action', 'Payment.updated')->where('subject_id', $payment->id)->exists())->toBeTrue()
        ->and(AuditLog::query()->where('action', 'Refund.created')->where('subject_id', $refund->id)->exists())->toBeTrue()
        ->and(AuditLog::query()->where('action', 'LedgerEntry.created')->where('subject_id', $entry->id)->exists())->toBeTrue();
});

it('preserves an active super administrator when the last account is edited', function () {
    $superAdminRole = Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);
    $catalogRole = Role::query()->create(['name' => 'catalog_manager', 'guard_name' => 'web']);
    $user = User::query()->create([
        'name' => 'Seul admin', 'email' => 'solo@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $user->assignRole($superAdminRole);

    expect(fn () => app(ProtectLastSuperAdmin::class)->assertCanUpdate($user, [$catalogRole->id], 'active'))
        ->toThrow(ValidationException::class);
    expect(fn () => app(ProtectLastSuperAdmin::class)->assertCanUpdate($user, [$superAdminRole->id], 'suspended'))
        ->toThrow(ValidationException::class);
    expect(fn () => app(ProtectLastSuperAdmin::class)->assertCanUpdate($user, [$superAdminRole->id], 'active'))
        ->not->toThrow(ValidationException::class);
});

it('keeps role management access on the super admin role', function () {
    $role = Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);
    $manageRoles = Permission::query()->create(['name' => 'roles.manage', 'guard_name' => 'web']);
    $manageUsers = Permission::query()->create(['name' => 'users.manage', 'guard_name' => 'web']);
    $catalog = Permission::query()->create(['name' => 'catalog.view', 'guard_name' => 'web']);

    expect(fn () => app(ProtectSuperAdminRole::class)->assertCanUpdate($role, 'catalog_admin', [$manageRoles->id, $manageUsers->id]))
        ->toThrow(ValidationException::class);
    expect(fn () => app(ProtectSuperAdminRole::class)->assertCanUpdate($role, 'super_admin', [$catalog->id]))
        ->toThrow(ValidationException::class);
    expect(fn () => app(ProtectSuperAdminRole::class)->assertCanUpdate($role, 'super_admin', [$manageRoles->id, $manageUsers->id]))
        ->not->toThrow(ValidationException::class);
});

it('invites a staff account with password reset and email verification links', function () {
    Notification::fake();
    $permission = Permission::query()->create(['name' => 'users.manage', 'guard_name' => 'web']);
    $superAdminRole = Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);
    $superAdminRole->givePermissionTo($permission);
    $admin = User::query()->create([
        'name' => 'Administrateur', 'email' => 'root@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $admin->forceFill(['email_verified_at' => now(), 'two_factor_confirmed_at' => now()])->save();
    $admin->assignRole($superAdminRole);
    $this->actingAs($admin);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Nouveau support',
            'email' => 'support@example.test',
            'status' => 'active',
            'roles' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $invited = User::query()->where('email', 'support@example.test')->firstOrFail();
    expect($invited->status)->toBe('active')
        ->and($invited->email_verified_at)->toBeNull()
        ->and(filled($invited->password))->toBeTrue();
    Notification::assertSentTo($invited, VerifyEmail::class);
    Notification::assertSentTo($invited, ResetPassword::class);
    expect(AuditLog::query()->where('action', 'user.roles_assigned')->where('subject_id', $invited->id)->exists())->toBeTrue();
});

it('filters the product and order tables by their operational fields', function () {
    $catalogPermission = Permission::query()->create(['name' => 'catalog.view', 'guard_name' => 'web']);
    $ordersPermission = Permission::query()->create(['name' => 'orders.view', 'guard_name' => 'web']);
    $catalogRole = Role::query()->create(['name' => 'catalog_manager', 'guard_name' => 'web']);
    $catalogRole->givePermissionTo($catalogPermission);
    $financeRole = Role::query()->create(['name' => 'finance_manager', 'guard_name' => 'web']);
    $financeRole->givePermissionTo($ordersPermission);
    $staff = User::query()->create([
        'name' => 'Catalogue', 'email' => 'filtre@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $staff->assignRole($catalogRole, $financeRole);
    $this->actingAs($staff);

    $draft = Product::query()->create(['type' => 'game', 'title' => 'Brouillon', 'slug' => 'brouillon', 'price_amount' => 1000, 'status' => 'draft']);
    $publisher = Publisher::query()->create(['name' => 'Studio filtre']);
    $draft->publisher()->associate($publisher);
    $draft->published_at = '2026-11-24 12:00:00';
    $draft->save();
    $published = Product::query()->create([
        'type' => 'game', 'title' => 'Publié', 'slug' => 'publie', 'price_amount' => 2000,
        'status' => 'published', 'published_at' => '2026-11-12 12:00:00',
    ]);
    Livewire::test(ListProducts::class)
        ->filterTable('status', 'draft')
        ->assertCanSeeTableRecords([$draft])
        ->assertCanNotSeeTableRecords([$published]);
    Livewire::test(ListProducts::class)
        ->filterTable('publisher', $publisher)
        ->assertCanSeeTableRecords([$draft])
        ->assertCanNotSeeTableRecords([$published]);
    Livewire::test(ListProducts::class)
        ->filterTable('published_at', ['from' => '2026-11-23', 'until' => '2026-11-25'])
        ->assertCanSeeTableRecords([$draft])
        ->assertCanNotSeeTableRecords([$published]);

    $firstOrder = Order::query()->create([
        'reference' => 'GH-FILTER-0001', 'user_id' => $staff->id, 'status' => 'pending',
        'subtotal_amount' => 1000, 'discount_amount' => 0, 'tax_amount' => 0, 'total_amount' => 1000, 'currency' => 'XOF',
    ]);
    $secondOrder = Order::query()->create([
        'reference' => 'GH-FILTER-0002', 'user_id' => $staff->id, 'status' => 'paid',
        'subtotal_amount' => 2000, 'discount_amount' => 0, 'tax_amount' => 0, 'total_amount' => 2000, 'currency' => 'XOF',
    ]);
    $secondCustomer = User::query()->create([
        'name' => 'Second client', 'email' => 'second-client@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $secondOrder->forceFill(['user_id' => $secondCustomer->id, 'created_at' => '2026-11-12 10:00:00'])->save();
    $firstOrder->forceFill(['created_at' => '2026-11-24 10:00:00'])->save();
    Livewire::test(ListOrders::class)
        ->filterTable('status', 'paid')
        ->assertCanSeeTableRecords([$secondOrder])
        ->assertCanNotSeeTableRecords([$firstOrder]);
    Livewire::test(ListOrders::class)
        ->filterTable('user', $staff)
        ->assertCanSeeTableRecords([$firstOrder])
        ->assertCanNotSeeTableRecords([$secondOrder]);
    Livewire::test(ListOrders::class)
        ->filterTable('created_at', ['from' => '2026-11-23', 'until' => '2026-11-25'])
        ->assertCanSeeTableRecords([$firstOrder])
        ->assertCanNotSeeTableRecords([$secondOrder]);
});

it('filters the audit table by actor, action, object type and date', function () {
    $permission = Permission::query()->create(['name' => 'audit.view', 'guard_name' => 'web']);
    $role = Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);
    $role->givePermissionTo($permission);
    $actor = User::query()->create([
        'name' => 'Audit admin', 'email' => 'audit-admin@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $actor->assignRole($role);
    $this->actingAs($actor);
    $login = AuditLog::query()->create([
        'actor_id' => $actor->id, 'action' => 'admin.login', 'subject_type' => User::class,
        'subject_id' => $actor->id,
    ]);
    $login->forceFill(['created_at' => '2026-11-24 10:00:00'])->save();
    $other = AuditLog::query()->create([
        'actor_id' => null, 'action' => 'Product.updated', 'subject_type' => Product::class,
        'subject_id' => 99,
    ]);
    $other->forceFill(['created_at' => '2026-11-12 10:00:00'])->save();

    Livewire::test(ListAuditLogs::class)
        ->filterTable('action', 'admin.login')
        ->assertCanSeeTableRecords([$login])
        ->assertCanNotSeeTableRecords([$other]);
    Livewire::test(ListAuditLogs::class)
        ->filterTable('user', $actor)
        ->assertCanSeeTableRecords([$login])
        ->assertCanNotSeeTableRecords([$other]);
    Livewire::test(ListAuditLogs::class)
        ->filterTable('created_at', ['from' => '2026-11-23', 'until' => '2026-11-25'])
        ->assertCanSeeTableRecords([$login])
        ->assertCanNotSeeTableRecords([$other]);
});

it('resumes a catalog build upload and assembles it with a checked hash', function () {
    Storage::fake('local');
    $permission = Permission::query()->create(['name' => 'catalog.manage', 'guard_name' => 'web']);
    $role = Role::query()->create(['name' => 'catalog_manager', 'guard_name' => 'web']);
    $role->givePermissionTo($permission);
    $user = User::query()->create([
        'name' => 'Gestionnaire', 'email' => 'catalog@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $user->forceFill(['two_factor_confirmed_at' => now()])->save();
    $user->assignRole($role);
    $this->actingAs($user);
    $uploadId = (string) Str::uuid();
    $payload = ['upload_id' => $uploadId, 'total_chunks' => 2, 'file_size_bytes' => 12, 'filename' => 'Test Build.zip'];

    $this->postJson(route('admin.build-uploads.chunks'), [...$payload, 'chunk_index' => 0, 'chunk' => UploadedFile::fake()->createWithContent('Test Build.zip', 'first-')])->assertOk();
    $this->getJson(route('admin.build-uploads.status', $uploadId))->assertOk()->assertJsonPath('uploaded_chunks', [0]);
    $this->postJson(route('admin.build-uploads.chunks'), [...$payload, 'chunk_index' => 1, 'chunk' => UploadedFile::fake()->createWithContent('Test Build.zip', 'second')])->assertOk();

    $response = $this->postJson(route('admin.build-uploads.complete'), ['upload_id' => $uploadId, 'total_chunks' => 2, 'file_size_bytes' => 12])->assertCreated();
    $key = $response->json('storage_key');
    expect(Storage::disk('local')->get($key))->toBe('first-second')
        ->and($response->json('sha256'))->toBe(hash('sha256', 'first-second'));
});

it('keeps used taxonomy references and wires the support message thread', function () {
    $category = Category::query()->create(['name' => 'Action', 'slug' => 'action']);
    $product = Product::query()->create([
        'type' => 'game', 'title' => 'Jeu catégorisé', 'slug' => 'jeu-categorise', 'price_amount' => 1000, 'status' => 'draft',
    ]);
    $product->categories()->attach($category);

    expect(fn () => $category->delete())->toThrow(QueryException::class);
    expect(SupportTicketResource::getRelations())->toContain(MessagesRelationManager::class);

    $customer = User::query()->create([
        'name' => 'Client Test', 'email' => 'client@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $ticket = SupportTicket::query()->create([
        'user_id' => $customer->id, 'subject' => 'Installation', 'category' => 'technical', 'status' => 'open',
    ]);
    $message = SupportMessage::query()->create([
        'ticket_id' => $ticket->id, 'author_id' => $customer->id, 'body' => 'Le jeu ne démarre pas.', 'is_internal' => false,
    ]);

    expect(AuditLog::query()->where('action', 'SupportMessage.created')->where('subject_id', $message->id)->exists())->toBeTrue();
});

it('lets authorized staff reply to a ticket and add an internal note', function () {
    $permissions = collect(['tickets.view', 'tickets.handle'])->map(
        fn (string $name) => Permission::query()->create(['name' => $name, 'guard_name' => 'web']),
    );
    $role = Role::query()->create(['name' => 'support_agent', 'guard_name' => 'web']);
    $role->givePermissionTo($permissions);
    $staff = User::query()->create([
        'name' => 'Support', 'email' => 'support-staff@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $staff->assignRole($role);
    $customer = User::query()->create([
        'name' => 'Client', 'email' => 'ticket-client@example.test', 'password' => 'secret', 'status' => 'active',
    ]);
    $ticket = SupportTicket::query()->create([
        'user_id' => $customer->id, 'subject' => 'Aide installation', 'category' => 'technical', 'status' => 'open',
    ]);
    $this->actingAs($staff);

    Livewire::test(MessagesRelationManager::class, ['ownerRecord' => $ticket, 'pageClass' => ViewSupportTicket::class])
        ->callTableAction('create', data: ['body' => 'Bonjour, voici la marche à suivre.', 'is_internal' => false]);

    $message = $ticket->messages()->firstOrFail();
    expect($message->author_id)->toBe($staff->id)
        ->and($message->body)->toBe('Bonjour, voici la marche à suivre.')
        ->and($message->is_internal)->toBeFalse()
        ->and(AuditLog::query()->where('action', 'SupportMessage.created')->where('subject_id', $message->id)->exists())->toBeTrue();
});
