<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Currency;

class CurrencyRateService
{
    /**
     * Fetch the rate of 1 unit of the given currency to USD.
     * Returns null if unavailable.
     */
    public function getRateToUSD(string $code): ?float
    {
        $code = strtoupper(trim($code));
        if ($code === 'USD') {
            return 1.0;
        }

        $rates = $this->getRatesToUSD([$code]);
        return $rates[$code] ?? null;
    }

    /**
     * Update and persist the currency's rate_to_usd if available.
     */
    public function updateCurrencyRate(Currency $currency): bool
    {
        $rate = $this->getRateToUSD($currency->code);
        if ($rate === null) {
            return false;
        }
        // لم نعد نخزن سعر الصرف في قاعدة البيانات، فقط نرجع نجاح الجلب
        return $rate !== null;
    }

    /**
     * Fetch live rates to USD for multiple currency codes in one request.
     * Returns map: [code => rate_to_usd]. USD is always 1.0
     */
    public function getRatesToUSD(array $codes): array
    {
        $codes = array_values(array_unique(array_map(fn($c) => strtoupper(trim($c)), $codes)));
        if (empty($codes)) return [];

        if (!in_array('USD', $codes, true)) {
            $codes[] = 'USD';
        }

        $cacheKey = 'currency_rates_to_usd:' . implode(',', $codes);
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($codes) {
            $result = $this->fetchFromExchangerateHost($codes);
            if (empty($result)) {
                $result = $this->fetchFromOpenErApi($codes);
            }
            return $result;
        });
    }

    private function fetchFromExchangerateHost(array $codes): array
    {
        try {
            $response = Http::timeout(10)->get('https://api.exchangerate.host/latest', [
                'base' => 'USD',
                'symbols' => implode(',', $codes),
            ]);

            if (!$response->ok()) {
                return [];
            }

            $data = $response->json();
            // Ensure the API indicates success and provides rates; the service now
            // requires an access_key for most endpoints. If not successful, force
            // fallback by returning an empty result.
            if (!is_array($data) || ($data['success'] ?? false) !== true || empty($data['rates'])) {
                return [];
            }
            $result = [];
            foreach ($codes as $code) {
                if ($code === 'USD') {
                    $result[$code] = 1.0;
                    continue;
                }
                $rateFromUsd = $data['rates'][$code] ?? null;
                if ($rateFromUsd) {
                    $result[$code] = round(1.0 / (float) $rateFromUsd, 8);
                }
            }
            // If we only ended up with USD (no other codes), treat as failure to
            // trigger fallback provider.
            if (count($result) <= 1) {
                return [];
            }
            // Remember provider name for UI display
            Cache::put('currency_rates_provider', 'exchangerate.host', now()->addMinutes(10));
            return $result;
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function fetchFromOpenErApi(array $codes): array
    {
        try {
            $response = Http::timeout(10)->get('https://open.er-api.com/v6/latest/USD');
            if (!$response->ok()) {
                return [];
            }
            $data = $response->json();
            if (($data['result'] ?? '') !== 'success' || empty($data['rates'])) {
                return [];
            }
            $rates = $data['rates'] ?? [];
            $result = [];
            foreach ($codes as $code) {
                if ($code === 'USD') {
                    $result[$code] = 1.0;
                    continue;
                }
                $rateFromUsd = $rates[$code] ?? null;
                if ($rateFromUsd) {
                    $result[$code] = round(1.0 / (float) $rateFromUsd, 8);
                }
            }
            if (count($result) <= 1) {
                return [];
            }
            // Remember provider name for UI display
            Cache::put('currency_rates_provider', 'open.er-api.com', now()->addMinutes(10));
            return $result;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Returns the name of the last successful rates provider, from cache.
     */
    public function getRatesProvider(): ?string
    {
        return Cache::get('currency_rates_provider');
    }
}
