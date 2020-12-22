<?php

namespace App\Models\Site;

use App\Components\Cart;
use App\Components\Currency;
use App\Components\Language;
use App\Helpers\CalculationHelper;
use App\Repository\LanguageRepository;
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


    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
        $this->productRepository = new ProductRepository();
    }

    /**
     * @return array|void
     * @throws Exception
     */
    public function getIndexData(): array
    {
        $productList = [];

        foreach (Cart::getAllCartData() as $id => $count) {
            $product = $this->productRepository->getById($id, ['language_id' => $this->language['id']]);

            $product['convert_price'] = CalculationHelper::convert($product['price'], $this->currency['rate']);

            if (!empty($product['sale'])) {
                $salePrice = CalculationHelper::sale($product['price'], $product['sale']);
                $product['sale_price'] = $salePrice;
                $product['convert_sale'] = CalculationHelper::convert($salePrice, $this->currency['rate']);
            }


            $productList[$id] = array_merge($product,
                ['count' => $count],
                ['total' => $count * $product['price']],
                ['total_sale' => $count * $product['sale_price']],
                ['total_convert' => $count * $product['convert_price']],
                ['total_convert_sale' => $count * $product['convert_sale']]
            );
        }

        var_dump();

        return [
            'productList' => $productList,
        ];
    }
}
