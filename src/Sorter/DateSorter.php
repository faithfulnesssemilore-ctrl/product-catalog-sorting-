<?php 
declare(strict_types=1);

namespace App\Sorter;
class DateSorter extends AbstractSorter{
    protected function extractValue(array $product): float{
        $dateString = $product['created_at'] ?? null;
        if ($dateString === null) {
            return 0.0; // Default value for products without a date, why 0.0 because it represents the epoch time, which is a reasonable default for sorting purposes.
        }
        $timestamp = strtotime($dateString);
        return $timestamp !== false ? (float) $timestamp : 0.0; // Convert to float for comparison
    }
}