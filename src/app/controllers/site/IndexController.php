<?php

namespace App\Controller\Site;

use App\Components\Language;
use App\Controller\Controller;
use App\Model\Site\IndexModel;

class IndexController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new IndexModel();
    }

    public function showMainPage()
    {
        $data = $this->model->getMainPageData();
        $data['page'] = 'main';
        $this->view->generate('/site/index.twig', $data);
    }

    public function actionChangeLanguage()
    {
        $language = new Language();
        $language->setLanguage($_POST['language']);
        header('Location: ' .$_SERVER['HTTP_REFERER']);
    }
}
