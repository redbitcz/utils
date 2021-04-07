<?php
/** @testCase */

declare(strict_types=1);

namespace Tests\IO;

use Tester\Assert;
use Tester\TestCase;
use Tests\WriterMock;

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
        $mock = new WriterMock();

        $writer = $mock->getWriter();
        $writer->write($testData);
        $writer->error('noob');

        Assert::equal($testData, $mock->getOutputStreamContent());
    }

    /**
     * @dataProvider getTestOutputData
     * @param string $testData
     */
    public function testError(string $testData): void
    {
        $mock = new WriterMock();

        $writer = $mock->getWriter();
        $writer->write('noob');
        $writer->error($testData);

        Assert::equal($testData, $mock->getErrorStreamContent());
    }
}

(new FileWriterTest)->run();
