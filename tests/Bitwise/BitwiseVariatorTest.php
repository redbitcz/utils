<?php
/** @testCase */

declare(strict_types=1);

namespace Tests\Bitwise;

use Redbitcz\Utils\Bitwise\BitwiseVariator;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class BitwiseVariatorTest extends TestCase
{
    public function getVariationsData(): array
    {
        return [
            [0b0000, [0b0000]],
            [0b0001, [0b0000, 0b0001]],
            [0b0010, [0b0000, 0b0010]],
            [0b0011, [0b0000, 0b0001, 0b0010, 0b0011]],
            [0b1001, [0b0000, 0b0001, 0b1000, 0b1001]],
        ];
    }

    /**
     * @dataProvider getVariationsData
     * @param int $bits
     * @param int[] $variations
     */
    public function testVariations(int $bits, array $variations): void
    {
        Assert::equal($variations, BitwiseVariator::create($bits)->variate());
    }

    public function getVariationsFilterMustData(): array
    {
        return [
            [0b0000, 0b0000, [0b0000]],
            [0b0011, 0b0000, [0b0000, 0b0001, 0b0010, 0b0011]],
            [0b0011, 0b0010, [0b0010, 0b0011]],
            [0b0011, 0b1010, [0b0010, 0b0011]], // Filter out of bits
        ];
    }

    /**
     * @dataProvider getVariationsFilterMustData
     * @param int $bits
     * @param int $must
     * @param int[] $variations
     */
    public function testVariationsFilterMust(int $bits, int $must, array $variations): void
    {
        Assert::equal($variations, BitwiseVariator::create($bits)->must($must)->variate());
    }

    public function getVariationsFilterCombinedMustData(): array
    {
        return [
            [0b11011, 0b00001, 0b00010, 0b01000, [0b01011, 0b11011]],
            [0b00011, 0b00000, 0b00000, 0b00000, [0b00000, 0b00001, 0b00010, 0b00011]],
            [0b00011, 0b00010, 0b00010, 0b00010, [0b00010, 0b00011]],
            [0b00011, 0b00010, 0b00010, 0b00100, [0b00010, 0b00011]], // Filter out of bits
        ];
    }

    /**
     * @dataProvider getVariationsFilterCombinedMustData
     * @param int $bits
     * @param int $must1
     * @param int $must2
     * @param int $must3
     * @param int[] $variations
     */
    public function testVariationsFilterCombinedMust(
        int $bits,
        int $must1,
        int $must2,
        int $must3,
        array $variations
    ): void {
        Assert::equal($variations, BitwiseVariator::create($bits)->must($must1)->must($must2)->must($must3)->variate());
    }

    public function getVariationsFilterMustNotData(): array
    {
        return [
            [0b0000, 0b0000, [0b0000]],
            [0b0011, 0b0000, [0b0000, 0b0001, 0b0010, 0b0011]],
            [0b0011, 0b0010, [0b0000, 0b0001]],
            [0b0011, 0b1010, [0b0000, 0b0001]], // Filter out of bits
        ];
    }

    /**
     * @dataProvider getVariationsFilterMustNotData
     * @param int $bits
     * @param int $mustNot
     * @param int[] $variations
     */
    public function testVariationsFilterMustNot(int $bits, int $mustNot, array $variations): void
    {
        Assert::equal($variations, BitwiseVariator::create($bits)->mustNot($mustNot)->variate());
    }

    public function getVariationsFilterCombinedMustNotData(): array
    {
        return [
            [0b11011, 0b00001, 0b00010, 0b01000, [0b00000, 0b10000]],
            [0b00011, 0b00000, 0b00000, 0b00000, [0b00000, 0b00001, 0b00010, 0b00011]],
            [0b00011, 0b00010, 0b00010, 0b00010, [0b00000, 0b00001]],
            [0b00011, 0b00010, 0b00010, 0b00100, [0b00000, 0b00001]], // Filter out of bits
        ];
    }

    /**
     * @dataProvider getVariationsFilterCombinedMustNotData
     * @param int $bits
     * @param int $mustNot1
     * @param int $mustNot2
     * @param int $mustNot3
     * @param int[] $variations
     */
    public function testVariationsFilterCombinedMustNot(
        int $bits,
        int $mustNot1,
        int $mustNot2,
        int $mustNot3,
        array $variations
    ): void {
        Assert::equal(
            $variations,
            BitwiseVariator::create($bits)->mustNot($mustNot1)->mustNot($mustNot2)->mustNot($mustNot3)->variate()
        );
    }

    /**
     * @return int[][]
     */
    public function getVariationGeneratorData(): array
    {
        return [
            [1, 0b0000],
            [2, 0b0001],
            [4, 0b0011],
            [8, 0b1011],
        ];
    }

    /**
     * @dataProvider getVariationGeneratorData
     * @param int $bits
     * @param int $variationsCount
     */
    public function testVariationGenerator(int $variationsCount, int $bits): void
    {
        $count = 0;
        foreach (BitwiseVariator::create($bits)->variate() as $variate) {
            $count++;
        }

        Assert::same($variationsCount, $count);
    }

}

(new BitwiseVariatorTest)->run();
