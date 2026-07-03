<?php
declare(strict_types=1);
namespace App\Enums; 

enum SortDirection: string {
    case ASC = 'asc';
    case DESC = 'desc'; 

     public static function fromString(string $direction): self {
        $normalized = strtolower(trim($direction));
        return self::tryFrom($normalized) ?? 
        throw new \InvalidArgumentException("Invalid sort direction: '$direction'. Only accepted methods are 'asc' and 'desc'");
     }

     public function multiplier(): int {
        return $this === self::ASC ? 1 : -1;
    }

}
