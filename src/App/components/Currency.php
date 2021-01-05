<?php

namespace App\Components;

use App\Repository\CountryRepository;
use App\Repository\CurrencyRepository;

class Currency
{
    CONST DEFAUL_CURRENCY_ID = 1;

    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;

    public function __construct()
    {
        $this->currencyRepository = new CurrencyRepository();
    }

    public function setCurrency()
    {
        $currencyId = $_SESSION['country']['currency_id'] ?? self::DEFAUL_CURRENCY_ID;

        $currency = $this->currencyRepository->getByParams([
            'id' => $currencyId
        ]);

        $_SESSION['currency'] = $currency;
    }

    public function getCurrency()
    {
        if (!empty($_SESSION['currency'])) {
            return $_SESSION['currency'];
        }

        $this->setCurrency();

        return $_SESSION['currency'];
    }
}

