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

// Returns the first key of an item that matches the regex.
Mem::match($regex, $group = 'default'): ?string

// Returns items whose keys match the regex.
Mem::matches($regex, $group = 'default'): ?array

// Checks if a group exists.
Mem::hasGroup(mixed $group): bool

// Returns the value of a key from a group.
Mem::get(mixed $key, mixed $group = 'default', mixed $default = null): bool

// Changes the value of a key in a group.
Mem::set(mixed $key, mixed $value, mixed $group = 'default'): bool

// Deletes a key from a group.
Mem::del($key, $group = 'default'): bool

// Deletes items based on key matching with regex.
Mem::delMatches($regex, $group = 'default'): bool

// Returns all storages (groups) and their keys.
Mem::all(): array

// Returns all keys and values of a group.
Mem::group($group = 'default'): Storage

// Returns the number of existing groups.
Mem::groupsCount(): int

// Clears the entire group.
Mem::drop($group = 'default'): bool

// Clears the entire cache.
Mem::reset(): bool

```

<hr>

### Configuration

The `config` method is used for configuration, with the first argument being the group name & the second argument as an array to config that group.
If the second argument is `false` or not set, it returns the current config.

The `configProp` method retrieves a key's value from the config, with the key and group name as parameters.

The default config value is in `DEFAULT_CONFIG` const.

Currently, there's only one configuration, `length_limit`, with a default value of `-1`, which defines the group size.
When the item count exceeds this, the first item is removed.
`-1` means unlimited, also, `0` is currently not useful & should not be used.

```php
Mem::config('default', ['length_limit' => 3]);

Mem::set('item_1', 'value 1');
Mem::set('item_2', 'value 2');
Mem::set('item_3', 'value 3');
Mem::set('item_4', 'value 4');

/*
 * Items in the default group:
 *  [
 *      'item_2' => 'value 2',
 *      'item_3' => 'value 3',
 *      'item_4' => 'value 4'
 *  ]
 */
```

## 📖 License

Copyright (c) Hadi Akbarzadeh

Licensed under the MIT license, see [LICENSE.md](LICENSE.md) for details.