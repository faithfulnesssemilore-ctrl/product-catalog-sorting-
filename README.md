# Product Catalog Sorting

## Overview

A flexible product catalog sorting engine built in plain PHP. It supports multiple sorting strategies, configurable sort directions, chained sorting, and registry-based strategy lookup without modifying the core `Catalog` class.

## Features

- Sort by Price
- Sort by Sales per View (Popularity)
- Sort by Creation Date
- Ascending and Descending sorting
- Chained sorting (tie-breaking)
- Registry-based sorter lookup
- Unit tests using Pest

## Requirements

- PHP 8.4+
- Composer

## Installation

```bash
git clone https://github.com/faithfulnesssemilore-ctrl/product-catalog-sorting-.git

cd product-catalog-sorting-

composer install
```

## Run Tests

```bash
vendor/bin/pest
```
## Run example script
## To run the example script:
     ```bash 
     php index.php    or  php -S localhost:8000  
```
- This should display the process when  someone actually instantiate the Catalog, register sorters in the registry, and then call getProducts..

```

HOMEPAGE (popularity - sales count descending) This is Zebra Table : sales_count = 301, This is Coffee Table : sales_count = 1048, This is Alabaster Table : sales_count = 32

SALE PAGE (price ascending) - Coffee Table : price = $10.00, - Alabaster Table : price = $12.99, - Zebra Table : price = $44.49

NEW ARRIVALS (created date descending) - Alabaster Table : created = 2019-01-04, - Coffee Table : created = 2014-05-28, - Zebra Table : created = 2012-01-04

```
- You can open and edit the demo.php file with your preferred IDE or code editor to register the sorting strategies using unique keys that u want.

## Adding a New Sorter
- To add a new sorter, follow these steps:

Create a new class in the src/Sorters folder extends AbstractSorter and  implements  extractValue() to return the value for sorting,register it in the registry, and the rest of the system works without modifying the existing catalog or sorting infrastructure.

## Project Structure

src/
├── Catalog.php
├── Enums/
├── Registry/
├── Sorter/

tests/
```

For the design decisions , see **DESIGN.md**.
