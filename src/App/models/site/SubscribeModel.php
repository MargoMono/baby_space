<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Exceptions\SiteException;
use App\Repository\SubscribeRepository;
use App\Repository\LanguageRepository;

class SubscribeModel
{
    /**
     * @var mixed
     */
    private $language;
    /**
     * @var SubscribeRepository
     */
    private $subscribeRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->commentRepository = new SubscribeRepository();
    }

    public function createSubscribe($params)
    {
        $newSubscribeId = $this->subscribeRepository->create([
            'user_email' => $params['user_email'],
            'status' => 1
        ]);

    }
}
