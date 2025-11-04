<?php

namespace App\Traits;

use App\Models\SystemLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            self::writeLog($model, 'create', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            if (!empty($dirty)) {
                $old = [];
                foreach ($dirty as $key => $value) {
                    $old[$key] = $model->getOriginal($key);
                }
                self::writeLog($model, 'update', $old, $dirty);
            }
        });

        static::deleted(function ($model) {
            self::writeLog($model, 'delete', $model->getAttributes(), null);
        });
    }

    protected static function writeLog($model, string $action, ?array $oldValues, ?array $newValues): void
    {
        try {
            SystemLog::create([
                'user_id' => optional(auth()->user())->id,
                'type' => 'model',
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->getKey(),
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'message' => __("logs.messages.model_{$action}", ['model' => class_basename($model)]),
                'locale' => app()->getLocale(),
            ]);
        } catch (\Throwable $e) {
            // Swallow logging errors to not affect business logic
        }
    }
}
