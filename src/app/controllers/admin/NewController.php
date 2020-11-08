<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\Context;
use App\Model\Admin\NewStrategy;

class NewController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->context =  new Context(new NewStrategy());

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $data['newList'] = $this->context->getIndexData($_POST['order']);

        $this->view->generate('admin/new/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $data['newList'] = $this->context->getShowCreatePageData();

        $this->view->generate('admin/new/create.twig', $data);
    }

    public function create()
    {
        $data = $this->context->create($_FILES, $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/new/create.twig', $data);
            return;
        }

        header('Location: /admin/new');
    }

    public function actionShowUpdatePage($id)
    {
        $data['new'] = $this->context->getShowUpdatePageData($id);

        $this->view->generate('admin/new/update.twig', $data);
    }

    public function update()
    {
        $data = $this->context->update($_FILES, $_POST);
        $data['new'] = $this->context->getShowUpdatePageData($_POST['id']);

        if ($data['errors']) {
            $this->view->generate('admin/new/update.twig', $data);
            return;
        }

        header('Location: /admin/new');
    }

    public function actionShowDeletePage($id)
    {
        $data['new'] = $this->context->getShowDeletePageData($id);

        $this->view->generate('admin/new/delete.twig', $data);
    }

    public function delete()
    {
        $data = $this->context->delete($_POST);
        $data['new'] = $this->context->getShowDeletePageData($_POST['id']);

        if ($data['errors']) {
            $this->view->generate('admin/new/delete.twig', $data);
            return;
        }

        header('Location: /admin/new');
    }
}
