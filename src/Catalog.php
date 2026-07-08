<?php
declare(strict_types=1);//Enforces strict data-type rules.

namespace App;
use App\Sorter\SorterInterface;


class Catalog{
   private array $products;// Secure our products data by making it private so that it can only be accessed within the class.

     public function __construct(array $products){ //Initializes the object  when it is created.
        $this->products = $products;//We call the products data and store it in the private property.
     }
     
     public function getProducts(SorterInterface $sorter): array{ //We define a method getProducts that takes a SorterInterface object as a parameter and returns an array of products.
        return $sorter->sort($this->products);
     }
}
