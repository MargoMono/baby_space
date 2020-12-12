<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;

class CatalogModel
{
    const PRODUCT_COUNT = 12;

    private $language;
    private $categoryRepository;
    private $productRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->categoryRepository = new CategoryRepository();
        $this->productRepository = new ProductRepository();
    }

    public function getCategoryData($categoryId)
    {
        $category = $this->categoryRepository->getById($categoryId);
        $productList = $this->productRepository->getByCatageoryAndLanguageId(
            $categoryId,
            $this->language['id'],
            self::PRODUCT_COUNT
        );

        return [
            'category' => $category,
            'productList' => $productList,
        ];
    }

    public function getShowMoreData($categoryId, $offset)
    {
        $productList = $this->productRepository->getMoreByCatageoryAndLanguageId(
            $categoryId,
            $this->language['id'],
            self::PRODUCT_COUNT,
            $offset
        );

        return [
            'productList' => $productList,
        ];
    }

    public function checkLastPage($categoryId, $count)
    {
        $lastPage = false;

        $allProducts = $this->productRepository->getAllByCategoryAndLanguageId($categoryId, $this->language['id']);

        if (count($allProducts) == $count) {
            $lastPage = true;
        }

        return $lastPage;
    }
}

