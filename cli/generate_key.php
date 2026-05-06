<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Defuse\Crypto\Key;

$key = Key::createNewRandomKey();

echo "ENCRYPTION_KEY=" . $key->saveToAsciiSafeString() . PHP_EOL;
