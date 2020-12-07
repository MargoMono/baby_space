<?php

namespace App\Models\Site;

use App\Models\Model;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PortfolioRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleRepository;
use DateTime;
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
        $data['sale'] = $saleRepository->getById(1);

        return $data;
    }
}
