<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Models\Model;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleRepository;
use Exception;

class IndexModel extends Model
{
    /**
     * @return array|void
     * @throws Exception
     */
    public function getHomePageData()
    {
        $categoryRepository = new CategoryRepository();
        $data['categoryList'] = $categoryRepository->getAllAvailable();

        $productRepository = new ProductRepository();
        $data['productList'] = $productRepository->getAllAvailable();

        $saleRepository = new SaleRepository();

        $language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $data['sale'] = $saleRepository->getByLanguageId($language['id']);

        return $data;
    }
}
