<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CurrencyPairExchange;
use App\Exception\UpdateRatesException;
use App\Repository\CurrencyPairExchangeRepository;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyRatesService
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct(
        private readonly CurrencyRepository             $currencyRepository,
        private readonly CurrencyPairExchangeRepository $currencyPairExchangeRepository,
        private readonly HttpClientInterface            $httpClient,
        private readonly EntityManagerInterface         $entityManager,
        #[Autowire(env: "X_COIN_API_KEY")] string       $apiKey,
        #[Autowire(env: "X_COIN_API_URL")] string       $apiUrl
    ) {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    public function updateRates(string $baseTag, string $quoteTag): void
    {
        $base = $this->currencyRepository->getByTag($baseTag);
        $quote = $this->currencyRepository->getByTag($quoteTag);

        try {
            $response = $this->httpClient->request("GET", $this->apiUrl . $baseTag . "/" . $quoteTag, [
                "headers" => [
                    "X-CoinAPI-Key" => $this->apiKey,
                ],
            ]);
        } catch (TransportExceptionInterface $e) {
            throw new UpdateRatesException(
                "Unable to update currency pair exchange data.",
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $data = $response->toArray();
        $stringRate = sprintf("%.20f", $data["rate"]);

        $exchange = new CurrencyPairExchange($base, $quote, $stringRate);
        $this->currencyPairExchangeRepository->save($exchange, true);
    }
}