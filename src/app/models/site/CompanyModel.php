<?php

namespace App\Model\Site;

use App\Model\Model;
use App\Modules\FileUploader;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;
use App\Repository\BlogRepository;
use App\Repository\PageRepository;
use RuntimeException;

class CompanyModel extends Model
{
    public function getCompanyPageData()
    {
        $newRepository = new PageRepository();
        $data['company'] = $newRepository->getPageById(PageRepository::COMPANY_PAGE_ID);

        return $data;
    }
}

