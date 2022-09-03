<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector;

use Iterator;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RenameClassRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return SetList::LAMINAS_SERVICEMANGER_40;
    }
}
