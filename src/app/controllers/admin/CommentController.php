<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\BlogStrategy;
use App\Models\Admin\CommentModel;
use App\Models\Admin\ModelContext;

class CommentController extends Controller
{
    private $controllerContext;

    private $directory = 'comment';

    public function __construct()
    {
        $this->controllerContext = new ControllerContext(new BlogStrategy(),
            new ModelContext(new BlogStrategy()), $this->directory);

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
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
