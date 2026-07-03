# Product Catalog Sorting

**Engineering Task Submission**  

---

#  Project Overview

This project contains my solution for the **Product Catalog Sorting** engineering task.

The objective was to design a **highly extensible backend sorting engine** for an e-commerce platform that allows multiple frontend teams (Homepage, Sale Page, New Arrivals, etc.) to dynamically apply different sorting strategies using configuration keys or query parameters—without modifying the core database models.

##  Features

- ** Sorting Strategies**
  - Sort by Price
  - Sort by Popularity *(Sales ÷ Views)*
  - Sort by Creation Date

- **Composite Tie-Breaking**
  - Automatically applies a secondary sorting rule whenever the primary sorter results in a tie.

- **Zero-Division Protection**
  - Prevents runtime errors when calculating popularity for newly created products that have zero views.

- **Registry-Based Strategy Lookup**
  - Maps incoming query string keys directly to fully instantiated sorting strategies using an associative array (hash map), providing constant-time **O(1)** lookup performance.

---

#  Tech Stack

| Technology 

| PHP 8+ 
| Strict Types | Type Safety |
| Composer 
| Pest - Testing Framework |

---

#  Why I Chose Pest Instead of PHPUnit

For this submission, I intentionally chose **Pest** over PHPUnit.

Pest is a modern testing framework built on top of PHPUnit that emphasizes **readability**, **simplicity**, and an excellent developer experience.

Instead of writing verbose class-based tests, Pest allows tests to be written using expressive, sentence-based syntax such as:

```php
test('it sorts products by price', function () {
    // ...
});
```

This style makes the test suite read more like a **product specification** than traditional test code, making it easier forme to understand the expected business behavior without digging through complex testing structures.

---

#  Running the Project

## 1. Clone the Repository

```bash
git clone  https://github.com/faithfulnesssemilore-ctrl/product-catalog-sorting-.git
cd product-catalog-sorter
```

## 2. Install Dependencies

```bash
composer install
```

## 3. Run the Test Suite

```bash
vendor/bin/pest
```

---

#  Architecture Questions & Answers

## 1. Which SOLID principles does your design satisfy?

###  Single Responsibility Principle (SRP)

Each class has exactly one responsibility.

- **Catalog** manages product storage.
- **Sorters** perform comparison logic.
- **SorterRegistry** resolves sorter keys.
- **ChainedSorter** coordinates multiple sorters.

Every component focuses on a single concern.

---

###  Open/Closed Principle (OCP)

The application is **open for extension but closed for modification**.

For example, if the business later requires sorting by **Discount Percentage**, a developer simply creates a new sorter class implementing the existing interface.

No existing production code needs to be modified.

---

###  Dependency Inversion Principle (DIP)

The `Catalog` class depends on the abstraction (`SorterInterface`) rather than concrete implementations like:

- `PriceSorter`
- `PopularitySorter`
- `DateSorter`

This keeps the catalog completely decoupled from any specific sorting algorithm.

---

## 2. Why did you choose an Associative Array for the registry?

The registry is implemented using a native PHP **Associative Array (Hash Map)**.

It maps simple text keys such as:

```text
price
popularity
newest
```

directly to instantiated sorter objects.

This provides:

- Constant-time lookup
- Minimal memory overhead
- Excellent scalability
- Fast request parsing for incoming API query parameters

Using a hash map is the most efficient lookup structure for this problem.

---

## 3. What happens when two products have the same value?

When two products are equal according to the primary sorter (for example, identical prices), the sorter returns:

```php
0
```

using PHP's spaceship operator (`<=>`).

The `ChainedSorter` detects this tie and automatically delegates comparison to the next sorter in the chain.

Example:

```
PriceSorter
      ↓
Same price?
      ↓
DateSorter
      ↓
Newest product wins
```

This ensures deterministic and predictable ordering.

---

## 4. How would you automatically register sorters instead of manually adding them?

If this project grew larger, I would eliminate manual registration by using **PHP Attributes**.

Each sorter could declare its registry key directly:

```php
#[SorterKey('price')]
class PriceSorter implements SorterInterface
{
    // ...
}
```

During application startup, a registry scanner would:

1. Scan the `src/Sorter` directory.
2. Discover sorter classes automatically.
3. Read their attributes using Reflection.
4. Register them into the registry.


---

#  Project Structure

```
src/
│
├── Catalog.php
├--Enums/SorterDirection.php
│
├── Sorter/
|   |--AbstractSorter.php
│   ├── SorterInterface.php
│   ├── PriceSorter.php
│   ├── PopularitySorter.php
│   ├── DateSorter.php
│   └── ChainedSorter.php
│
└── Registry/
    └── SorterRegistry.php

tests/
```

---

#  Testing

The project includes automated tests covering:

- Price sorting
- Popularity sorting
- Date sorting
- Registry lookups
- Tie-breaking behavior
- Division-by-zero protection
- Invalid sorter requests
- Strategy chaining
- No product exist

The goal is to ensure both correctness and long-term maintainability.

---

#  Feedback

Thank you for taking the time to review this submission.

If you have any questions, suggestions, or feedback regarding the architecture or implementation, feel free to open an issue or reach out during the code review process.

I appreciate the opportunity to complete this engineering task.
