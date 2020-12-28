<?php

namespace App\Controllers\Admin;

use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\CommentModel;
use App\Models\Admin\ModelContext;

class CommentController implements ControllerStrategy
{
    private $controllerContext;
    private $model;

    private $directory = 'comment';

    public function __construct()
    {
        $this->controllerContext = new ControllerContext(new CommentModel(),
            new ModelContext(new CommentModel()), $this->directory);
        $this->model = new CommentModel();

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $this->controllerContext->actionIndex();
    }

    public function actionShowCreatePage()
    {
    }

    public function create()
    {
    }

    public function actionShowUpdatePage($id)
    {
        $this->controllerContext->actionShowUpdatePage($id);
    }

    public function update()
    {
        $this->controllerContext->update();
    }

    public function actionShowDeletePage($id)
    {
        $this->controllerContext->actionShowDeletePage($id);
    }

    public function delete()
    {
        $this->controllerContext->delete();
    }

    public function imageDelete($id, $imageId)
    {
        $this->controllerContext->imageDelete($id, $imageId);
    }

    public function commentAnswerImageDelete($commentId, $commentAnswerId, $imageId)
    {
        $this->model->commentAnswerImageDelete($commentId, $commentAnswerId, $imageId);
    }
}
