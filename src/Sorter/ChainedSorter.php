<?php
declare(strict_types=1);
namespace App\Sorter;

class ChainedSorter implements SorterInterface{
    private array $sorters;

    public function __construct(array $sorters){
        if (count($sorters) < 2) {
            throw new \InvalidArgumentException('ChainedSorter requires at least two sorters.');
        }
        foreach ($sorters as $sorter) {//verifies that each sorter implements the SorterInterface.
            if (!($sorter instanceof SorterInterface)) {
                throw new \InvalidArgumentException('All sorters must implement SorterInterface.');
            }
        }
        $this->sorters = array_values($sorters);
    }
    public function sort(array $products): array{
        if (count($products) === 0) return [];//its a good practice to handle empty arrays.

        $sorted = $products;
        usort($sorted, function (array $productA, array $productB): int {//starts the sorting process using a custom comparison function.
            return $this->compare($productA, $productB);
        });// Drives the automated arrangement engine.
        return $sorted;// Returns the sorted array.
    }
    public function compare(array $productA, array $productB): int{
        foreach ($this->sorters as $sorter) {// Iterates through each sorter in the chain.
            $result = $sorter->compare($productA, $productB);// Compares the two products using the current sorter.

            if ($result !== 0) {
                return $result;// If the comparison yields a non-zero result, it returns that result.
            }
        }
        return 0;//Absolute tie resolution.
    }
}