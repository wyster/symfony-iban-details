<?php

declare(strict_types=1);

namespace IbanDetails;

use IbanDetails\Provider\ProviderInterface;

class IbanDetails
{
    private ProviderInterface $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function __invoke(string $iban): IbanDetailsEntity
    {
        return $this->provider->getDetails($iban);
    }
}
