<?php
/** @testCase */

declare(strict_types=1);

namespace Tests\IO;

use LogicException;
use Redbitcz\Utils\IO\LazyStreamWriter;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class LazyStreamWriterTest extends TestCase
{
    public function testInvalidCallback(): void
    {
        Assert::exception(static function () {
            $writer = new LazyStreamWriter(function() {return null;}, function() {return null;});
            $writer->write('foo');
        }, LogicException::class);

        Assert::exception(static function () {
            $writer = new LazyStreamWriter(function() {return null;}, function() {return null;});
            $writer->error('foo');
        }, LogicException::class);
    }
}

(new LazyStreamWriterTest)->run();
