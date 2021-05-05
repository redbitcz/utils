<?php

declare(strict_types=1);

use Redbitcz\Utils\Bitwise\BitwiseVariator;

require_once __DIR__ . '/../../vendor/autoload.php';

echo "Variations of 1011 ===================================\n";
$variations = BitwiseVariator::create(0b1011)->variate();
foreach ($variations as $i => $variation) {
    echo sprintf("% 2s. variation: %04s (decimal: % 3s)\n", $i+1, decbin($variation), $variation);
}

echo "\nVariations of 1011 with bit 0010 =====================\n";
$variations = BitwiseVariator::create(0b1011)->must(0b0010)->variate();
foreach ($variations as $i => $variation) {
    echo sprintf("% 2s. variation: %04s (decimal: % 3s)\n", $i+1, decbin($variation), $variation);
}

echo "\nVariations of 1011 without bit 0010 ==================\n";
$variations = BitwiseVariator::create(0b1011)->mustNot(0b0010)->variate();
foreach ($variations as $i => $variation) {
    echo sprintf("% 2s. variation: %04s (decimal: % 3s)\n", $i+1, decbin($variation), $variation);
}



