<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\Context;
use App\Models\Admin\CurrencyStrategy;

class CurrencyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->strategy = new CurrencyStrategy();
        $this->context = new Context($this->strategy);

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $data = $this->strategy->getIndexData($_GET);
        $this->view->generate('admin/currency/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $data = $this->strategy->getShowCreatePageData();
        $this->view->generate('admin/currency/create.twig', $data);
    }

    public function create()
    {
        try {
            $this->context->create($_FILES, $_POST);
        } catch (\Exception $exception) {
            $data = $this->strategy->getShowCreatePageData();
            $data['error_warning'] = 'Невозможно создать валюту, обратитесь к разработчику и сообщите ему код ошибки (' . $exception->getMessage() . ' )';
            $this->view->generate('admin/currency/create.twig', $data);
            return;
        }

        header('Location: /admin/currency');
    }

    public function actionShowUpdatePage($id)
    {
        $data = $this->strategy->getShowUpdatePageData($id);
        $this->view->generate('admin/currency/update.twig', $data);
    }

    public function update()
    {
        try {
            $this->context->update($_FILES, $_POST);
        } catch (\Exception $exception) {
            $data = $this->strategy->getShowUpdatePageData($_POST['id']);
            $data['error_warning'] = 'Невозможно обновить валюту, обратитесь к разработчику и сообщите ему код ошибки (' . $exception->getMessage() . ' )';
            $this->view->generate('admin/currency/update.twig', $data);
            return;
        }

        header('Location: /admin/currency');
    }

    public function actionShowDeletePage($id)
    {
        $data = $this->strategy->getShowDeletePageData($id);
        $this->view->generate('admin/currency/delete.twig', $data);
    }

    public function delete()
    {
        try {
            $this->context->delete($_POST);
        } catch (\Exception $exception) {
            $data = $this->strategy->getShowCreatePageData($_POST['id']);
            $data['error_warning'] = 'Невозможно удалить валюту, обратитесь к разработчику и сообщите ему код ошибки (' . $exception->getMessage() . ' )';
            $this->view->generate('admin/currency/index.twig', $data);
            return;
        }

        header('Location: /admin/currency');
    }
}
