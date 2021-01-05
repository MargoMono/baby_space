<?php

namespace App\Components;

use App\Components\Vendors\SxGeo;
use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;

class Country
{
    /**
     * @var mixed
     */
    private $language;

    /**
     * @var CountryRepository
     */
    private $countryRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->countryRepository = new CountryRepository();
    }

    public function setCountry($countryCode = null)
    {
        $countryCode = $countryCode ?? $_SESSION['country']['alpha2'];

        $country = $this->countryRepository->getByParams([
            'alpha2' => $countryCode,
            'language_id' => $this->language['id']
        ]);

        $_SESSION['country'] = $country;
    }

    public function getCountry()
    {
        if (!empty($_SESSION['country'])) {
           return $_SESSION['country'];
        }

        if(filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $SxGeo = new SxGeo('SxGeo.dat', SXGEO_BATCH | SXGEO_MEMORY);
        $countryCode = $SxGeo->getCountry($ip);
        $country = $this->countryRepository->getByParams([
            'alpha2' => $countryCode,
            'language_id' => $this->language['id']
        ]);

        $_SESSION['country'] = $country;

        return $country;
    }
}
