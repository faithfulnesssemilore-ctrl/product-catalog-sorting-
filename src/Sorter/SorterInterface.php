<?php
declare(strict_types=1);

namespace App\Sorter;

interface SorterInterface{

    public function compare(array $productA, array $productB): int; // Compares two products and returns an integer indicating their order.

    public function sort(array $products): array; // Sorts an array of products based on the comparison logic defined in the compare method.
}
