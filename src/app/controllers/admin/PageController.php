<?php

namespace App\Controller\Admin;

use App\Components\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\BlogModel;
use App\Model\Admin\PageModel;

class PageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new PageModel();
    }

    public function actionIndex()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getIndexData($_POST['order']);

        $this->view->generate('admin/page/index.twig', $data);
    }

    public function actionShowUpdatePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowUpdatePageData($id);

        $this->view->generate('admin/page/update.twig', $data);
    }

    public function update()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->update($_FILES['file'], $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/page/update.twig', $data);
            return;
        }

        header("Location: /admin/page");
    }
}
