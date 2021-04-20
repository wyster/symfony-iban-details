<?php

declare(strict_types=1);

namespace IbanDetails\Provider;

use IbanDetails\IbanDetails;
use IbanDetails\IbanDetailsEntity;

interface ProviderInterface
{
    public function getDetails(string $iban): IbanDetailsEntity;
}