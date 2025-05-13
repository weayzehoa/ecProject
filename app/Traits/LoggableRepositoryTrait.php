<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait LoggableRepositoryTrait
{
    protected function logModelCreated(string $action, Model $model): void
    {
        adminLog($action, [
            'id' => $model->getKey(),
            'created' => $model->getAttributes(),
        ]);
    }

    protected function logModelDeleted(string $action, Model $model): void
    {
        adminLog($action, [
            'id' => $model->getKey(),
            'deleted' => $model->getAttributes(),
        ]);
    }

    protected function logModelChanges(
        string $action,
        Model $model,
        array $original,
        ?string $logKey = null,
        array $ignoreFields = ['created_at', 'updated_at', 'deleted_at'] // ✅ 可自訂忽略欄位
    ): void {
        $after = $model->getAttributes();
        $changed = [];

        foreach ($after as $key => $value) {
            if (in_array($key, $ignoreFields)) continue;

            $old = $original[$key] ?? null;
            if ($old !== $value) {
                $changed[$key] = [
                    'before' => $old,
                    'after' => $value,
                ];
            }
        }

        if (!empty($changed)) {
            adminLog($action, [
                'id' => $model->getKey(),
                'changed' => $changed,
            ]);
        }
    }

    /**
     * 自動從 Model 類名推斷 log key，例如：CompanySetting → company_setting_id
     */
    protected function guessLogKey(Model $model): string
    {
        return Str::snake(class_basename($model)) . '_id';
    }
}
