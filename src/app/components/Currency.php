<?php

namespace App\Components;

use App\Repository\CurrencyRepository;

class Currency
{
    CONST DEFAUL_CURRENCY_CODE = 'RUB';

    public function setCurrency($currency)
    {
        $_SESSION['currency'] = $currency;
    }

    public function getCurrency()
    {
        if (empty($_SESSION['currency'])) {
            $this->setCurrency( self::DEFAUL_CURRENCY_CODE);
        }

        $currencyRepository = new CurrencyRepository();

        return $currencyRepository->getByCode($_SESSION['currency']);
    }
}

