<?php

declare(strict_types=1);

namespace IbanDetails\Tests\Provider;

use IbanDetails\Provider\IbanCodes;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IbanCodesTest extends TestCase
{
    public const VALID_GB_IBAN = 'GB33BUKB20201555555555';

    private HttpClientInterface|MockObject $httpClient;
    private IbanCodes $ibanCodes;

    public function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->ibanCodes = new IbanCodes($this->httpClient);
    }

    public function testGetDetails(): void
    {
        $iban = self::VALID_GB_IBAN;
        $content = file_get_contents(__DIR__ . "/fixtures/{$iban}.html");
        $responses = [
            new MockResponse($content)
        ];
        $httpClient = new MockHttpClient($responses);
        $ibanCodes = new IbanCodes($httpClient);

        $result = $ibanCodes->getDetails(self::VALID_GB_IBAN);
        $this->assertTrue($result->isValid);
        $this->assertSame('BUKBGB22', $result->bic);
        $this->assertSame('BARCLAYS BANK UK PLC', $result->bankName);
    }
}
