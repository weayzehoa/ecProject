<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait LoggableRepository
{
    protected function logModelCreated(string $action, Model $model): void
    {
        $logKey = $this->guessLogKey($model);

        adminLog($action, [
            $logKey => $model->getKey(),
            'created' => $model->getAttributes(),
        ]);
    }

    protected function logModelDeleted(string $action, Model $model): void
    {
        $logKey = $this->guessLogKey($model);

        adminLog($action, [
            $logKey => $model->getKey(),
            'deleted' => $model->getAttributes(),
        ]);
    }

    protected function logModelChanges(
        string $action,
        Model $model,
        array $original,
        ?string $logKey = null,
        array $ignoreFields = ['updated_at']
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
            $logData = ['changed' => $changed];

            // ✅ 自動推斷 log key，如果沒指定
            if (!$logKey) {
                $logKey = $this->guessLogKey($model);
            }

            $logData[$logKey] = $model->getKey();

            adminLog($action, $logData);
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
