<?php

namespace App\Models\Site;

use App\Components\Currency;
use App\Components\Language;
use App\Helpers\CalculationHelper;
use App\Helpers\ProductPriceHelper;
use App\Repository\BlogRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleRepository;
use App\Exceptions\SiteException;
use App\Helpers\FileUploaderHelper;
use App\Repository\CommentDescriptionRepository;
use App\Repository\CommentRepository;
use App\Repository\FileRepository;
use Exception;
use DateTime;

class IndexModel
{
    const COMMENTS_COUNT = 3;
    private $language;
    private $blogRepository;
    private $productRepository;
    private $saleRepository;
    private $currency;
    /**
     * @var ProductPriceHelper
     */
    private $productPriceHelper;
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var CommentDescriptionRepository
     */
    private $commentDescriptionRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->currency = (new Currency())->getCurrency();
        $this->blogRepository = new BlogRepository();
        $this->productRepository = new ProductRepository();
        $this->saleRepository = new SaleRepository();
        $this->productPriceHelper = new ProductPriceHelper();
        $this->commentRepository = new CommentRepository();
        $this->commentDescriptionRepository = new CommentDescriptionRepository();
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

        $data['is_convert'] = empty($this->currency['rate']) ? false : true;

        foreach ($productList as $key => $product) {
            $productList[$key]['price'] = $this->productPriceHelper->getPrice($product);
            $productList[$key]['convert_price'] = $this->productPriceHelper->getConvertPrice($product);
        }

        $data['productList'] = $productList;
        $data['articleList'] =  $this->blogRepository->getLastByLanguageId($this->language['id'], 3);
        $data['sale'] =  $this->saleRepository->getByLanguageId($this->language['id']);
        $data['commentList'] =  $this->commentRepository->getAllByParams([
            'language_id' => $this->language['id'],
        ], self::COMMENTS_COUNT);

        return $data;
    }
}
