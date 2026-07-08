<?php
declare(strict_types=1);

namespace App\Sorter;
use App\Enums\SortDirection;
                                                                                                                    
abstract class AbstractSorter implements SorterInterface            {                                                                  
    protected SortDirection $direction;

    public function __construct(string|SortDirection $direction = 'asc')
    {
        $this->direction = is_string($direction)
            ? SortDirection::fromString($direction)
            : $direction;//If a string comes in, we call the Enum's static helper method to convert it to an Enum instance. If it's already an Enum instance, we just use it directly.
    }

    public function sort(array $products): array { // Sorts an array of products based on the comparison logic defined in the compare method.

     if (count ($products) === 0) {// handles the edge case where the input array is empty. If there are no products to sort, we simply return an empty array.
        return [];
     }

     $sorted = $products;
     usort($sorted, function (array $productA, array $productB): int{
                       return $this->compare($productA, $productB);
     });
     return $sorted;
    }
    public function compare(array $productA, array $productB): int { // Compares two products and returns an integer indicating their order.
        $valueA = $this->extractValue($productA);
        $valueB = $this->extractValue($productB);
        return ($valueA <=> $valueB) * $this->direction->multiplier();// Multiply the spaceship operator by our Enum multiplier
    }
  
    abstract protected function extractValue(array $product): float; // Extracts the value to be compared from a product array.
}