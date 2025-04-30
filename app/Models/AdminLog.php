<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'description',
        'ip',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    public function getDescriptionTextAttribute(): string
    {
        $desc = $this->description;

        try {
            $parsed = json_decode($desc, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($parsed)) {
                return $desc;
            }

            $sections = [];

            foreach (['changed' => '變更', 'created' => '建立', 'deleted' => '刪除'] as $key => $label) {
                if (empty($parsed[$key]) || !is_array($parsed[$key])) {
                    continue;
                }

                $data = $parsed[$key];
                $lines = [];

                if ($key === 'changed') {
                    foreach ($data as $field => $change) {
                        if (is_array($change) && isset($change['before'], $change['after'])) {
                            $before = is_scalar($change['before']) ? $change['before'] : json_encode($change['before'], JSON_UNESCAPED_UNICODE);
                            $after  = is_scalar($change['after'])  ? $change['after']  : json_encode($change['after'], JSON_UNESCAPED_UNICODE);
                            $lines[] = "{$field}: {$before} → {$after}";
                        }
                    }

                    if ($this->created_at) {
                        $lines[] = "變更時間: " . $this->created_at->format('Y-m-d H:i:s');
                    }

                } elseif ($key === 'created') {
                    foreach (['id', 'name', 'title'] as $field) {
                        if (isset($data[$field])) {
                            $value = is_scalar($data[$field]) ? $data[$field] : json_encode($data[$field], JSON_UNESCAPED_UNICODE);
                            $lines[] = "{$field}: {$value}";
                        }
                    }

                    if (isset($data['created_at'])) {
                        $lines[] = "建立時間: {$data['created_at']}";
                    }

                } elseif ($key === 'deleted') {
                    foreach (['id', 'name', 'title'] as $field) {
                        if (isset($data[$field])) {
                            $value = is_scalar($data[$field]) ? $data[$field] : json_encode($data[$field], JSON_UNESCAPED_UNICODE);
                            $lines[] = "{$field}: {$value}";
                        }
                    }

                    $deletedTime = $data['deleted_at'] ?? $this->created_at?->format('Y-m-d H:i:s') ?? '';
                    if ($deletedTime) {
                        $lines[] = "刪除時間: {$deletedTime}";
                    }
                }

                if (!empty($lines)) {
                    $sections[] = "{$label}：\n" . implode("\n", $lines);
                }
            }

            return implode("\n\n", $sections);

        } catch (\JsonException) {
            $content = trim(preg_replace('/\s+/', ' ', $desc));
            $createdAt = $this->created_at?->format('Y-m-d H:i:s');
            return $createdAt
                ? "{$content}\n操作時間: {$createdAt}"
                : $content;
        }
    }


}
