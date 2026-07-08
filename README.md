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
     ```bash 
     php index.php    or  php -S localhost:8000  
```

## Project Structure

```
src/
├── Catalog.php
├── Enums/
├── Registry/
├── Sorter/

tests/
```

For the design decisions , see **DESIGN.md**.
