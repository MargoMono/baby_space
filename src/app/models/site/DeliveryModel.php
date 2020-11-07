<?php

namespace App\Model\Site;

use App\Components\Model;
use App\Repository\PageRepository;

class DeliveryModel extends Model
{
    public function getDeliveryPageData()
    {
        $newRepository = new PageRepository();
        $data['delivery'] = $newRepository->getPageById(PageRepository::DELIVERY_PAGE_ID);

        return $data;
    }
}

