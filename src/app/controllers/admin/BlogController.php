<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\BlogStrategy;
use App\Models\Admin\Context;

class BlogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->context = new Context(new BlogStrategy());

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $data = $this->context->getIndexData($_POST['order']);
        $this->view->generate('admin/blog/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $data = $this->context->getShowCreatePageData();
        $this->view->generate('admin/blog/create.twig', $data);
    }

    public function create()
    {
        $data = $this->context->create($_FILES, $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/blog/create.twig', $data);
            return;
        }

        header('Location: /admin/blog');
    }

    public function actionShowUpdatePage($id)
    {
        $data  = $this->context->getShowUpdatePageData($id);
        $this->view->generate('admin/blog/update.twig', $data);
    }

    public function update()
    {
        $data = $this->context->update($_FILES, $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->context->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/blog/update.twig', $data);
            return;
        }

        header('Location: /admin/blog');
    }

    public function actionShowDeletePage($id)
    {
        $data = $this->context->getShowDeletePageData($id);
        $this->view->generate('admin/blog/delete.twig', $data);
    }

    public function delete()
    {
        $data = $this->context->delete($_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->context->getShowDeletePageData($_POST['id']));
            $this->view->generate('admin/blog/delete.twig', $data);
            return;
        }

        header('Location: /admin/blog');
    }
}
