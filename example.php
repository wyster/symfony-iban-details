<?php

declare(strict_types=1);

use IbanDetails\IbanDetailsFactory;

require __DIR__ . '/vendor/autoload.php';

$validIBAN = 'GB33BUKB20201555555555';
$ibanDetails = IbanDetailsFactory::create()($validIBAN);
var_dump($ibanDetails);