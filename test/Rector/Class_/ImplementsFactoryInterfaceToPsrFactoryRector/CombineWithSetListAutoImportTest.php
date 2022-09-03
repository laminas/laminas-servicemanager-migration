<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class CombineWithSetListAutoImportTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureCombineWithSetListAutoImport');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_combine_with_set_list_auto_import.php';
    }
}
