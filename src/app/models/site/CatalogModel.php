<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;

class CatalogModel
{
    const PRODUCT_COUNT = 4;

    private $language;
    private $categoryRepository;
    private $productRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->categoryRepository = new CategoryRepository();
        $this->productRepository = new ProductRepository();
    }

    public function getIndexData()
    {
        $productList = $this->productRepository->getAllByParams(
            [
                'language_id' => $this->language['id']
            ],
            self::PRODUCT_COUNT
        );

        return [
            'productList' => $productList,
        ];
    }


    public function getShowMoreData($offset)
    {
        $productList = $this->productRepository->getAllByParams(
            [
                'language_id' => $this->language['id']
            ],
            self::PRODUCT_COUNT,
            $offset
        );

        return [
            'productList' => $productList,
        ];
    }

    public function checkLastPage($count)
    {
        $lastPage = false;

        $allProducts = $this->productRepository->getAllByParams([
            'language_id' => $this->language['id']
        ]);

        if (count($allProducts) == $count) {
            $lastPage = true;
        }

        return $lastPage;
    }
}

