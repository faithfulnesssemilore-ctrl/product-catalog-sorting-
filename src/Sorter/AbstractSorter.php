<?php
declare(strict_types=1);

namespace App\Sorter;
use App\Enums\SortDirection;
                                                                                                                    
abstract class AbstractSorter implements SorterInterface            {                                                                  
    protected SortDirection $direction;

    public function __construct(string|SortDirection $direction = 'asc')// Sets up the sorting orientation at class creation.
    {
        $this->direction = is_string($direction)
            ? SortDirection::fromString($direction)
            : $direction;
    }
     
    public function sort(array $products): array { // Sorts an array of products based on the comparison logic defined in the compare method.

     if (count ($products) === 0) {// Graceful empty array defense.
        return [];
     }

     $sorted = $products;
     usort($sorted, function (array $productA, array $productB): int{// Drives the automated arrangement engine.
        return $this->compare($productA, $productB);
     });
     return $sorted;
    }
    public function compare(array $productA, array $productB): int { // Compares two products and returns an integer indicating their order.
        $valueA = $this->extractValue($productA);
        $valueB = $this->extractValue($productB);
        return ($valueA <=> $valueB) * $this->direction->multiplier();
    }
  
    abstract protected function extractValue(array $product): float; // Extracts the value to be compared from a product array.
}