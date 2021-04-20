<?php

declare(strict_types=1);

namespace IbanDetails;

class IbanDetailsEntity
{
    public ?string $bic = null;
    public ?string $bankName = null;

    public function __construct(public string $iban, public bool $isValid)
    {
    }
}
