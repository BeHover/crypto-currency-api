<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CurrencyPairExchangeDTO;
use App\Exception\InvalidCurrencyPairException;
use App\Repository\CurrencyPairExchangeRepository;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyRatesService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/cryptocurrency")]
class CurrencyPairExchangeController extends AbstractController
{
    private const TAG_REGEX =  "/^[A-Z]{3,}$/";
    private const DATETIME_REGEX =  "/^\d{2}-\d{2}-\d{4},\d{2}:\d{2}:\d{2}$/";
    private const DATETIME_FORMAT_REGEX =  "d-m-Y,H:i:s";

    public function __construct(
        private readonly CurrencyRepository $currencyRepository,
        private readonly CurrencyPairExchangeRepository $currencyPairExchangeRepository,
        private readonly CurrencyRatesService $currencyRatesService
    ) {
    }

    #[Route("/rates", name: "get_rates_from_currency_pair", methods: ["GET"])]
    public function getDataFromCurrencyPair(
        #[MapQueryParameter(filter: FILTER_VALIDATE_REGEXP, options: ["regexp" => self::TAG_REGEX])] string $baseTag,
        #[MapQueryParameter(filter: FILTER_VALIDATE_REGEXP, options: ["regexp" => self::TAG_REGEX])] string $quoteTag,
        #[MapQueryParameter(filter: FILTER_VALIDATE_REGEXP, options: ["regexp" => self::DATETIME_REGEX])] string $from,
        #[MapQueryParameter(filter: FILTER_VALIDATE_REGEXP, options: ["regexp" => self::DATETIME_REGEX])] string $to
    ): JsonResponse
    {
        $base = $this->currencyRepository->getByTag($baseTag);
        $quote = $this->currencyRepository->getByTag($quoteTag);

        if ($base->getIsCrypto() === $quote->getIsCrypto()) {
            throw new InvalidCurrencyPairException(
                "The application does not provide values for this currency pair: " . $baseTag . "/" . $quoteTag,
                Response::HTTP_NOT_FOUND
            );
        }

        $fromDateTime = DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT_REGEX, $from);
        $toDateTime = DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT_REGEX, $to);

        $exchanges = $this->currencyPairExchangeRepository->getRatesByTimeData($base, $quote, $fromDateTime, $toDateTime);
        $data = [];

        foreach ($exchanges as $exchange) {
            $data[] = new CurrencyPairExchangeDTO(
                $exchange->getBase()->getTag(),
                $exchange->getBase()->getName(),
                $exchange->getQuote()->getTag(),
                $exchange->getQuote()->getName(),
                $exchange->getRate(),
                $exchange->getCreatedAt()
            );
        }

        return new JsonResponse($data);
    }
}