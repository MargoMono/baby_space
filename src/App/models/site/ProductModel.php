<?php

namespace App\Models\Site;

use App\Components\Currency;
use App\Components\Language;
use App\Helpers\CalculationHelper;
use App\Helpers\ProductPriceHelper;
use App\Repository\LanguageRepository;
use App\Repository\ProductRecommendationsRepository;
use App\Repository\ProductRepository;

class ProductModel
{
    private $language;
    private $currency;
    private $productRepository;
    private $productRecommendationsRepository;
    private $productPriceHelper;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
        $this->productRepository = new ProductRepository();
        $this->productRecommendationsRepository = new ProductRecommendationsRepository();
        $this->productPriceHelper = new ProductPriceHelper();
    }

    public function getIndexData($id = null)
    {
        $product = $this->productRepository->getById(
            $id,
            ['language_id' => $this->language['id']]
        );

        $product['price'] = $this->productPriceHelper->getPrice($id);
        $product['is_convert'] = empty($this->currency['rate']) ? false : true;

        return [
            'product' => $product,
            'productRecommendationsList' =>  $this->productRecommendationsRepository->getByProductId(
                $product['id'],
                $this->language['id']
            )
        ];
    }
}

