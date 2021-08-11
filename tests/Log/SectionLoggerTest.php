<?php
/** @testCase */

declare(strict_types=1);

namespace Tests\Log;

use Redbitcz\Utils\Log\Logger;
use Tester\Assert;
use Tester\TestCase;
use Tests\WriterMock;

require __DIR__ . '/../../vendor/autoload.php';

class SectionLoggerTest extends TestCase
{
    public function testSection(): void
    {
        $mock = new WriterMock();

        $logger = (new Logger($mock->getWriter()))->section('foo');

        $logger->log('testSection', 'bar');

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] TESTSECTION: \\{foo\\} bar$~', $output);
    }

    public function testSubSection(): void
    {
        $mock = new WriterMock();

        $logger = (new Logger($mock->getWriter()))->section('foo')->section('bar');

        $logger->log('testSection', 'baz');

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] TESTSECTION: \\{foo/bar\\} baz$~', $output);
    }

    public function testSubSectionSeparator(): void
    {
        $mock = new WriterMock();

        $logger = (new Logger($mock->getWriter()))->section('foo', ' > ')->section('bar');

        $logger->log('testSection', 'baz');

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] TESTSECTION: \\{foo > bar\\} baz$~', $output);
    }

    public function testSubSectionSubSeparator(): void
    {
        $mock = new WriterMock();

        $logger = (new Logger($mock->getWriter()))->section('foo')->section('bar', ' > ');

        $logger->log('testSection', 'baz');

        $output = $mock->getOutputStreamContent();

        Assert::match('~^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] TESTSECTION: \\{foo > bar\\} baz$~', $output);
    }

}

(new SectionLoggerTest)->run();
