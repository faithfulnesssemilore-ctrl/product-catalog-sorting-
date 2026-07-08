<?php
declare(strict_types=1);
namespace App\Enums; 

enum SortDirection: string {
    case ASC = 'asc';
    case DESC = 'desc'; //these are the two accepted methods of sorting our data in ascending and descending order.

     public static function fromString(string $direction): self {//if the user inputs a string that is not accepted we will throw an exception to let them know that they have inputted an invalid sort direction.
        $normalized = strtolower(trim($direction));
        return self::tryFrom($normalized) ?? 
        throw new \InvalidArgumentException("Invalid sort direction: '$direction'. Only accepted methods are 'asc' and 'desc'");
     }

     public function multiplier(): int {//This the multiplier method that will return a value of 1 for ascending order and -1 for descending order. This is useful for sorting algorithms that require a multiplier to determine the order of elements.
        return $this === self::ASC ? 1 : -1;
    }

}
