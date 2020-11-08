<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\BlogStrategy;
use App\Model\Admin\Context;

class BlogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->context =  new Context(new BlogStrategy());

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $data['newList'] = $this->context->getIndexData($_POST['order']);

        $this->view->generate('admin/blog/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $data['newList'] = $this->context->getShowCreatePageData();

        $this->view->generate('admin/blog/create.twig', $data);
    }

    public function create()
    {
        $data = $this->context->create($_FILES['file'], $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/blog/create.twig', $data);
            return;
        }

        header('Location: /admin/blog');
    }

    public function actionShowUpdatePage($id)
    {
        $data['new'] = $this->context->getShowUpdatePageData($id);

        $this->view->generate('admin/blog/update.twig', $data);
    }

    public function update()
    {
        $data = $this->context->update($_FILES['file'], $_POST);
        $data['new'] = $this->context->getShowUpdatePageData($_POST['id']);

        if ($data['errors']) {
            $this->view->generate('admin/blog/update.twig', $data);
            return;
        }

        header('Location: /admin/blog');
    }

    public function actionShowDeletePage($id)
    {
        $data['new'] = $this->context->getShowDeletePageData($id);

        $this->view->generate('admin/blog/delete.twig', $data);
    }

    public function delete()
    {
        $data = $this->context->delete($_POST);
        $data['new'] = $this->context->getShowDeletePageData($_POST['id']);

        if ($data['errors']) {
            $this->view->generate('admin/blog/delete.twig', $data);
            return;
        }

        header('Location: /admin/blog');
    }
}
