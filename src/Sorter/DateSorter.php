<?php 
declare(strict_types=1);

namespace App\Sorter;
class DateSorter extends AbstractSorter{
    protected function extractValue(array $product): float
{
    return isset($product['created_at']) ? (float) strtotime($product['created_at']) : 0.0;
}}