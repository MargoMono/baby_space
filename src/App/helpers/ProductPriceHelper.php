<?php

namespace App\Helpers;

use App\Components\Cart;
use App\Components\Currency;
use App\Components\Language;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;

class ProductPriceHelper
{
    private $language;
    private $currency;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
        $this->productRepository = new ProductRepository();
    }

    public function getTotalPrice(){
        $cartData = Cart::getAllCartData();
        $totalPrice = 0;

        if (empty($cartData)){
            return $totalPrice;
        }

        foreach ($cartData as $id => $count) {
            $product['price'] = $this->getPrice($id);
            $totalPrice += $product['price'] * $count;
        }

        return $totalPrice;
    }

    public function getPrice($id)
    {
        $convert = false;
        $sale = false;

        $product = $this->productRepository->getById($id, ['language_id' => $this->language['id']]);
        $product['convert_price'] = CalculationHelper::convert($product['price'], $this->currency['rate']);

        if (!empty($this->currency['rate'])){
            $convert = true;
        }

        if (!empty($product['sale'])){
            $sale = true;
        }

        if ($convert && $sale){
            $salePrice = CalculationHelper::sale($product['price'], $product['sale']);
            $price =  CalculationHelper::convert($salePrice, $this->currency['rate']);
        }

        if ($convert && !$sale) {
            $price =  CalculationHelper::convert($product['price'], $this->currency['rate']);
        }

        if (!$convert && $sale) {
            $price = CalculationHelper::sale($product['price'], $product['sale']);
        }

        if (!$convert && !$sale) {
            $price = $product['price'];
        }

        return $price;
    }
}