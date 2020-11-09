<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\Context;
use App\Model\Admin\PageModel;
use App\Model\Admin\PageStrategy;

class PageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new PageModel();
        $this->context = new Context(new PageStrategy());

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $data = $this->context->getIndexData($_POST['order']);

        $this->view->generate('admin/page/index.twig', $data);
    }

    public function actionShowUpdatePage($id)
    {
        $data['page'] = $this->context->getShowUpdatePageData($id);
        $this->view->generate('admin/page/update.twig', $data);
    }

    public function update()
    {
        $data = $this->model->update($_FILES, $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->context->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/page/update.twig', $data);
            return;
        }

        header('Location: /admin/page');
    }
}
