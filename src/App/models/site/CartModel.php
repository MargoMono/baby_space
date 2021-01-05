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
use DateTime;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use LapayGroup\RussianPost\Exceptions\RussianPostException;
use LapayGroup\RussianPost\Exceptions\RussianPostTarrificatorException;
use LapayGroup\RussianPost\TariffCalculation;

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
     * @return array|null
     * @throws Exception
     */
    public function getIndexData(): ?array
    {
        $productList = [];
        $cartData = Cart::getAllCartData();

        if (empty($cartData)) {
            return null;
        }

        $totalProductsWeight = 0;

        foreach ($cartData as $id => $count) {
            $product = $this->productRepository->getById($id, ['language_id' => $this->language['id']]);

            $price = $this->productPriceHelper->getPrice($product);
            $convertPrice = $this->productPriceHelper->getConvertPrice($product);

            $product['count'] = $count;
            $product['price'] = $price;
            $product['convert_price'] = $convertPrice;

            $product['total_price'] = $product['price'] * $count;
            $product['convert_total_price'] = $product['convert_price'] * $count;

            $productList[$id] = $product;

            $totalProductsWeight += $product['weight'];
        }

        return [
            'paymentMethodList' => $this->orderRepository->getAllPaymentMethods(),
            'shippingMethodList' => $this->orderRepository->getAllShippingMethods(),
            'countryList' => $this->countryRepository->getAllByParams($this->language['id']),
            'productList' => $productList,
            'total_price' => $this->productPriceHelper->getTotalPrice(),
            'convert_total_price' => $this->productPriceHelper->getTotalConvertPrice(),
            'total_weight' => $totalProductsWeight,
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
        try {
            $objectId = 7020; // https://tariff.pochta.ru/#/108
            $date = new DateTime();

            $params = [
                'weight' => $params['weight'],
                'sumoc' => 7020,
                'from' => 420076,
                'to' => $params['postcode'],
                'group' => 0,
                'date' => $date->format('Ymd'),
                'time' => $date->format('Hi'),
            ];

            $services = [41];

            $TariffCalculation = new TariffCalculation();
            $calcInfo = $TariffCalculation->calculate($objectId, $params, $services);
            $tariff = $calcInfo->getPayNds();
        } catch (RussianPostTarrificatorException | RussianPostException $e) {
            $errors = $e->getErrors(); // Массив вида [['msg' => 'текст ошибки', 'code' => код ошибки]]
        }

        if (!empty($this->currency['rate'])) {
            $tariffConvert = CalculationHelper::convert($tariff, $this->currency['rate']);
        }

        return [
            'tariff' => $tariff,
            'tariff_convert' => $tariffConvert ?? null
        ];
    }
}
