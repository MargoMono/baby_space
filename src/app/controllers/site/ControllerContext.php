<?php

namespace App\Controllers\Site;

use App\Components\Language;
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
        $data = array_merge($data, $this->getDefaultData());
        $this->view->generate("site/$this->viewDirectory/$page", $data);
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

        return $data;
    }
}