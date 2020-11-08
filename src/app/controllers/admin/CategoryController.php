<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\CategoryStrategy;
use App\Model\Admin\Context;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->strategy = new CategoryStrategy();
        $this->context =  new Context(new CategoryStrategy());

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $data['categoryList'] = $this->context->getIndexData($_POST['order']);

        $this->view->generate('admin/category/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $data['categoryList'] = $this->context->getShowCreatePageData();

        $this->view->generate('admin/category/create.twig', $data);
    }

    public function createCategory()
    {
        $data = $this->context->create($_FILES, $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/category/create.twig', $data);
            return;
        }

        header('Location: /admin/category');
    }

    public function actionShowUpdatePage($id)
    {
        $data = $this->context->getShowUpdatePageData($id);

        $this->view->generate('admin/category/update.twig', $data);
    }

    public function updateCategory()
    {
        $data = $this->context->update($_FILES, $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->context->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/category/update.twig', $data);
            return;
        }

        header('Location: /admin/category');
    }

    public function actionShowDeletePage($id)
    {
        $data = $this->strategy->getShowDeletePageData($id);

        $this->view->generate('admin/category/delete.twig', $data);
    }

    public function deleteCategory()
    {
        $data = $this->context->delete($_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->strategy->getShowDeletePageData($_POST['id']));
            $this->view->generate('admin/category/delete.twig', $data);
            return;
        }

        header('Location: /admin/category');
    }

    public function photoDelete($id, $photoId)
    {
        $data = $this->strategy->photoDelete($id, $photoId);
        $data = array_merge($data, $this->strategy->getShowUpdatePageData($id));

        $this->view->generate('admin/category/update.twig', $data);
    }
}
