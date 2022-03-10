<?php
/** @testCase */

declare(strict_types=1);

namespace Tests\IO;

use Redbitcz\Utils\IO\BufferWriter;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class BufferWriterTest extends TestCase
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
        $writer = new BufferWriter();

        $writer->write($testData);
        $writer->error('noob');

        Assert::equal($testData, $writer->getOutputStreamContent());
    }

    /**
     * @dataProvider getTestOutputData
     * @param string $testData
     */
    public function testError(string $testData): void
    {
        $writer = new BufferWriter();

        $writer->write('noob');
        $writer->error($testData);

        Assert::equal($testData, $writer->getErrorStreamContent());
    }
}

(new BufferWriterTest)->run();
