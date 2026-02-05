<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    public const BASE_CURRENCY = 'AED';

    public function code(): string
    {
        return session('preferred_currency', self::BASE_CURRENCY);
    }

    public function rate(): float
    {
        $code = $this->code();

        if ($code === self::BASE_CURRENCY) {
            return 1.0;
        }

        return $this->fetchRate(self::BASE_CURRENCY, $code);
    }

    public function convert(float $amount, ?string $targetCode = null): float
    {
        $code = $targetCode ?: $this->code();

        if ($code === self::BASE_CURRENCY) {
            return $amount;
        }

        return $amount * $this->rate();
    }

    public function convertToBase(float $amount, ?string $sourceCode = null): float
    {
        $code = $sourceCode ?: $this->code();

        if ($code === self::BASE_CURRENCY) {
            return $amount;
        }

        $rate = $this->rate();
        if ($rate <= 0) {
            return $amount;
        }

        return $amount / $rate;
    }

    public function format(float $amount, int $decimals = 2): string
    {
        $code = $this->code();
        $converted = $this->convert($amount, $code);

        return $code . ' ' . number_format($converted, $decimals);
    }

    protected function fetchRate(string $base, string $target): float
    {
        $cacheKey = 'currency_rate_' . $base . '_' . $target;

        return Cache::remember($cacheKey, now()->addHour(), function () use ($base, $target) {
            try {
                $response = Http::timeout(5)->get('https://api.exchangerate.host/latest', [
                    'base' => $base,
                    'symbols' => $target,
                ]);

                if ($response->successful()) {
                    $rate = data_get($response->json(), 'rates.' . $target);
                    if (is_numeric($rate)) {
                        return (float) $rate;
                    }
                }
            } catch (\Throwable $e) {
                // Ignore and fallback below
            }

            return 15.0;
        });
    }
}
