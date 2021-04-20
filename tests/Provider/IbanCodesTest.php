<?php

declare(strict_types=1);

namespace IbanDetails\Tests\Provider;

use IbanDetails\Provider\IbanCodes;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * @internal
 * @coversNothing
 */
final class IbanCodesTest extends TestCase
{
    public const VALID_GB_IBAN = 'GB33BUKB20201555555555';

    public function testGetDetails(): void
    {
        $iban = self::VALID_GB_IBAN;
        $content = file_get_contents(__DIR__."/fixtures/{$iban}.html");
        $responses = [
            new MockResponse($content),
        ];
        $httpClient = new MockHttpClient($responses);
        $ibanCodes = new IbanCodes($httpClient);

        $result = $ibanCodes->getDetails(self::VALID_GB_IBAN);
        static::assertTrue($result->isValid);
        static::assertSame('BUKBGB22', $result->bic);
        static::assertSame('BARCLAYS BANK UK PLC', $result->bankName);
    }
}
