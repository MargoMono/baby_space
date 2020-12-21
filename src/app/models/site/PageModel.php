<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Repository\LanguageRepository;
use App\Repository\PageRepository;

class PageModel
{
    private $language;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->pageRepository = new PageRepository();
    }


    public function getCompanyData()
    {
        $data['company'] = $this->pageRepository->getByIdAndLanguageId(
            PageRepository::COMPANY_PAGE_ID,
            $this->language['id']
        );

        return $data;
    }

    public function getDeliveryData()
    {
        $data['delivery'] = $this->pageRepository->getByIdAndLanguageId(
            PageRepository::DELIVERY_PAGE_ID,
            $this->language['id']
        );
        return $data;
    }
}

