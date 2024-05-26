# Type Perfect

[![Downloads](https://img.shields.io/packagist/dt/rector/type-perfect.svg?style=flat-square)](https://packagist.org/packages/rector/type-perfect/stats)

Next level type declaration check PHPStan rules.

<br>

## Install

```bash
composer require rector/type-perfect --dev
```

*Note: Make sure you use [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer#usage) to load necessary service configs.*

<br>

@todo enable by configuration

```yaml
parameters:
    type_coverage:
        narrow: false
        no_mixed: false
        no_falsy_return: false
```

Add sets one by one, fix what you find useful and ignore the rest.

<br>

Happy coding!
