<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\CommentModel;

class CommentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new CommentModel();
    }

    public function actionIndex()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getIndexData($_POST['order']);

        $this->view->generate('admin/comment/index.twig', $data);
    }

    public function publish($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->publish($id);

        if ($data['errors']) {
            $this->view->generate('admin/comment/index.twig', $data);
            return;
        }

        header("Location: /admin/comments");
    }

    public function actionShowCreatePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowCreatePageData($id);

        $this->view->generate('admin/comment/create.twig', $data);
    }

    public function create()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->create($_FILES, $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/comment/create.twig', $data);
            return;
        }

        header("Location: /admin/comments");
    }

    public function actionShowDeletePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowDeletePageData($id);

        $this->view->generate('admin/comment/delete.twig', $data);
    }

    public function delete()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->delete($_POST);

        if ($data['errors']) {
            $this->view->generate('admin/comment/delete.twig', $data);
            return;
        }

        header("Location: /admin/comments");
    }

}
