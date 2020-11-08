<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Model\Admin\PriceListModel;

class PriceListController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new PriceListModel();
    }

    public function actionIndex()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getIndexData($_POST['order']);

        $this->view->generate('admin/price-list/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();


        $this->view->generate('admin/price-list/create.twig');
    }

    public function create()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->create($_FILES, $_POST);

        if ($data['errors']) {
            $this->view->generate('admin/price-list/create.twig', $data);
            return;
        }

        header("Location: /admin/price-list");
    }

    public function actionShowUpdatePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowUpdatePageData($id);

        $this->view->generate('admin/price-list/update.twig', $data);
    }

    public function update()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->update($_FILES, $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/price-list/update.twig', $data);
            return;
        }

        header("Location: /admin/price-list");
    }

    public function actionShowDeletePage($id)
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getShowDeletePageData($id);

        $this->view->generate('admin/price-list/delete.twig', $data);
    }

    public function delete()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->delete($_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->model->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/price-list/delete.twig', $data);
            return;
        }

        header("Location: /admin/price-list");
    }

    public function actionOrder()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $data = $this->model->getOrderData($_POST['order']);

        $this->view->generate('admin/price-list-order/index.twig', $data);
    }
}
