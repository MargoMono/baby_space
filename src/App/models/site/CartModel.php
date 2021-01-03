<?php

namespace App\Models\Site;

use App\API\RussianPost;
use App\Components\Cart;
use App\Components\Currency;
use App\Components\Language;
use App\Helpers\CalculationHelper;
use App\Helpers\ProductPriceHelper;
use App\Repository\CountryRepository;
use App\Repository\CouponRepository;
use App\Repository\LanguageRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Exception;

class CartModel
{
    private $language;

    private $currency;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ProductPriceHelper
     */
    private $productPriceHelper;

    /**
     * @var CouponRepository
     */
    private $couponRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var CountryRepository
     */
    private $countryRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
        $this->productRepository = new ProductRepository();
        $this->couponRepository = new CouponRepository();
        $this->productPriceHelper = new ProductPriceHelper();
        $this->orderRepository = new OrderRepository();
        $this->countryRepository = new CountryRepository();
    }

    /**
     * @return array|void
     * @throws Exception
     */
    public function getIndexData(): array
    {
        $productList = [];
        $cartData = Cart::getAllCartData();

        if (!empty($cartData)) {
            foreach ($cartData as $id => $count) {
                $product = $this->productRepository->getById($id, ['language_id' => $this->language['id']]);
                $product['price'] = $this->productPriceHelper->getPrice($id);
                $product['count'] = $count;
                $product['total_price'] = $product['price'] * $count;

                $productList[$id] = $product;
            }
        }

        return [
            'paymentMethodList' => $this->orderRepository->getAllPaymentMethods(),
            'shippingMethodList' => $this->orderRepository->getAllShippingMethods(),
            'countryList' => $this->countryRepository->getAll(),
            'productList' => $productList,
            'total_price' => $this->productPriceHelper->getTotalPrice(),
            'is_convert' => empty($this->currency['rate']) ? false : true,
        ];
    }

    public function getUpdateData($id): array
    {
        $count = Cart::getProductCountById($id);
        $product = $this->productRepository->getById($id, ['language_id' => $this->language['id']]);

        $product['price'] = $this->productPriceHelper->getPrice($id);
        $product['count'] = $count;
        $product['total_price'] = $product['price'] * $count;
        $product['is_convert'] = empty($this->currency['rate']) ? false : true;

        return [
            'product' => $product,
            'total_price' => $this->productPriceHelper->getTotalPrice(),
            'count' => Cart::getProductCountInCart(),
        ];
    }

    public function getCouponData($couponCode)
    {
        return $this->couponRepository->getByCode($couponCode);
    }

    public function getCalculateDeliveryData($params)
    {
        $russianPost = new RussianPost(141008, 1000, 100);
        $tariff = $russianPost->getTariff() / 100;

        if (!empty($this->currency['rate'])) {
            $tariff = CalculationHelper::convert($tariff, $this->currency['rate']);
        }

        return [
            'tariff' => $tariff
        ];
    }
}
