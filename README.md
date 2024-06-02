# Type Perfect

[![Downloads](https://img.shields.io/packagist/dt/rector/type-perfect.svg?style=flat-square)](https://packagist.org/packages/rector/type-perfect/stats)

Next level type declaration check PHPStan rules.

We use these sets to improve code quality of our clients' code beyond PHPStan features.

These rules make skipped object types explicit, param types narrow and help you to fill more accurate object type hints. If you care about code quality and type safety, add these rules to your CI.

<br>

## Install

```bash
composer require rector/type-perfect --dev
```

*Note: Make sure you use [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer#usage) to load necessary service configs.*

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

## Narrow Param Types

```php

Add sets one by one, fix what you find useful and ignore the rest.

<br>

Happy coding!
