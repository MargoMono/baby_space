<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\CategoryModel;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new CategoryModel();
    }

    public function actionIndex()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getIndexData($_POST['order']);

        $this->view->generate('admin/category/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowCreatePageData();

        $this->view->generate('admin/category/create.twig', $data);
    }

    public function createCategory()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->createCategory($_FILES, $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/category/create.twig', $data);
            return;
        }

        header("Location: /admin/category");
    }

    public function actionShowUpdatePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowUpdatePageData($id);

        $this->view->generate('admin/category/update.twig', $data);
    }

    public function updateCategory()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->updateCategory($_FILES, $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/category/update.twig', $data);
            return;
        }

        header("Location: /admin/category");
    }

    public function actionShowDeletePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowDeletePageData($id);

        $this->view->generate('admin/category/delete.twig', $data);
    }

    public function deleteCategory()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->deleteCategory($_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/category/delete.twig', $data);
            return;
        }

        header("Location: /admin/category");
    }


    public function photoDelete($id, $photoId)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->photoDelete($id, $photoId);
        $data = array_merge($data, $this->model->getShowUpdatePageData($id));

        $this->view->generate('admin/category/update.twig', $data);
    }
}
