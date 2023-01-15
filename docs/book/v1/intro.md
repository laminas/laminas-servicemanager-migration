# laminas-servicemanager-migration

## Using with Rector

To use with rector, you can create the following `rector.php`:

```php
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::LAMINAS_SERVICEMANGER_40]);
    $rectorConfig->paths([__DIR__ . '/module']);
};
```

Rector works with all class names as fully qualified by default.In the most projects, that's not a desired behavior, because short version with use statement is easier to read.

To import FQCN, configure rector.php with:

```php
$rectorConfig->importNames();
```

If you want to make renamed class type hint to use short name with import its fully qualified ot use statement, you may use `SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT` set list that utilize `$rectorConfig->importNames()` on it, so the `rector.php` config will be as follow:

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
