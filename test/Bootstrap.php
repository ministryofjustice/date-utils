<?php

namespace DateUtilsTest;

$vendorDir = __DIR__ . '/../vendor';

if (file_exists($file = $vendorDir . '/autoload.php')) {
    require_once $file;
} else if (file_exists($file = './vendor/autoload.php')) {
    require_once $file;
} else {
    throw new \RuntimeException("Not found composer autoload");
}

date_default_timezone_set('Europe/London');
