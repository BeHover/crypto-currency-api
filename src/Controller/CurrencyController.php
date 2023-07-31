<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CurrencyDTO;
use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/cryptocurrency/currencies")]
class CurrencyController extends AbstractController
{
    public function __construct(
        private readonly CurrencyRepository $currencyRepository
    ) {
    }

    #[Route("", name: "get_all_currencies", methods: ["GET"])]
    public function getAllCurrencies(): JsonResponse
    {
        $currencies = $this->currencyRepository->findAll();
        $data = [];

        foreach ($currencies as $currency) {
            $data[] = new CurrencyDTO(
                $currency->getTag(),
                $currency->getName(),
                $currency->getIsCrypto()
            );
        }

        return new JsonResponse($data);
    }
}