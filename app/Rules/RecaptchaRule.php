<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;

class RecaptchaRule implements ValidationRule
{
    protected string $secret;
    protected string $version;
    protected float $minScore;
    protected string $action;

    public function __construct(
        string $version = 'v3',
        ?string $secret = null,
        float $minScore = 0.5,
        string $action = 'login'
    ) {
        $this->version = $version;
        $this->action = $action;
        $this->minScore = $minScore;

        $this->secret = $secret ?: match ($version) {
            'v2' => env('GOOGLE_NOCAPTCHA_V2_SECRET'),
            'v3' => env('GOOGLE_NOCAPTCHA_V3_SECRET'),
            default => env('GOOGLE_NOCAPTCHA_V3_SECRET'),
        };
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value) {
            $fail(__('recaptcha.required'));
            return;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $this->secret,
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (! $response->ok()) {
            $fail(__('recaptcha.unreachable'));
            return;
        }

        $data = $response->json();

        if (! ($data['success'] ?? false)) {
            $fail(__('recaptcha.failed'));
            return;
        }

        if ($this->version === 'v3') {
            if (($data['action'] ?? '') !== $this->action || ($data['score'] ?? 0) < $this->minScore) {
                $fail(__('recaptcha.low_score'));
            }
        }

    }
}
