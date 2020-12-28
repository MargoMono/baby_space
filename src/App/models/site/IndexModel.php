<?php

namespace App\Models\Site;

use App\Components\Currency;
use App\Components\Language;
use App\Helpers\CalculationHelper;
use App\Repository\BlogRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleRepository;
use Exception;

class IndexModel
{
    private $language;
    private $blogRepository;
    private $productRepository;
    private $saleRepository;
    private $currency;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
        $this->blogRepository = new BlogRepository();
        $this->productRepository = new ProductRepository();
        $this->saleRepository = new SaleRepository();
    }

    /**
     * @return array|void
     * @throws Exception
     */
    public function getHomePageData()
    {
        $productList = $this->productRepository->getAllByParams(
            ['popular' => 1]
        );

        foreach ($productList as $key => $product) {
            $salePrice = null;

            if (!empty($product['sale'])) {
                $salePrice = CalculationHelper::sale($product['price'], $product['sale']);
            }

            $productList[$key]['convert_price'] = CalculationHelper::convert($product['price'], $this->currency['rate']);

            if (empty($salePrice)) {
                continue;
            }

            $productList[$key]['convert_sale'] = CalculationHelper::convert($salePrice, $this->currency['rate']);
            $productList[$key]['sale_price'] = $salePrice;
        }

        $data['productList'] = $productList;
        $data['articleList'] =  $this->blogRepository->getLastByLanguageId($this->language['id'], 3);
        $data['sale'] =  $this->saleRepository->getByLanguageId($this->language['id']);

        return $data;
    }
}
