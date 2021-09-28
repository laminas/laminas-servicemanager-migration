<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector;

use Iterator;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class AutoImportTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImport');
    }

    public function provideConfigFilePath(): string
    {
        return SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT;
    }
}
