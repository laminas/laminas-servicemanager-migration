# laminas-servicemanager-migration

## Using with Rector

To use with rector, you can create the following `rector.php`:

```php
use Rector\Core\Configuration\Option;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::LAMINAS_SERVICEMANGER_40);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/module']);
};
```

If you want to make renamed class type hint auto import enabled, you may use `SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT` set list, so the `rector.php` config will be as follow:

```php
use Rector\Core\Configuration\Option;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/module']);
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
