<?php

namespace App\Models\Site;

use App\Components\Currency;
use App\Components\Language;
use App\Helpers\CalculationHelper;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;
use App\Repository\SizeRepository;
use App\Repository\TypeRepository;

class CatalogModel
{
    const PRODUCT_COUNT = 12;

    private $language;
    private $currency;
    private $categoryRepository;
    private $productRepository;
    public $sizeRepository;
    public $typeRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
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
            'productList' => $this->getProductConvertAndSalePrice($productList),
            'sizeList' => $this->sizeRepository->getAll(),
            'typeList' => $this->typeRepository->getAll(),
            'max' => max($priceList),
            'max_convert' => CalculationHelper::convert(max($priceList), $this->currency['rate']),
            'min' => min($priceList),
            'min_convert' => CalculationHelper::convert(min($priceList), $this->currency['rate']),
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
            'productList' => $this->getProductConvertAndSalePrice($productList),
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
            'productList' => $this->getProductConvertAndSalePrice($productList),
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


    private function getProductConvertAndSalePrice($productList)
    {
        foreach ($productList as $key => $product) {
            $salePrice = null;

            if (!empty($product['sale'])) {
                $salePrice = CalculationHelper::sale($product['price'], $product['sale']);
            }

            $productList[$key]['convert_price'] = CalculationHelper::convert($product['price'],
                $this->currency['rate']);

            if (empty($salePrice)) {
                continue;
            }

            $productList[$key]['convert_sale'] = CalculationHelper::convert($salePrice, $this->currency['rate']);
            $productList[$key]['sale_price'] = $salePrice;
        }

        return $productList;
    }

    public function prepareData($params): array
    {
        return [
            'language_id' => $this->language['id'],
            'category_id' => $params['category_id'],
            'size_id' => $params['size_id'],
            'type_id' => $params['type_id'],
            'max' => CalculationHelper::unconvert($params['max'], $this->currency['rate']) ?? $params['max'],
            'min' => CalculationHelper::unconvert($params['min'], $this->currency['rate']) ?? $params['min'],
            'count' => $params['count'] ?? null,
        ];
    }
}

