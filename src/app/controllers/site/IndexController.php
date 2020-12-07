<?php

namespace App\Controllers\Site;

use App\Components\Language;
use App\Controllers\Controller;
use App\Models\Site\IndexModel;

class IndexController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new IndexModel();
    }

    public function showHomePage()
    {
        $data = $this->model->getHomePageData();
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
