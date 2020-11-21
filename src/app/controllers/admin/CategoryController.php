<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\CategoryStrategy;
use App\Models\Admin\ModelContext;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->context = new ModelContext(new CategoryStrategy());

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $data = $this->context->getIndexData($_POST['order']);
        $this->view->generate('admin/category/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $data = $this->context->getShowCreatePageData();
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
        $data = $this->context->getShowDeletePageData($id);

        $this->view->generate('admin/category/delete.twig', $data);
    }

    public function deleteCategory()
    {
        $data = $this->context->delete($_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->context->getShowDeletePageData($_POST['id']));
            $this->view->generate('admin/category/delete.twig', $data);
            return;
        }

        header('Location: /admin/category');
    }

    public function photoDelete($id, $photoId)
    {
        $data = $this->context->photoDelete($id, $photoId);
        $data = array_merge($data, $this->context->getShowUpdatePageData($id));

        $this->view->generate('admin/category/update.twig', $data);
    }
}
