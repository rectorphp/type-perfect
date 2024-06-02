# Type Perfect

[![Downloads](https://img.shields.io/packagist/dt/rector/type-perfect.svg?style=flat-square)](https://packagist.org/packages/rector/type-perfect/stats)

Next level type declaration check PHPStan rules.

We use these sets to improve code quality of our clients' code beyond PHPStan features.

* These rules make skipped object types explicit, param types narrow and help you to fill more accurate object type hints.
* They're easy to enable, even if your code does not pass level 0
* They're effortless to resolve.

If you care about code quality and type safety, add these rules to your CI.

<br>

## Install

```bash
composer require rector/type-perfect --dev
```

*Note: Make sure you use [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer#usage) to load necessary service configs.*

<br>

There are 2 checks enabled out of the box. First one makes sure we don't miss a chance to use `instanceof` to make further code know about exact object type:

```php
private ?SomeType $someType = null;

if (! empty($this->someType)) {
    // ...
}

if (! isset($this->someType)) {
    // ...
}

// here we only know, that $this->someType is not empty/null
```

:no_good:

↓


```php
if (! $this->someType instanceof SomeType) {
    return;
}

// here we know $this->someType is exactly SomeType
```

:heavy_check_mark:

<br>

Second rule checks we use explicit object methods over magic array access:

```php
$article = new Article();

$id = $article['id'];
// we have no idea, what the type is
```
:no_good:

↓

```php
$id = $article->getId();
// we know the type is int
```

:heavy_check_mark:

<br>

## Configure

Next rules you can enable by configuration:

```yaml
parameters:
    type_coverage:
        narrow_param: true
        no_mixed: true
        null_over_false: true
```

## 1. Narrow Param Types

```php

```

## 2. No mixed Caller

```php

```

## 3. Null over False

```php

```




Add sets one by one, fix what you find useful and ignore the rest.

<br>

Happy coding!
