<?php

namespace App\Models\Site;

use App\Models\Models;
use App\Repository\PageRepository;

class DeliveryModel extends Model
{
    public function getDeliveryPageData()
    {
        $newRepository = new PageRepository();
        $data['delivery'] = $newRepository->getById(PageRepository::DELIVERY_PAGE_ID);

        return $data;
    }
}

