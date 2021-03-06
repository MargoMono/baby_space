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

    public function getTotalPrice()
    {
        $cartData = Cart::getAllCartData();
        $totalPrice = 0;

        if (empty($cartData)) {
            return $totalPrice;
        }

        foreach ($cartData as $id => $count) {
            $product = $this->productRepository->getById($id, ['language_id' => $this->language['id']]);
            $price = $this->getPrice($product);
            $totalPrice += $price * $count;
        }

        return $totalPrice;
    }

    public function getPrice($product)
    {
        if (!empty($product['sale'])) {
            $price = CalculationHelper::sale($product['price'], $product['sale']);
        } else {
            $price = $product['price'];
        }

        return $price;
    }

    public function getTotalConvertPrice()
    {
        $cartData = Cart::getAllCartData();
        $totalPrice = 0;

        if (empty($this->currency['rate'])) {
            return false;
        }

        if (empty($cartData)) {
            return $totalPrice;
        }

        foreach ($cartData as $id => $count) {
            $product = $this->productRepository->getById($id, ['language_id' => $this->language['id']]);
            $price = $this->getConvertPrice($product);
            $totalPrice += $price * $count;
        }

        return $totalPrice;
    }

    public function getConvertPrice($product)
    {
        $sale = false;

        if (empty($this->currency['rate'])) {
            return false;
        }

        if (!empty($product['sale'])) {
            $sale = true;
        }

        if ($sale) {
            $salePrice = CalculationHelper::sale($product['price'], $product['sale']);
            $price = CalculationHelper::convert($salePrice, $this->currency['rate']);
        } else {
            $price = CalculationHelper::convert($product['price'], $this->currency['rate']);
        }

        return $price;
    }
}