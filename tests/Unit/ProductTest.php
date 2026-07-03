<?php

use App\Catalog;
use App\Enums\SortDirection;
use App\Sorter\ChainedSorter;
use App\Sorter\PriceSorter;
use App\Sorter\SalesPerViewSorter;
use App\Sorter\DateSorter;
use App\Registry\SorterRegistry;


beforeEach(function () {
    $this->products = [
        ['id' => 1, 'name' => 'Alabaster Table', 'price' => 12.99, 'created_at' => '2019-01-04', 'sales_count' => 32,   'views_count' => 730],
        ['id' => 2, 'name' => 'Zebra Table',     'price' => 44.49, 'created_at' => '2012-01-04', 'sales_count' => 301,  'views_count' => 3279],
        ['id' => 3, 'name' => 'Coffee Table',    'price' => 10.00, 'created_at' => '2014-05-28', 'sales_count' => 1048, 'views_count' => 20123],
    ];
    $this->catalog = new Catalog($this->products);
});

it('SALE PAGE (sorts products by Price ascending)', function () {
   
    $sorter = new PriceSorter(SortDirection::ASC);
    $result = $this->catalog->getProducts($sorter);

    expect($result[0]['id'])->toBe(3); // Coffee Table ($10.00)
    expect($result[1]['id'])->toBe(1); // Alabaster Table ($12.99)
    expect($result[2]['id'])->toBe(2); // Zebra Table ($44.49)
});

it('SALE PAGE (sorts products by price descending)', function () {
    $sorter = new PriceSorter(SortDirection::DESC);
    $result = $this->catalog->getProducts($sorter);

    expect($result[0]['id'])->toBe(2); // Zebra Table ($44.49)
    expect($result[1]['id'])->toBe(1); // Alabaster Table ($12.99)
    expect($result[2]['id'])->toBe(3); // Coffee Table ($10.00)
});

it('sorts products by sales-per-view ratio', function () {
    $sorter = new SalesPerViewSorter(SortDirection::DESC);
    $result = $this->catalog->getProducts($sorter);

    expect($result[0]['id'])->toBe(2); // Highest ratio
    expect($result[2]['id'])->toBe(1); // Lowest ratio
});
     
it('sorts products by creation date descending', function () {
    $sorter = new DateSorter(SortDirection::DESC);
    $result = $this->catalog->getProducts($sorter);
    
    expect($result[0]['id'])->toBe(1); // Alabaster Table (2019)
    expect($result[1]['id'])->toBe(3); // Coffee Table (2014)
    expect($result[2]['id'])->toBe(2); // Zebra Table (2012)
});

it('handles the edge case of products with Zero views', function () {
    $productsWithZeroViews = [
        ['id' => 4, 'name' => 'Test Table', 'price' => 25.00, 'created_at' => '2020-01-01', 'sales_count' => 100, 'views_count' => 0],
        ['id' => 10, 'name' => 'No View Item', 'price' => 15.00, 'created_at' => '2020-01-01','sales_count' => 0, 'views_count' => 0],
        ['id' => 11, 'name' => 'High View Item', 'price' => 15.00, 'created_at' => '2020-01-01','sales_count' => 10, 'views_count' => 10],
    ];
    $catalogWithZeroViews = new Catalog($productsWithZeroViews);
    $sorter = new SalesPerViewSorter(SortDirection::DESC);
    $result = $catalogWithZeroViews->getProducts($sorter);
    
    expect($result[0]['id'])->toBe(11); // Ratio = 1.0 (Highest)
    expect($result[1]['id'])->toBe(4);  // Ratio = 0.0 (Preserves array order, 4 comes before 10)
    expect($result[2]['id'])->toBe(10); // Ratio = 0.0 
});
   
it('[Test ChainedSorter] supports chained sorting to break ties', function () {
    $tiedProducts = [
        ['id' => 1, 'name' => 'Old Tied Item', 'price' => 20.00, 'created_at' => '2026-02-01'],
        ['id' => 2, 'name' => 'New Tied Item', 'price' => 20.00, 'created_at' => '2025-01-01'],
        ['id' => 3, 'name' => 'last Tied Item', 'price' => 20.00, 'created_at' => '2026-02-01'],
    ];
    $catalog = new Catalog($tiedProducts);
    
    $priceSorter = new PriceSorter(SortDirection::ASC);
    $dateSorter = new DateSorter(SortDirection::DESC);
    $chained = new ChainedSorter([$priceSorter, $dateSorter]);

    $result = $catalog->getProducts($chained);
    
    expect($result[0]['id'])->toBe(1); // Old Tied Item (2026-02-01)
    expect($result[1]['id'])->toBe(3); // last Tied Item (2026-02-01)
    expect($result[2]['id'])->toBe(2); // New Tied Item (2025-01-01)
});

it('throws an exception when fed an invalid direction string', function () {
    expect(function () {
        SortDirection::fromString('invalid_text');
    })->toThrow(\InvalidArgumentException::class);
});

it('registry maps text keys to sorters and handles invalid keys', function () {
    $registry = new SorterRegistry();
    $priceSorter = new PriceSorter(SortDirection::ASC);
    
    $registry->register('price_cheapest', $priceSorter);
    $retrievedSorter = $registry->get('price_cheapest');

    expect($registry->has('price_cheapest'))->toBeTrue();
    expect($retrievedSorter)->toBe($priceSorter);

    expect(fn() => $registry->get('unknown_key'))
        ->toThrow(\InvalidArgumentException::class);
});

it('will returns an empty array when no products exist', function () {
    $catalog = new Catalog([]);
    $sorter = new PriceSorter(SortDirection::ASC);
    
    $result = $catalog->getProducts($sorter);
    
    expect($result)->toBeEmpty();
});