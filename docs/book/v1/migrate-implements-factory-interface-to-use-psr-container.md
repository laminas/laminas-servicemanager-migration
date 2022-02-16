# Migrate Class Implements FactoryInterface to use Psr Container

To migrate existing factory classes that implements `Laminas\ServiceManager\Factory\FactoryInterface`, there is a Rector rule that can be used to migrate for that. For example, we have the following factory class:

```php
use My\Service;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Service();
    }
}
```

We need to migrate to :

```php
use My\Service;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ServiceFactory
{
    public function __invoke(\Psr\Container\ContainerInterface $container)
    {
        return new Service();
    }
}
```

The steps to apply the changes are:

- Remove the interface from Factory class
- Replace `invoke__()` class method param and the use of Interop class in the class

To apply that, we can register rector rule: `Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector` to our `rector.php` as an individual service:

```php
use Rector\Core\Configuration\Option;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::LAMINAS_SERVICEMANGER_40);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/module']);

     // register ImplementsFactoryInterfaceToPsrFactoryRector service
    $services = $containerConfigurator->services();
    $services->set(
        \Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector::class
    );
};
```

If we want to auto import, we can use `LAMINAS_SERVICEMANGER_40_AUTO_IMPORT`:

```php
use Rector\Core\Configuration\Option;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/module']);

    // register ImplementsFactoryInterfaceToPsrFactoryRector service
    $services = $containerConfigurator->services();
    $services->set(
        \Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector::class
    );
};
```

After configuration in place, we can run:

```bash
vendor/bin/rector process --dry-run
```

Ensure that the change is correct, if everything ok, we can run the fix:

```bash
vendor/bin/rector process
```

## Additional Adjustment

- Add Return type for the service, for example:

```php
use My\Service;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ServiceFactory
{
    public function __invoke(ContainerInterface $container): Service
    {
        return new Service();
    }
}
```

For add return type by new instance creation, we can use [`ReturnTypeFromReturnNewRector` TypeDeclaration rule](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#returntypefromreturnnewrector).

- Sort use statement, to sort it, we may require coding style tool for it, for example: [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) or [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)