<?php

namespace App\Observers;

use App\Models\Catalog\Product;
use App\Models\Platform\AuditLog;

class ProductObserver
{
    public function created(Product $product): void
    {
        $this->audit('created', $product, [], $product->only(['title', 'slug', 'publisher_id', 'price_amount', 'status']));
    }

    public function updated(Product $product): void
    {
        $changes = $product->getChanges();
        $old = [];
        $new = [];

        foreach (array_keys($changes) as $key) {
            if (in_array($key, ['updated_at'], true)) {
                continue;
            }
            $old[$key] = $product->getOriginal($key);
            $new[$key] = $product->getAttribute($key);
        }

        if (array_key_exists('price_amount', $changes) && $product->getOriginal('price_amount') !== null) {
            $product->priceHistories()->create([
                'old_price_amount' => $product->getOriginal('price_amount'),
                'new_price_amount' => $product->price_amount,
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]);
        }
        if ($new !== []) {
            $this->audit('updated', $product, $old, $new);
        }
    }

    public function deleted(Product $product): void
    {
        $this->audit('deleted', $product, $product->only(['title', 'slug', 'publisher_id', 'price_amount', 'status']), []);
    }

    private function audit(string $action, Product $product, array $old, array $new): void
    {
        AuditLog::query()->create([
            'actor_id' => auth()->id(), 'action' => 'product.'.$action,
            'subject_type' => Product::class, 'subject_id' => $product->getKey(),
            'old_values' => $old ?: null, 'new_values' => $new ?: null,
            'ip_address' => request()?->ip(), 'created_at' => now(),
        ]);
    }
}
