<?php

namespace App\Models\Site;

use App\Models\Model;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PortfolioRepository;
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

        return $data;
    }
}
