<?php

namespace App\Model\Site;

use App\Components\Model;
use App\Repository\CoatingRepository;
use App\Repository\DesignRepository;
use App\Repository\Site\ProductCoatingRepository;
use App\Repository\Site\ProductDesignRepository;
use App\Repository\Site\ProductPageKindRepository;
use App\Repository\Site\ProductRecommendationsRepository;
use App\Repository\Site\ProductRepository;

class ProductModel extends Model
{
    public function getProductData($productId)
    {
        $productPageKindRepository = new ProductPageKindRepository();
        $enableProductPageKind = $productPageKindRepository->getEnableProductPageKind();

        if ($enableProductPageKind['id'] == ProductPageKindRepository::COATING_PAGE_ID) {
            return $this->getCoatingProductData($productId);
        }

        return $this->getDesignProductData($productId);
    }

    private function getCoatingProductData($productId)
    {
        $productRepository = new ProductRepository();
        $product = $productRepository->getEnableProductById($productId);

        if (!$product) {
            return false;
        }

        $productCoatingRepository = new ProductCoatingRepository();
        $productCoatingIds = $productCoatingRepository->getProductCoatingListByProductId($productId);

        foreach ($productCoatingIds as $productCoating) {
            $coatingRepository = new CoatingRepository();
            $coatingFiles = $coatingRepository->getCoatingPhotos($productCoating['coating_id']);
            $productCoating['fileList'] = $coatingFiles;
            $product['coatingList'][] = $productCoating;
        }


        $productRecommendationsRepository = new ProductRecommendationsRepository();
        $productRecommendationList = $productRecommendationsRepository->getProductRecommendationsIdsByProductId($productId);
        $product['recommendationList'] = $productRecommendationList;
        $product['isComparison'] = $this->isComparisonProduct($productId);

        $params = [
            'breadcrumbs' => $this->getBreadcrumbs($product['category_id']),
            'product' => $product,
            'pageKind' => 'coating'
        ];

        return $params;
    }

    private function getDesignProductData($productId)
    {
        $productRepository = new ProductRepository();
        $product = $productRepository->getEnableProductById($productId);

        if (!$product) {
            return false;
        }

        $productDesignRepository = new ProductDesignRepository();
        $productDesignList = $productDesignRepository->getProductDesignListByProductId($productId);

        foreach ($productDesignList as $productDesign) {
            $designRepository = new DesignRepository();
            $designPhotos = $designRepository->getDesignPhotos($productDesign['design_id']);
            $productDesign['fileList'] = $designPhotos;
            $product['designList'][] = $productDesign;
        }

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        $productRecommendationList = $productRecommendationsRepository->getProductRecommendationsIdsByProductId($productId);
        $product['recommendationList'] = $productRecommendationList;
        $product['isComparison'] = $this->isComparisonProduct($productId);

        $params = [
            'breadcrumbs' => $this->getBreadcrumbs($product['category_id']),
            'product' => $product,
            'pageKind' => 'design'
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

