<?php

declare(strict_types=1);

namespace Tests\IO;

use Redbitcz\Utils\IO\FileWriter;
use Redbitcz\Utils\IO\OutputWriter;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class OutputWriterTest extends TestCase
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
        $writer = new OutputWriter();

        ob_start();
        $writer->write($testData);
        $content = ob_get_clean();

        Assert::equal($testData, $content);
    }

    /**
     * @dataProvider getTestOutputData
     * @param string $testData
     */
    public function testError(string $testData): void
    {
        $writer = new OutputWriter();

        ob_start();
        $writer->error($testData);
        $content = ob_get_clean();

        Assert::equal($testData, $content);
    }
}

(new FileWriterTest)->run();
