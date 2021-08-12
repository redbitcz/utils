<?php

declare(strict_types=1);

use Redbitcz\Utils\Lock\Exception\LockObtainException;
use Redbitcz\Utils\Lock\Locker;

require_once __DIR__ . '/../../vendor/autoload.php';

$tempDir = __DIR__ . '/temp';

if (!@mkdir($tempDir) && !is_dir($tempDir)) {
    throw new RuntimeException(sprintf('Directory "%s" was not created', $tempDir));
}

$locker = new Locker($tempDir, basename(__FILE__), Locker::NON_BLOCKING);

echo "== Example od Non-blocking locker ==\n\n";

echo "Locker is lock following code of script to 30 seconds, try to run same script from another PHP process.\n";

try {
    $locker->lock();

    echo "Locker is successfully locked.\n";

    for ($i = 0; $i < 30; $i++) {
        echo ".";
        sleep(1);
    }

    $locker->unlock();

    echo "\nLocker is unlocked. Done.\n";
} catch (LockObtainException $e) {
    echo 'Error: lock is alreasy locked by another process! ' . $e->getMessage();
}
