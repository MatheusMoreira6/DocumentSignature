<?php

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', base_url());
define('APP_KEY', '{RANDOM_STRING_32_BYTES}'); // php -r "echo base64_encode(random_bytes(32)) . PHP_EOL;"
