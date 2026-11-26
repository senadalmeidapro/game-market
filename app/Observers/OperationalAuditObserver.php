<?php

namespace App\Observers;

use App\Models\Platform\AuditLog;
use Illuminate\Database\Eloquent\Model;

class OperationalAuditObserver
{
    public function created(Model $record): void
    {
        $this->write('created', $record, [], $this->values($record));
    }

    public function updated(Model $record): void
    {
        $old = $new = [];
        foreach (array_keys($record->getChanges()) as $key) {
            if (in_array($key, ['updated_at', 'password', 'two_factor_secret', 'two_factor_recovery_codes'], true)) {
                continue;
            }
            $old[$key] = $record->getOriginal($key);
            $new[$key] = $record->getAttribute($key);
        }
        if ($new !== []) {
            $this->write('updated', $record, $old, $new);
        }
    }

    public function deleted(Model $record): void
    {
        $this->write('deleted', $record, $this->values($record), []);
    }

    private function values(Model $record): array
    {
        return collect($record->getAttributes())->except(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])->all();
    }

    private function write(string $event, Model $record, array $old, array $new): void
    {
        AuditLog::query()->create([
            'actor_id' => auth()->id(), 'action' => class_basename($record).'.'.$event,
            'subject_type' => $record::class, 'subject_id' => $record->getKey(),
            'old_values' => $old ?: null, 'new_values' => $new ?: null,
            'ip_address' => request()?->ip(), 'created_at' => now(),
        ]);
    }
}
