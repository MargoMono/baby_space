<?php

namespace App\Controller\Site;

use App\Controller\Controller;
use App\Model\Site\ProductModel;

class ProductController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new ProductModel();
    }

    public function showProductPage($alias, $productId)
    {
        $data = $this->model->getProductData($productId);

        if (!$data) {
            http_response_code(404);
            $this->view->generate('site/404.twig');
            return;
        }

        $data['page'] = 'catalog';

        if ($data['pageKind'] == 'coating') {
            $this->view->generate('site/product/coating.twig', $data);
            return;
        }

        $this->view->generate('site/product/design.twig', $data);
    }

    public function addToComparison()
    {
        $data = $this->model->addToComparison($_POST['id']);
        $data['result'] = true;
        $data['action'] = 'add';
        $this->view->generateAjax($data);
    }

    public function deleteFromComparison()
    {
        $data = $this->model->deleteFromComparison($_POST['id']);
        $data['result'] = true;
        $data['action'] = 'delete';
        $this->view->generateAjax($data);
    }
}
