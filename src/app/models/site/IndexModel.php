<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Models\Model;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleRepository;
use Exception;

class IndexModel extends Model
{
    private $language;
    private $blogRepository;
    private $productRepository;
    private $saleRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
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
        $data['productList'] =  $this->productRepository->getAllAvailable();
        $data['articleList'] =  $this->blogRepository->getLastByLanguageId($this->language['id'], 3);
        $data['sale'] =  $this->saleRepository->getByLanguageId($this->language['id']);

        return $data;
    }
}
