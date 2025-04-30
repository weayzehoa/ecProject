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
                    foreach ($data as $field => $change) {
                        if (is_array($change) && isset($change['before'], $change['after'])) {
                            $before = is_scalar($change['before']) ? $change['before'] : json_encode($change['before'], JSON_UNESCAPED_UNICODE);
                            $after  = is_scalar($change['after'])  ? $change['after']  : json_encode($change['after'], JSON_UNESCAPED_UNICODE);
                            $lines[] = "{$field}: {$before} → {$after}";
                        }
                    }

                    if (!empty($lines)) {
                        $sections[] = implode("\n", $lines); // ⛔ 不加 "變更：" 前綴
                    }

                } else {
                    // created / deleted：只顯示 id、name、title 欄位值，不加任何 label
                    foreach (['id', 'name', 'title'] as $field) {
                        if (isset($data[$field])) {
                            $value = is_scalar($data[$field]) ? $data[$field] : json_encode($data[$field], JSON_UNESCAPED_UNICODE);
                            $lines[] = "{$field}: {$value}";
                        }
                    }

                    if (!empty($lines)) {
                        $sections[] = implode("\n", $lines); // ⛔ 不加 created/deleted label
                    }
                }
            }

            return implode("\n\n", $sections);

        } catch (\JsonException) {
            $content = trim(preg_replace('/\s+/', ' ', $desc));
            return $content;
        }
    }

}
