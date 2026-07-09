<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Catalog;
use App\Enums\SortDirection;
use App\Registry\SorterRegistry;
use App\Sorter\DateSorter;
use App\Sorter\PriceSorter;
use App\Sorter\SalesPerViewSorter;

$products = [
    ['id' => 1, 'name' => 'Alabaster Table', 'price' => 12.99, 'created_at' => '2019-01-04', 'sales_count' => 32, 'views_count' => 730],
    ['id' => 2, 'name' => 'Zebra Table', 'price' => 44.49, 'created_at' => '2012-01-04', 'sales_count' => 301, 'views_count' => 3279],
    ['id' => 3, 'name' => 'Coffee Table', 'price' => 10.00, 'created_at' => '2014-05-28', 'sales_count' => 1048, 'views_count' => 20123],
];

$catalog = new Catalog($products);

$registry = new SorterRegistry();

$registry->register('price', new PriceSorter(SortDirection::ASC));
$registry->register('popularity', new SalesPerViewSorter(SortDirection::DESC));
$registry->register('newest', new DateSorter(SortDirection::DESC));

echo " HOMEPAGE (popularity - sales count descending) " . PHP_EOL;
foreach ($catalog->getProducts($registry->get('popularity')) as $home) {
    echo "<br> This is  {$home['name']} : sales_count = {$home['sales_count']} <br>" . PHP_EOL;
}

echo "<br>" . " SALE PAGE (price ascending) " . PHP_EOL;
foreach ($catalog->getProducts($registry->get('price')) as $p) {
    echo "<br>- {$p['name']} : price = $" . number_format($p['price'], 2) . ",<br>" . PHP_EOL ;
}

echo "<br>" . "NEW ARRIVALS (created date descending) " . PHP_EOL;
foreach ($catalog->getProducts($registry->get('newest')) as $p) {
    echo "<br>- {$p['name']}, : created = {$p['created_at']},  ,<br>" . PHP_EOL ;
}
