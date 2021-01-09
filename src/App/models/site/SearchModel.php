<?php

namespace App\Models\Site;

use App\Components\Currency;
use App\Components\Language;

;

use App\Helpers\ProductPriceHelper;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;

class SearchModel
{
    /**
     * @var mixed
     */
    private $language;

    /**
     * @var mixed
     */
    private $currency;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ProductPriceHelper
     */
    private $productPriceHelper;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
        $this->productRepository = new ProductRepository();
        $this->productPriceHelper = new ProductPriceHelper();
    }

    public function getSearchData($data)
    {
        $productList = $this->productRepository->getAllByParams([
            'language_id' => $this->language['id'],
            'pd.name' => $data['query'],
            'pd.description' => $data['query'],
            'like' => ['pd.name', 'pd.description']
        ]);

        foreach ($productList as $key => $product) {
            $productList[$key]['price'] = $this->productPriceHelper->getPrice($product);
            $productList[$key]['convert_price'] = $this->productPriceHelper->getConvertPrice($product);
        }

        return [
            'query' => $data['query'],
            'productList' => $productList,
            'is_convert' => empty($this->currency['rate']) ? false : true,
        ];
    }
}

