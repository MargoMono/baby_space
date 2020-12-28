<?php

namespace App\Controllers\Site;

use App\Models\Site\UserModel;
use App\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    public function showLoginPage()
    {
        $this->view->generate('site/user/login.twig');
    }

    public function actionLogin()
    {
        $data = $this->model->actionLogin();

        if ($data['result'] === true) {
            header("Location: /admin/product");
        }

        $this->view->generate('site/user/login.twig', $data);
    }

    public function showRestorePasswordPage()
    {
        $this->view->generate('site/user/restorePassword.twig');
    }

    public function restorePassword()
    {
        $data = $this->model->actionRestorePassword($_POST);

        $this->view->generate('site/user/restorePassword.twig', $data);
    }

    public function showUpdatePasswordPage($activeHex)
    {
        $data = $this->model->showUpdatePasswordPage($activeHex);

        $this->view->generate('site/user/updatePassword.twig', $data);
    }

    public function updatePassword()
    {
        $data = $this->model->updatePassword($_POST);

        $this->view->generate('site/user/updatePassword.twig', $data);
    }


    public function actionLogout()
    {
        unset($_SESSION["user"]);
        header("Location: /");
    }
}

