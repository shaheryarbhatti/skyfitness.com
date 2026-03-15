<?php

namespace App\Services;

use App\Models\Currency;

class CurrencyService
{
    /**
     * Get the active currency
     */
    public static function getActiveCurrency()
    {
        return Currency::active()->first();
    }

    /**
     * Convert amount to active currency
     * Assumes amount is in base currency (Indonesian Rupiah - RP)
     * exchange_rate represents how many units of this currency = 1 RP
     */
    public static function convertToActive($amount, $fromCurrencyId = null)
    {
        $activeCurrency = self::getActiveCurrency();

        if (!$activeCurrency) {
            return $amount; // No conversion if no active currency
        }

        // If amount is in a specific currency, first convert to base (RP)
        if ($fromCurrencyId) {
            $fromCurrency = Currency::find($fromCurrencyId);
            if ($fromCurrency) {
                // Convert from currency to RP: amount * from_exchange_rate
                $amount = $amount * $fromCurrency->exchange_rate;
            }
        }

        // Now convert from RP to active currency: amount / active_exchange_rate
        return $amount / $activeCurrency->exchange_rate;
    }

    /**
     * Format amount with active currency symbol
     */
    public static function formatAmount($amount, $fromCurrencyId = null)
    {
        $convertedAmount = self::convertToActive($amount, $fromCurrencyId);
        $activeCurrency = self::getActiveCurrency();

        if ($activeCurrency) {
            return $activeCurrency->symbol . number_format($convertedAmount, 2);
        }

        return number_format($convertedAmount, 2);
    }
}
