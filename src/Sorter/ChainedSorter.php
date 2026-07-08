<?php
declare(strict_types=1);
namespace App\Sorter;

// Combines multiple sorters into priority chain
class ChainedSorter implements SorterInterface{
    private array $sorters;

    // Validates and stores sorters for chaining
    public function __construct(array $sorters){
       
        if (count($sorters) < 2) {
            throw new \InvalidArgumentException('ChainedSorter requires at least two sorters.');
        }
        // Verify each sorter implements required interface
        foreach ($sorters as $sorter) {
            if (!($sorter instanceof SorterInterface)) {
                throw new \InvalidArgumentException('All sorters must implement SorterInterface.');
            }
        }
        // Re-indexes array and stores for later use 
        $this->sorters = array_values($sorters);
    }

    // Sorts products using chained sorters
    public function sort(array $products): array{
        // Return empty array if no products
        if (count($products) === 0) return [];

        $sorted = $products;
        // Apply usort with custom comparison logic
        usort($sorted, function (array $productA, array $productB): int {
            return $this->compare($productA, $productB);
        });
        // Return final sorted products array
        return $sorted;
    }
    // Compares products through sorter chain order
    public function compare(array $productA, array $productB): int{
        // Apply each sorter until difference found
        foreach ($this->sorters as $sorter) {
            // Get comparison result from current sorter
            $result = $sorter->compare($productA, $productB);

            // Return immediately if not equal
            if ($result !== 0) {
                return $result;
            }
        }
        // Products are equal across all sorters
        return 0;
    }
}