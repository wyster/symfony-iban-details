<?php

declare(strict_types=1);

namespace IbanDetails\Tests\Provider;

use IbanDetails\IbanDetailsEntity;
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
    public const INVALID_GB_IBAN = 'GB03BUKB20201555555555';

    /**
     * @dataProvider getDetailsDataProvider
     */
    public function testGetDetails(IbanDetailsEntity $entity): void
    {
        $content = file_get_contents(__DIR__."/fixtures/{$entity->iban}.html");
        $responses = [
            new MockResponse($content),
        ];
        $httpClient = new MockHttpClient($responses);
        $ibanCodes = new IbanCodes($httpClient);

        $result = $ibanCodes->getDetails(self::VALID_GB_IBAN);
        static::assertSame($entity->isValid, $result->isValid);
        static::assertSame($entity->bic, $result->bic);
        static::assertSame($entity->bankName, $result->bankName);
    }

    public function getDetailsDataProvider(): iterable
    {
        $entity = new IbanDetailsEntity(self::VALID_GB_IBAN, true);
        $entity->bic = 'BUKBGB22';
        $entity->bankName = 'BARCLAYS BANK UK PLC';
        yield [$entity];

        $entity = new IbanDetailsEntity(self::INVALID_GB_IBAN, false);
        $entity->bic = 'BUKBGB22';
        $entity->bankName = 'BARCLAYS BANK UK PLC';
        yield [$entity];
    }
}
