<?php

namespace App\Controllers\Site;

use App\Components\Currency;
use App\Components\Language;
use App\Repository\CategoryRepository;
use App\Repository\CurrencyRepository;
use App\Repository\LanguageRepository;
use App\View\View;

class ControllerContext
{
    /**
     * @var string
     */
    private $viewDirectory;

    /**
     * @var View
     */
    private $view;

    public function __construct($viewDirectory)
    {
        $this->viewDirectory = $viewDirectory;
        $this->view = new View();
    }

    public function render($data, $page)
    {
        if (!empty($data)){
            $data = array_merge($data, $this->getDefaultData());
        } else {
            $data = $this->getDefaultData();
        }

        $this->view->generate("site/$this->viewDirectory/$page", $data);
    }

    public function generateAjax($data = [])
    {
        echo json_encode($data);
    }

    private function getDefaultData()
    {
        $languagesRepository = new LanguageRepository();
        $data['languageList'] = $languagesRepository->getAll();

        $currencyRepository = new CurrencyRepository();
        $data['currencyList'] = $currencyRepository->getAll();

        $language = new Language();
        $language->setContent();
        $data['language'] = $languagesRepository->getByAlias($language->getLanguage());
        $data['content'] = $language->getContent();

        $currency = new Currency();
        $data['currency'] = $currency->getCurrency();

        $categoryRepository = new CategoryRepository();
        $data['categoryList'] = $categoryRepository->getAllAvailable($data['language']['id']);


        return $data;
    }
}