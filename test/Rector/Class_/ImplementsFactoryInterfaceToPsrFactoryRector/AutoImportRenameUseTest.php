<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class AutoImportRenameUseTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImportRenameUse');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_auto_import_rename_use.php';
    }
}
