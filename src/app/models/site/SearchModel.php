<?php

namespace App\Model\Site;

use App\Model\Model;
use App\Model\SearchRepository;

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

