<?php
declare(strict_types=1);//Enforces strict data-type rules.

namespace App;
use App\Sorter\SorterInterface;


class Catalog{
   private array $products;// Secures our raw product data

     public function __construct(array $products){ //Initializes the object when it is born.
        $this->products = $products;
     }
     
     public function getProducts(SorterInterface $sorter): array{ // Exposes our sorted data to the outside world.
        return $sorter->sort($this->products);
     }
}