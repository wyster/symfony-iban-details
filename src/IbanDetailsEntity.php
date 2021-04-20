<?php

declare(strict_types=1);

namespace IbanDetails;

class IbanDetailsEntity
{
    public string $iban;
    public bool $isValid;
    public ?string $bic = null;
    public ?string $bankName = null;
}
