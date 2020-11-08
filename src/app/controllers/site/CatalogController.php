<?php

namespace App\Controller\Site;

use App\Controller\Controller;
use App\Model\Site\CatalogModel;

class CatalogController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new CatalogModel();
    }

    public function showCatalogPage($alias, $id)
    {
        $data = $this->model->getCatalogPageData($id);

        if (!$data){
            http_response_code(404);
            $this->view->generate('site/404.twig');
            return;
        }

        $data['page'] = 'catalog';

        switch ($data['template']) {
            case 'childCategory' :
                $this->view->generate('site/catalog/childCategory.twig', $data);
                break;
            case 'products' :
                $this->view->generate('site/catalog/products.twig', $data);
                break;
            case 'categoryAndProducts' :
                $this->view->generate('site/catalog/categoryAndProducts.twig', $data);
                break;
            case 'category' :
                $this->view->generate('site/catalog/category.twig', $data);
                break;
            default:
                $this->view->generate('site/catalog/category.twig', $data);
                break;
        }
    }

    public function getMoreByCategory($categoryId, $count)
    {
        $data = $this->model->getMoreByCategoryData($categoryId, $count);
        $this->view->generate('site/catalog/showMore.twig', $data);
    }

    public function showProductComparisonPage()
    {
        $data = $this->model->getComparisonData($_SESSION['comparison_product']);

        $data['page'] = 'catalog';
        $this->view->generate('site/catalog/comparisonProducts.twig', $data);
    }
}
