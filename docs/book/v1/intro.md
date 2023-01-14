# laminas-servicemanager-migration

## About Rector

Rector is a PHP tool that you can run on any PHP project to get instant upgrade or automated refactoring. It helps with PHP upgrade, framework upgrade and improves your code quality. Also it helps with type-coverage and help to get to the latest PHPStan level.

## Using with Rector

To use with Rector, you can create the following `rector.php`:

```php
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::LAMINAS_SERVICEMANGER_40]);
    $rectorConfig->paths([__DIR__ . '/module']);
};
```

If you want to make renamed class type hint auto import enabled, you may use `SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT` set list, so the `rector.php` config will be as follow:

```php
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT]);
    $rectorConfig->paths([__DIR__ . '/module']);
};
```

Above, the `Option::PATHS` is paths we want rector to run. After configuration in place, you can run:

```bash
vendor/bin/rector process --dry-run
```

Ensure that the change is correct, if everything ok, we can run the fix:

```bash
vendor/bin/rector process
```
