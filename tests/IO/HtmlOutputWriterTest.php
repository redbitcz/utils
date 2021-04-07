<?php
/** @testCase */

declare(strict_types=1);

namespace Tests\IO;

use Redbitcz\Utils\IO\HtmlOutputWriter;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class HtmlOutputWriterTest extends TestCase
{
    /**
     * @return string[][]
     */
    public function getTestOutputData(): array
    {
        return [
            ['Hello', 'Hello'],
            ['Lorem ipsum', 'Lorem ipsum'],
            ["Lorem ipsum\n", "Lorem ipsum<br />\n"],
            ["Lorem ipsum\ntest", "Lorem ipsum<br />\ntest"],
            ["", ""],
            ["\n", "<br />\n"],
            ["\n\n", "<br />\n<br />\n"],
            ['&"\'<>', '&amp;&quot;\'&lt;&gt;']
        ];
    }

    /**
     * @dataProvider getTestOutputData
     * @param string $testData
     * @param string $resultData
     */
    public function testOutput(string $testData, string $resultData): void
    {
        $writer = new HtmlOutputWriter();

        ob_start();
        $writer->write($testData);
        $content = ob_get_clean();

        Assert::equal($resultData, $content);
    }

    /**
     * @dataProvider getTestOutputData
     * @param string $testData
     * @param string $resultData
     */
    public function testError(string $testData, string $resultData): void
    {
        $writer = new HtmlOutputWriter();

        ob_start();
        $writer->error($testData);
        $content = ob_get_clean();

        Assert::equal($resultData, $content);
    }
}

(new HtmlOutputWriterTest)->run();
