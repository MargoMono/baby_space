<?php

namespace App\Model\Site;

use App\Model\Model;
use App\Repository\PageRepository;

class CompanyModel extends Model
{
    public function getCompanyPageData()
    {
        $newRepository = new PageRepository();
        $data['company'] = $newRepository->getById(PageRepository::COMPANY_PAGE_ID);

        return $data;
    }
}

