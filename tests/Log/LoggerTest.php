<?php
/** @testCase */

declare(strict_types=1);

namespace Tests\Log;

use Redbitcz\Utils\Log\Logger;
use Tester\Assert;
use Tester\TestCase;
use Tests\WriterMock;

require __DIR__ . '/../../vendor/autoload.php';

class LoggerTest extends TestCase
{
    /**
     * @return string[][]
     */
    public function getTestLogData(): array
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
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testLog(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->log('testLog', $testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] TESTLOG:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testEmergency(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->emergency($testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] EMERGENCY:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testAlert(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->alert($testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] ALERT:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testCritical(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->critical($testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] CRITICAL:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testError(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->error($testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] ERROR:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testWarning(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->warning($testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] WARNING:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testNotice(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->notice($testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] NOTICE:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testInfo(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->info($testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] INFO:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @dataProvider getTestLogData
     * @param string $testData
     */
    public function testDebug(string $testData): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->debug($testData);

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] DEBUG:~', $output);
        Assert::contains($testData . "\n", $output);
    }

    /**
     * @return array[]
     */
    public function getTestInterpolationData(): array
    {
        $interpolableClass = new class {
            public function __toString(): string
            {
                return 'bar';
            }
        };

        return [
            ['', '', ['foo' => 'bar']],
            ['Hello foo!', 'Hello foo!', ['foo' => 'bar']],
            ['Hello bar!', 'Hello {foo}!', ['foo' => 'bar']],
            ['Hello {foo}!', 'Hello {foo}!', ['bar' => 'baz']],
            ['Hello {foo}!', 'Hello {foo}!', ['foo' => ['bar']]],
            ['Hello {foo}!', 'Hello {foo}!', ['foo' => ['bar']]],
            ['Hello {foo}!', 'Hello {foo}!', ['foo' => (object)['bar']]],
            ['Hello bar!', 'Hello {foo}!', ['foo' => $interpolableClass]],
            ['bar', $interpolableClass, ['foo' => 'bar']],
            ['1', 1, ['foo' => 'bar']],
            ['1.1', 1.1, ['foo' => 'bar']],
            ['1', true, ['foo' => 'bar']],
            ['', false, ['foo' => 'bar']],
            ['', null, ['foo' => 'bar']],
        ];
    }

    /**
     * @dataProvider getTestInterpolationData
     * @param string $expected
     * @param mixed $message
     * @param mixed[] $context
     */
    public function testInterpolation(string $expected, $message, array $context): void
    {
        $mock = new WriterMock();

        $logger = new Logger($mock->getWriter());

        $logger->log('testInterpolation', $message, $context);

        $output = $mock->getOutputStreamContent();

        Assert::contains('] TESTINTERPOLATION: ' . $expected . "\n", $output);
    }
}

(new LoggerTest)->run();
