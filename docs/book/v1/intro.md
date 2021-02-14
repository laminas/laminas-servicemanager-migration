# laminas-servicemanager-migration

### Using with Rector

To use with rector, you can create the following `rector.php`:

```php
<?php
// rector.php
declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/module']);

    $parameters->set(Option::SETS, [
        SetList::LAMINAS_SERVICEMANGER_40,
    ]);
};
```

Above, the `Option::PATHS` is paths you want rector to run. After configuration in place, you can run:

```bash
vendor/bin/rector process --dry-run
```

Ensure that the change is correct, if everything ok, you can run the fix:

```bash
vendor/bin/rector process
```

