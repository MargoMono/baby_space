<?php

namespace App\Controllers\Site;

use App\Components\Cart;
use App\Components\Currency;
use App\Components\Language;
use App\Models\Site\IndexModel;

class IndexController
{
    private $directory = '';
    private $controllerContext;
    private $model;

    function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new IndexModel();
    }

    public function showHomePage()
    {
        $data = $this->model->getHomePageData();
        $this->controllerContext->render($data, 'index.twig');
    }

    public function actionChangeLanguage($languageAlias)
    {
        $language = new Language();
        $language->setLanguage($languageAlias);
        header('Location: ' .$_SERVER['HTTP_REFERER']);
    }

    public function actionChangeCurrency($currencyCode)
    {
        $currency = new Currency();
        $currency->setCurrency($currencyCode);
        header('Location: ' .$_SERVER['HTTP_REFERER']);
    }

}
