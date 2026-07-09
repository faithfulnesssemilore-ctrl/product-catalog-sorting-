<?php
declare(strict_types=1);
namespace App\Sorter;

class SalesPerViewSorter extends AbstractSorter{
    protected function extractValue(array $product): float{
        $views = (float) ($product['views_count'] ?? 0.0);
        if ($views === 0.0) {
            return 0.0;// handle the edge case of products with zero views by returning a ratio of 0.0 to avoid division by zero errors.
        }
        return (float) ($product['sales_count'] ?? 0.0) / $views;// calculate the sales-per-view ratio by dividing the sales count by the views count, ensuring that both values are treated as floats for accurate division.
    }
}