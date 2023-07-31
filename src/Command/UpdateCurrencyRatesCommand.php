<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\CurrencyRepository;
use App\Service\CurrencyRatesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'update-currency-rates',
    description: 'Command to retrieve the rate for currency pairs.'
)]
class UpdateCurrencyRatesCommand extends Command
{
    public function __construct(
        private readonly CurrencyRatesService $currencyRatesService,
        private readonly CurrencyRepository $currencyRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $currencies = $this->currencyRepository->findAll();

        foreach ($currencies as $base) {
            foreach ($currencies as $quote) {
                if ($base->getIsCrypto() !== $quote->getIsCrypto()) {
                    $this->currencyRatesService->updateRates($base->getTag(), $quote->getTag());
                }
            }
        }

        $output->writeln('Currency exchange rates updated successfully.');

        return Command::SUCCESS;
    }
}
