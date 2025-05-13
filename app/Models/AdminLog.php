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

            foreach (['changed', 'created', 'deleted'] as $key) {
                if (empty($parsed[$key]) || !is_array($parsed[$key])) {
                    continue;
                }

                $data = $parsed[$key];
                $lines = [];

                if ($key === 'changed') {
                    // 額外顯示 id（若有）
                    if (isset($parsed['id'])) {
                        $lines[] = "id: " . $this->formatValue($parsed['id']);
                    }

                    foreach ($data as $field => $change) {
                        if (is_array($change) && array_key_exists('before', $change) && array_key_exists('after', $change)) {
                            $before = $this->formatValue($change['before']);
                            $after  = $this->formatValue($change['after']);
                            $lines[] = "{$field}: {$before} → {$after}";
                        }
                    }

                    if (!empty($lines)) {
                        $sections[] = implode("\n", $lines);
                    }

                } else {
                    // created / deleted：只顯示 id、name、title
                    foreach (['id', 'name', 'title'] as $field) {
                        if (isset($data[$field])) {
                            $value = $this->formatValue($data[$field]);
                            $lines[] = "{$field}: {$value}";
                        }
                    }

                    if (!empty($lines)) {
                        $sections[] = implode("\n", $lines);
                    }
                }
            }

            return implode("\n\n", $sections);

        } catch (\JsonException) {
            return trim(preg_replace('/\s+/', ' ', $desc));
        }
    }

    protected function formatValue($value): string
    {
        if (is_null($value)) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string)$value;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
