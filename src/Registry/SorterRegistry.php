<?php
declare(strict_types=1);
namespace App\Registry;

use App\Sorter\SorterInterface;

class SorterRegistry{
    private array $registry = []; // Holds the registered sorters

    public function register(string $key, SorterInterface $sorter): void{ // Registers a sorter with a unique key
        $this->registry[$key] = $sorter;//Binds the text label to the working machine inside our array.
    }
    

    public function get(string $key): SorterInterface{ // Retrieves a sorter by its key
        if (!isset($this->registry[$key])) {
            throw new \InvalidArgumentException("Sorter not found for key: $key Not registered.");
        }
        return $this->registry[$key];
    }
    public function has(string $key): bool{ // Checks if a sorter is registered under a given key
        return isset($this->registry[$key]);
    }
}