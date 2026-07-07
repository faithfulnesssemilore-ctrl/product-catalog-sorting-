# Product Catalog Sorting – Design Documentation

## Problem Statement

The goal of this task was to design a flexible and extensible product sorting engine for an e-commerce platform.

Different sections of the website may require different sorting strategies. For example:

* Homepage → Sort by Popularity
* Sale Page → Sort by Price
* New Arrivals → Sort by Date

The challenge was to ensure that new sorting strategies can be introduced without modifying the existing `Catalog` class or previously implemented sorters.

---

# Design Goals

The solution was designed with the following goals in mind:

* Extensibility
* Maintainability
* Reusability
* Separation of concerns
* Testability

These goals ensure that the system can grow without requiring changes to existing production code.

---

# Architecture Overview

```
Catalog
    │
    v
SorterInterface
    │
    v
AbstractSorter
    │
 ---┼-----------------------------------
 v  v               v                   v
PriceSorter   SalesPerViewSorter   DateSorter
    │
    v
ChainedSorter
    │
    v
SorterRegistry
```

### Flow

1. The `Catalog` receives a sorting strategy.
2. It delegates sorting to the provided sorter instead of implementing sorting logic itself.
3. Concrete sorters inherit common behavior from `AbstractSorter`.
4. `ChainedSorter` combines multiple sorters to support tie-breaking.
5. `SorterRegistry` resolves sorters from configuration keys or query parameters.

---

# Design Patterns Used

## Strategy Pattern

The Strategy Pattern allows different sorting algorithms to be swapped without changing the `Catalog`.

The `Catalog` depends only on `SorterInterface`, making it independent of any specific sorting implementation.

Adding a new sorting strategy only requires creating another class that implements the interface.

This satisfies the requirement that the catalog should never change regardless of how many sorters are added.

---

## Composite Pattern

The Composite Pattern is implemented by `ChainedSorter`.

Instead of using a single sorter, it combines multiple sorting strategies into one.

Example:

```
Price
   |
   v
Tie?
   |
   v
Newest Date
```

If the first sorter returns a tie (`0`), the next sorter automatically breaks the tie.

This allows multiple sorting rules to be chained together.

---

## Registry Pattern

The `SorterRegistry` acts as a central lookup table.

Instead of using long `if` or `switch` statements, sorters are registered once and retrieved using a configuration key.

Example:

```
price
↓

PriceSorter

popularity
↓

SalesPerViewSorter
```

This allows sorting strategies to be selected dynamically from configuration files or URL query parameters.

---

# Sort Direction

Sorting direction is handled using a PHP Enum named `SortDirection`.

Instead of passing raw strings throughout the application, only two valid directions exist:

* ASC
* DESC

The enum also provides a `multiplier()` helper.

* ASC → 1
* DESC → -1

This allows the comparison result to be reversed without duplicating sorting logic.

---

# Edge Case Handling

| Edge Case          | Solution                                                     |
|
| Zero views         | Returns `0` before division to avoid division-by-zero errors 
| Invalid direction  | Throws `InvalidArgumentException`                            |
| Identical values   | Delegates to `ChainedSorter`                                 |
| Empty product list | Returns an empty array immediately                           |

---

# SOLID Principles

## Single Responsibility Principle (SRP)

Each class has exactly one responsibility.

Examples:

* `Catalog` stores products.
* `AbstractSorter` contains shared sorting logic.
* `PriceSorter` extracts prices.
* `SalesPerViewSorter` calculates popularity.
* `DateSorter` extracts dates.
* `SorterRegistry` manages sorter registration.
* `ChainedSorter` coordinates multiple sorters.

---

## Open/Closed Principle (OCP)

The system is open for extension but closed for modification.

To add a new sorting strategy, a developer simply creates another sorter class.

No existing production code needs to change.

---

## Dependency Inversion Principle (DIP)

The `Catalog` depends on the `SorterInterface` abstraction instead of concrete implementations.

Because of this, the catalog can work with any current or future sorter.

---

# Why an Associative Array for the Registry?

The registry is implemented using a PHP associative array (hash map).

Example:

```
price        --> PriceSorter

popularity   --> SalesPerViewSorter

newest       --> DateSorter
```

This provides:

* Constant-time O(1) lookup
* Minimal memory overhead
* Fast resolution of query parameters
* Easy registration of new sorting strategies

A hash map is an efficient choice because the registry's primary responsibility is fast key-based lookup.

---

# Tie-Breaking

When two products have identical values, the comparison returns `0`.

Rather than leaving the order undefined, the `ChainedSorter` automatically applies the next sorting rule.

Example:

```
PriceSorter
      |
      v
Same price?
      |
      v
DateSorter
      |
      v
Newest product wins
```

This guarantees deterministic and predictable ordering.

---

# Automatic Sorter Registration

For this assessment, sorters are registered manually.

If the project became significantly larger, registration could be automated using PHP Attributes and Reflection.

Each sorter could declare its own registry key:

```
#[SorterKey('price')]
class PriceSorter
{
    ...
}
```

During application startup, the registry could:

* Scan the sorter directory
* Discover sorter classes
* Read their attributes
* Register them automatically

This removes the need for manual registration while keeping the registry extensible.

---

# Testing Strategy

The project uses **Pest** for automated testing.

Pest was chosen because it provides a clean, expressive syntax while remaining fully compatible with PHPUnit.

The test suite covers:

* Price sorting
* Sales-per-view sorting
* Date sorting
* Ascending and descending directions
* Chained sorting
* Zero-view products
* Invalid direction input
* Registry lookups

These tests verify both the expected functionality and important edge cases.

---

# Conclusion

This solution was designed to prioritize extensibility, maintainability, and testability.

By combining the Strategy Pattern, Composite Pattern, Registry Pattern, and SOLID principles, the sorting engine can support new business requirements without requiring changes to the core catalog implementation.
