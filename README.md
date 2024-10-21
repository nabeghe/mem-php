# Mem (Simple Realtime Cache for PHP)

> A cool real-time cache using static variable that lets you group keys.

<hr>

## 🫡 Usage

### 🚀 Installation

You can install the package via composer:

```bash
composer require nabeghe/mem
```

<hr>

### Methods Syntax

```php
// Checks if a key exists in a cache group.
Mem::has(mixed $key, mixed $group = 'default'): bool

// Checks if a group exists.
Mem::hasGroup(mixed $group): bool

// Returns the value of a key from a group.
Mem::get(mixed $key, mixed $group = 'default', mixed $default = null): bool

// Changes the value of a key in a group.
Mem::set(mixed $key, mixed $value, mixed $group = 'default'): bool

// Deletes a key from a group.
Mem::del($key, $group = 'default'): bool

// Returns all groups and their keys.
Mem::all(): array

// Returns all keys and values of a group.
Mem::group($group = 'default'): array

// Returns the number of existing groups.
Mem::groupsCount(): int

// Clears the entire group.
Mem::drop($group = 'default'): bool

// Clears the entire cache.
Mem::reset(): bool

```

<hr>

## 📖 License

Copyright (c) Hadi Akbarzadeh

Licensed under the MIT license, see [LICENSE.md](LICENSE.md) for details.