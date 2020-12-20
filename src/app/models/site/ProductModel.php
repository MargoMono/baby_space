<?php

namespace App\Models\Site;

use App\Components\Currency;
use App\Components\Language;
use App\Helpers\CalculationHelper;
use App\Repository\LanguageRepository;
use App\Repository\ProductRecommendationsRepository;
use App\Repository\ProductRepository;

class ProductModel
{
    private $language;
    private $currency;
    private $productRepository;
    private $productRecommendationsRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
        $this->productRepository = new ProductRepository();
        $this->productRecommendationsRepository = new ProductRecommendationsRepository();
    }

    public function getIndexData($id = null)
    {
        $product = $this->productRepository->getById(
            $id,
            ['language_id' => $this->language['id']],
        );

        $product['convert_price'] = CalculationHelper::convert($product['price'], $this->currency['rate']);

        if (!empty($product['sale'])) {
            $salePrice = CalculationHelper::sale($product['price'], $product['sale']);
            $product['convert_sale'] = CalculationHelper::convert($salePrice, $this->currency['rate']);
            $product['sale_price'] = $salePrice;
        }

        return [
            'product' => $product,
            'productRecommendationsList' =>  $this->productRecommendationsRepository->getByProductId(
                $product['id'],
                $this->language['id']
            )
        ];
    }
}

