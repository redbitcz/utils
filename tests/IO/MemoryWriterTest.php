<?php
/** @testCase */

declare(strict_types=1);

namespace Tests\IO;

use Redbitcz\Utils\IO\MemoryWriter;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class MemoryWriterTest extends TestCase
{
    /**
     * @return string[][]
     */
    public function getTestOutputData(): array
    {
        return [
            ['Hello'],
            ['Lorem ipsum'],
            ["Lorem ipsum\n"],
            ["Lorem ipsum\ntest"],
            [""],
            ["\n"],
            ["\n\n"],
        ];
    }

    /**
     * @dataProvider getTestOutputData
     * @param string $testData
     */
    public function testOutput(string $testData): void
    {
        $writer = new MemoryWriter();

        $writer->write($testData);
        $writer->error('noob');

        Assert::equal($testData, $writer->getOutputContent());
        Assert::equal('noob', $writer->getErrorContent());
    }

    /**
     * @dataProvider getTestOutputData
     * @param string $testData
     */
    public function testError(string $testData): void
    {
        $writer = new MemoryWriter();

        $writer->write('noob');
        $writer->error($testData);

        Assert::equal('noob', $writer->getOutputContent());
        Assert::equal($testData, $writer->getErrorContent());
    }

    public function testEmpty(): void
    {
        $writer = new MemoryWriter();

        Assert::equal('', $writer->getOutputContent());
        Assert::equal('', $writer->getErrorContent());
    }

    public function testHasContent(): void
    {
        $writer = new MemoryWriter();

        Assert::equal(false, $writer->hasOutputContent());
        Assert::equal(false, $writer->hasErrorContent());

        $writer->write('foo');
        $writer->error('bar');

        Assert::equal(true, $writer->hasOutputContent());
        Assert::equal(true, $writer->hasErrorContent());
    }
}

(new MemoryWriterTest)->run();
