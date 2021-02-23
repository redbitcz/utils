<?php

declare(strict_types=1);

namespace Tests\IO;

use Redbitcz\Utils\IO\FileWriter;
use Tester\Assert;
use Tester\TestCase;
use Tests\FileUtils;

require __DIR__ . '/../../vendor/autoload.php';

class FileWriterTest extends TestCase
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
        $outputStream = FileUtils::createMemoryStream();
        $errorStream = FileUtils::createMemoryStream();

        $writer = new FileWriter($outputStream, $errorStream);
        $writer->write($testData);
        $writer->error('noob');

        $content = FileUtils::getStreamContent($outputStream);

        Assert::equal($testData, $content);
    }

    /**
     * @dataProvider getTestOutputData
     * @param string $testData
     */
    public function testError(string $testData): void
    {
        $outputStream = FileUtils::createMemoryStream();
        $errorStream = FileUtils::createMemoryStream();

        $writer = new FileWriter($outputStream, $errorStream);
        $writer->write('noob');
        $writer->error($testData);

        $content = FileUtils::getStreamContent($errorStream);

        Assert::equal($testData, $content);
    }
}

(new FileWriterTest)->run();
