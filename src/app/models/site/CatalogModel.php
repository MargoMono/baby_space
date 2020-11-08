<?php

namespace App\Model\Site;

use App\Model\Model;
use App\Controller\Site\MailController;
use App\Mailer;
use App\Repository\CategoryOrderRepository;
use App\Repository\CategoryRepository;
use App\Repository\Site\ProductRepository;
use PHPMailer\PHPMailer\Exception;

class CatalogModel extends Model
{
    const PRODUCT_COUNT = 12;

    /**
     * @return array|void
     */
    public function getIndexData()
    {
        $categoryRepository = new CategoryRepository();
        $mainCategoryList = $categoryRepository->getMainCategoryList();

        $params = [
            'categoryMenu' => $this->getCategoryMenu(),
            'mainCategoryList' => $mainCategoryList,
        ];

        return $params;
    }

    public function getCategoryLevel($id)
    {
        $categoryRepository = new CategoryRepository();
        $parent = $categoryRepository->getParentCategoryListById($id);

        $level = 1;

        while ($parent) {
            $level++;
            $parent = $categoryRepository->getParentCategoryListById($parent['id']);
        }

        return $level;
    }


    public function getCatalogPageData($id)
    {
        $categoryRepository = new CategoryRepository();
        $childCategoryList = $categoryRepository->getEnableChildCategoryListById($id);

        $level = $this->getCategoryLevel($id);

        $lastPage = 0;

        $productRepository = new ProductRepository();
        $productList = $productRepository->getEnabledProductListByCategory($id, self::PRODUCT_COUNT);
        $allProductList = $productRepository->getAllEnabledProductListByCategory($id);

        if (count($allProductList) <= self::PRODUCT_COUNT) {
            $lastPage = 1;
        }

        $category = $categoryRepository->getEnabledCategoryById($id);

        if (!$category) {
            return false;
        }

        $data['category'] = $category;
        $data['breadcrumbs'] = $this->getBreadcrumbs($id);

        if ($level == 1) {
            // Если мы заходим по ссылке 1 уровня, то отображаются только подкатегории.
            $data['childCategoryList'] = $childCategoryList;
            $data['template'] = 'childCategory';
        }

        if ($level == 3) {
            if (!empty($childCategoryList)) {
                return;
            }

            $parentCategory = $categoryRepository->getParentCategoryListById($id);
            $childCategoryList = $categoryRepository->getEnableChildCategoryListById($parentCategory['id']);

            $data['category'] = $parentCategory;
            $data['childCategoryList'] = $childCategoryList;
            $data['template'] = 'categoryAndProducts';
            $data['childCategoryActive'] = $category['alias'];
            $data['productList'] = $productList;
            $data['lastPage'] = $lastPage;
            $data['categoryId'] = $id;
        }

        // Если мы заходим по ссылке 1 уровня
        if ($level == 2) {
            //и у нее есть дочерние категории 3 уровня, отображаются категории и товары
            if (!empty($childCategoryList)) {
                $data['productList'] = [];

                foreach ($childCategoryList as $key => $childCategory) {
                    $productList = $productRepository->getAllEnabledProductListByCategory($childCategory['id']);
                    $data['productList'] = array_merge($data['productList'], $productList);
                }

                $lastPage = 0;

                if (count($data['productList']) <= self::PRODUCT_COUNT) {
                    $lastPage = 1;
                }

                $data['productList'] = array_slice($data['productList'], 0, self::PRODUCT_COUNT);
                $data['childCategoryList'] = $childCategoryList;
                $data['template'] = 'categoryAndProducts';
                $data['categoryId'] = $id;
                $data['lastPage'] = $lastPage;
            }  //у нее нет дочерних категорий
            elseif (empty($childCategoryList)) {
                //и нет товаров, то отображается описание категории
                if (empty($productList)) {
                    $data['categoryFilesList'] = $categoryRepository->getCategoryFilesByCategoryId($id);
                    $data['template'] = 'category';
                    // но есть товары, отображаются товары
                } else {
                    $data['productList'] = $productList;
                    $data['template'] = 'products';
                    $data['lastPage'] = $lastPage;
                }
            }
        }

        return $data;
    }

    public function getComparisonData($comparisonProductIdsList)
    {
        if (empty($comparisonProductIdsList)){
            $data['productList'] = null;
            return $data;
        }

        $lastPage = 0;

        $productRepository = new ProductRepository();
        $comparisonProductIds = implode(', ', $comparisonProductIdsList);
        $productList = $productRepository->getEnabledProductListByIds($comparisonProductIds, self::PRODUCT_COUNT);
        $allProductList = $productRepository->getAllEnabledProductListByIds($comparisonProductIds);

        if (count($allProductList) <= self::PRODUCT_COUNT) {
            $lastPage = 1;
        }

        $data['productList'] = $productList;
        $data['lastPage'] = $lastPage;

        return $data;
    }

    public function getMoreByCategoryData($categoryId, $count)
    {
        $lastPage = 0;

        $level = $this->getCategoryLevel($categoryId);

        $categoryRepository = new CategoryRepository();
        $productRepository = new ProductRepository();

        if ($level == 2) {
            $childCategoryList = $categoryRepository->getEnableChildCategoryListById($categoryId);

            $data['productList'] = [];

            foreach ($childCategoryList as $key => $childCategory) {
                $productList = $productRepository->getAllEnabledProductListByCategory($childCategory['id']);
                $data['productList'] = array_merge($data['productList'], $productList);
            }

            $data['productList'] = array_slice($data['productList'], $count, self::PRODUCT_COUNT);
            $productList = $data['productList'];
        }

        if ($level == 3) {
            $productList = $productRepository->getMoreEnabledProductsByCategory($categoryId, $count, self::PRODUCT_COUNT);
        }

        if (count($productList) !== self::PRODUCT_COUNT) {
            $lastPage = 1;
        }

        $params = [
            'productList' => $productList,
            'lastPage' => (int)$lastPage,
        ];

        return $params;
    }

    protected function getCategoryMenu()
    {
        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getAll();
        $categoryList = $categoryRepository->getArrayWithIdAsKey($categoryList);

        $categoryMenu = array();

        foreach ($categoryList as $id => &$category) {
            if (empty($category['parent_id'])) {
                $categoryMenu[$id] = &$category;
            } else {
                $categoryList[$category['parent_id']]['children'][$id] = &$category;
            }
        }

        return $categoryMenu;
    }
}

