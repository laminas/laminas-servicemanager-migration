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

<!-- markdownlint-disable MD033 -->
<pre class="language-php" data-line="8-11"><code>
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::LAMINAS_SERVICEMANGER_40]);
    $rectorConfig->paths([__DIR__ . '/module']);

     // register ImplementsFactoryInterfaceToPsrFactoryRector service
    $rectorConfig->rule(
        \Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector::class
    );
};
</code></pre>
<!-- markdownlint-restore -->

If we want to auto import, we can use `LAMINAS_SERVICEMANGER_40_AUTO_IMPORT`:

<!-- markdownlint-disable MD033 -->
<pre class="language-php" data-line="5"><code>
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT]);
    $rectorConfig->paths([__DIR__ . '/module']);

     // register ImplementsFactoryInterfaceToPsrFactoryRector service
    $rectorConfig->rule(
        \Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector::class
    );
};
</code></pre>
<!-- markdownlint-restore -->

After configuration in place, we can run:

```bash
vendor/bin/rector process --dry-run
```

Ensure that the change is correct, if everything ok, we can run the fix:

```bash
vendor/bin/rector process
```

## Additional Adjustment

### Add Return Type to Factory

To add return type by new instance creation, the standard Rector rule [`ReturnTypeFromReturnNewRector`](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#returntypefromreturnnewrector) can be used.

Register the rule in `rector.php`:

<!-- markdownlint-disable MD033 -->
<pre class="language-php" data-line="11"><code>
use Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT]);
    $rectorConfig->paths([__DIR__ . '/module']);

    $rectorConfig->rule(ImplementsFactoryInterfaceToPsrFactoryRector::class);
    $rectorConfig->rule(ReturnTypeFromReturnNewRector::class);
};
</code></pre>
<!-- markdownlint-restore -->

Result:

<!-- markdownlint-disable MD033 -->
<pre class="language-php" data-line="7"><code>
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
</code></pre>
<!-- markdownlint-restore -->

### Sort Use Statements

To sort the `use` statements for the factory a coding style tool can be used, for example: [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) with [Slevomat Coding Standard](https://github.com/slevomat/coding-standard#slevomatcodingstandardnamespacesalphabeticallysorteduses-).
