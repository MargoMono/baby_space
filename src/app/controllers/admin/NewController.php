<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\NewModel;

class NewController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new NewModel();
    }

    public function actionIndex()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getIndexData($_POST['order']);

        $this->view->generate('admin/new/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowCreatePageData();

        $this->view->generate('admin/new/create.twig', $data);
    }

    public function create()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->create($_FILES['file'], $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/new/create.twig', $data);
            return;
        }

        header("Location: /admin/new");
    }

    public function actionShowUpdatePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowUpdatePageData($id);

        $this->view->generate('admin/new/update.twig', $data);
    }

    public function update()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->update($_FILES['file'], $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/new/update.twig', $data);
            return;
        }

        header("Location: /admin/new");
    }

    public function actionShowDeletePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowDeletePageData($id);

        $this->view->generate('admin/new/delete.twig', $data);
    }

    public function delete()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->delete($_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/new/delete.twig', $data);
            return;
        }

        header("Location: /admin/new");
    }
}
