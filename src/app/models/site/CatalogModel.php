<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Helpers\TextHelper;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;
use App\Repository\SizeRepository;
use App\Repository\TypeRepository;

class CatalogModel
{
    const PRODUCT_COUNT = 12;

    private $language;
    private $categoryRepository;
    private $productRepository;
    public $sizeRepository;
    public $typeRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->categoryRepository = new CategoryRepository();
        $this->productRepository = new ProductRepository();
        $this->sizeRepository = new SizeRepository();
        $this->typeRepository = new TypeRepository();
    }

    public function getIndexData($id = null)
    {
        $productList = $this->productRepository->getAllByParams(
            [
                'language_id' => $this->language['id']
            ],
            self::PRODUCT_COUNT
        );

        $priceList = array_column($this->productRepository->getAll(), 'price');

        return [
            'category_id' => $id ?? null,
            'productList' => $productList,
            'sizeList' => $this->sizeRepository->getAll(),
            'typeList' => $this->typeRepository->getAll(),
            'max' => max($priceList),
            'min' => min($priceList),
        ];
    }

    public function getFilteredProductList($params)
    {
        $params = $this->prepareData($params);

        $productList = $this->productRepository->getAllByParams(
            $params,
             self::PRODUCT_COUNT
        );

        return [
            'productList' => $productList,
        ];
    }

    public function getShowMoreData($params)
    {
        $params['language_id'] = $this->language['id'];

        $productList = $this->productRepository->getAllByParams(
            $params,
            self::PRODUCT_COUNT,
            $params['count']
        );

        return [
            'productList' => $productList,
        ];
    }

    public function checkLastPage($params)
    {
        $params = $this->prepareData($params);

        $lastPage = false;

        $allProducts = $this->productRepository->getAllByParams(
            $params
        );

        if (count($allProducts) == $params['count']) {
            $lastPage = true;
        }

        return $lastPage;
    }

    public function prepareData($params): array
    {
        return [
            'language_id' => $this->language['id'],
            'category_id' => mb_substr($params['category_id'], -1),
            'size_id' => mb_substr($params['size_id'], -1),
            'type_id' => mb_substr($params['type_id'], -1),
            'max' => $params['max'] ?? null,
            'min' => $params['min'] ?? null,
            'count' => $params['count'] ?? null,
        ];
    }
}

