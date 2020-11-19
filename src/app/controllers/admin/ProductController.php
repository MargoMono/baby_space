<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\Context;
use App\Models\Admin\ProductStrategy;
use Symfony\Component\Yaml\Exception\RuntimeException;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->context = new Context(new ProductStrategy());

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $data = $this->context->getIndexData($_POST['order']);
        $this->view->generate('admin/product/index.twig', $data);
    }

    public function actionShowCreatePage()
    {
        $data = $this->context->getShowCreatePageData();
        $this->view->generate('admin/product/create.twig', $data);
    }

    public function create()
    {
        try {
            $this->context->validation($_FILES, $_POST);
            $this->context->create($_FILES, $_POST);
        } catch (\Exception $exception) {
            $data = $this->context->getShowCreatePageData();
            $data['error_warning'] = 'Невозможно создать продукт, обратитесь к разработчику и сообщите ему код ошибки (' . $exception->getMessage() .' )';
            $this->view->generate('admin/product/create.twig', $data);
            return;
        }

        header('Location: /admin/product');
    }

    public function actionShowUpdatePage($id)
    {
        $data = $this->context->getShowUpdatePageData($id);
        $this->view->generate('admin/product/update.twig', $data);
    }

    public function update()
    {
        $data = $this->context->update($_FILES, $_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->context->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/product/update.twig', $data);
            return;
        }

        header('Location: /admin/product');
    }

    public function actionShowDeletePage($id)
    {
        $data = $this->context->getShowDeletePageData($id);
        $this->view->generate('admin/product/delete.twig', $data);
    }

    public function delete()
    {
        $data = $this->context->delete($_POST);

        if ($data['errors']) {
            $data = array_merge($data, $this->context->getShowUpdatePageData($_POST['id']));
            $this->view->generate('admin/product/delete.twig', $data);
            return;
        }

        header("Location: /admin/product");
    }

    public function photoDelete($id, $photoId)
    {
        $data = $this->context->photoDelete($id, $photoId);
        $data = array_merge($data, $this->context->getShowUpdatePageData($id));

        $this->view->generate('admin/product/update.twig', $data);
    }
}

