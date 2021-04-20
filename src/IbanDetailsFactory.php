<?php

declare(strict_types=1);

namespace IbanDetails;

use IbanDetails\Provider\IbanCodes;
use Symfony\Component\HttpClient\HttpClient;

class IbanDetailsFactory
{
    public static function create(): IbanDetails
    {
        return new IbanDetails(new IbanCodes(HttpClient::create()));
    }
}