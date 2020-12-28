<?php

namespace App\Components;

use App\API\CBRAgent;
use App\Repository\CurrencyRepository;
use App\Repository\RateRepository;
use DateTime;
use PHPUnit\Util\Exception;

class Rates
{
    private $currencyRepository;
    private $rateRepository;

    public function __construct()
    {
        $this->currencyRepository = new CurrencyRepository();
        $this->rateRepository = new RateRepository();
    }

    public function getRates()
    {
        $rateList = $this->rateRepository->getAll();

        if (empty($rateList)) {
            $this->createRates();
        }

        $now = new DateTime();

        foreach ($rateList as $rate) {
            try {
                $date = new DateTime($rate['date']);
            } catch (\Exception $e) {
                throw new Exception('Convert problem');
            }
            if ($now->diff($date)->d > 0) {
                $this->updateRates($rate, $now->format('Y-m-d'));
            }
        }

        return $this->rateRepository->getAll();
    }

    public function createRates()
    {
        $cbr = new CBRAgent();

        $currencyList = $this->currencyRepository->getAllCurrencyForConvert();

        foreach ($currencyList as $currency) {
            if (!$cbr->load()) {
                throw new Exception('Convert problem');
            }
            $rate = $cbr->get($currency['code']);
            $now = new DateTime();
            $this->rateRepository->create([
                'currency_id' => $currency['id'],
                'rate' => $rate,
                'date' => $now->format('Y-m-d')
            ]);
        }
    }

    public function updateRates($rate, $date)
    {
        $cbr = new CBRAgent();

        if (!$cbr->load()) {
            throw new Exception('Convert problem');
        }

        $newRate = $cbr->get($rate['code']);

        $this->rateRepository->updateById([
            'currency_id' => $rate['currency_id'],
            'rate' => $newRate,
            'date' => $date,
            'id' => $rate['id']
        ]);
    }
}

