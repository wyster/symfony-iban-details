<?php

declare(strict_types=1);

namespace IbanDetails\Provider;

use IbanDetails\IbanDetailsEntity;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IbanCodes implements ProviderInterface
{
    private const URI = 'https://iban.codes/validate/%s';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDetails(string $iban): IbanDetailsEntity
    {
        $response = $this->client->request('GET', sprintf(self::URI, $iban));
        $content = $response->getContent();
        $t = new Crawler($content);
        $resultNode = $t->filterXPath('//legend[contains(., "Result")]');

        $entity = new IbanDetailsEntity();
        if (!$resultNode->count()) {
            throw new \RuntimeException('Result node not found');
        }

        $resultContent = $resultNode->closest('fieldset')->html();

        $entity->isValid = str_contains($content, 'This is a valid IBAN');
        $entity->iban = $iban;
        $bicMatches = [];
        preg_match('/BIC:<\/b>\s([a-z0-9]+)/ui', $resultContent, $bicMatches);
        if (count($bicMatches)) {
            $entity->bic = strip_tags($bicMatches[1]);
        }
        $bankNameMatches = [];
        preg_match('/Bank:<\/b>\s(.*?)<\/p>/ui', $resultContent, $bankNameMatches);
        if (count($bankNameMatches)) {
            $entity->bankName = $bankNameMatches[1];
        }

        return $entity;
    }
}