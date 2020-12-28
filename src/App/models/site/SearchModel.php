<?php

namespace App\Models\Site;

use App\Models\Models;
use App\Models\SearchRepository;

class SearchModel extends Model
{
    public function getIndexData($data)
    {
        $searchRepository = new SearchRepository();
        $productList = $searchRepository->getIndexData($data['search']);

        $data['productList'] = $productList;

        return $data;
    }
}

