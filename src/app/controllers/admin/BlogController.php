<?php

namespace App\Controller\Admin;

use App\Components\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\BlogModel;

class BlogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new BlogModel();
    }

    public function actionIndex()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getIndexData($_POST['order']);

        $this->view->generate('admin/blog/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowCreatePageData();

        $this->view->generate('admin/blog/create.twig', $data);
    }

    public function create()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->create($_FILES['file'], $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/blog/create.twig', $data);
            return;
        }

        header("Location: /admin/blog");
    }

    public function actionShowUpdatePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowUpdatePageData($id);

        $this->view->generate('admin/blog/update.twig', $data);
    }

    public function update()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->update($_FILES['file'], $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/blog/update.twig', $data);
            return;
        }

        header("Location: /admin/blog");
    }

    public function actionShowDeletePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowDeletePageData($id);

        $this->view->generate('admin/blog/delete.twig', $data);
    }

    public function delete()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->delete($_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/blog/delete.twig', $data);
            return;
        }

        header("Location: /admin/blog");
    }
}
