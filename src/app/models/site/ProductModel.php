<?php

namespace App\Model\Site;

use App\Model\Model;
use App\Repository\Site\ProductRecommendationsRepository;
use App\Repository\Site\ProductRepository;

class ProductModel extends Model
{
    public function getProductData($productId)
    {
        return $this->getCoatingProductData($productId);
    }

    private function getCoatingProductData($productId)
    {
        $productRepository = new ProductRepository();
        $product = $productRepository->getEnableProductById($productId);

        if (!$product) {
            return false;
        }

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        $productRecommendationList = $productRecommendationsRepository->getProductRecommendationsIdsByProductId($productId);
        $product['recommendationList'] = $productRecommendationList;
        $product['isComparison'] = $this->isComparisonProduct($productId);

        $params = [
            'breadcrumbs' => $this->getBreadcrumbs($product['category_id']),
            'product' => $product,
        ];

        return $params;
    }

    public function isComparisonProduct($id)
    {
        if (empty($_SESSION['comparison_product'])) {
            return false;
        }

        return array_search($id, $_SESSION['comparison_product']);
    }

    public function addToComparison($id)
    {
        if(empty($_SESSION['comparison_product'])){
            $_SESSION['comparison_product'][] = $id;
        } else {
            $key = array_search($id, $_SESSION['comparison_product']);
            if ($key === false){
                $_SESSION['comparison_product'][] = $id;
            }
        }

        $data['comparison_product_count'] = count($_SESSION['comparison_product']);

        return $data;
    }

    public function deleteFromComparison($id)
    {
        $key = array_search($id, $_SESSION['comparison_product']);

        if ($key !== false){
            unset($_SESSION['comparison_product'][$key]);
        }

        $data['comparison_product_count'] = count($_SESSION['comparison_product']);

        return $data;
    }
}

