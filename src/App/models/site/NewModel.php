<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Repository\LanguageRepository;
use App\Repository\NewRepository;
use DateTime;
use Exception;

class NewModel
{
    const NEWS_COUNT = 4;

    private $language;
    private $newRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->newRepository = new NewRepository();
    }

    /**
     * @return array|void
     * @throws Exception
     */
    public function getIndexData()
    {
        $newList = $this->newRepository->getAllByParams(
            [
                'language_id' => $this->language['id']
            ],
            self::NEWS_COUNT
        );

        foreach ($newList as $key => $article) {
            $date = new DateTime($article['created_at']);
            $newList[$key]['created_at'] = $date->format('d/m/Y');
        }

        return [
            'newList' => $newList,
        ];
    }

    /**
     * @param $offset
     * @return array|void
     * @throws Exception
     */
    public function getShowMoreData($offset)
    {
        $moreArticles = $this->newRepository->getAllByParams(
            [
                'language_id' => $this->language['id']
            ],
            self::NEWS_COUNT,
            $offset
        );

        foreach ($moreArticles as $key => $article) {
            $date = new DateTime($article['date']);
            $articles[$key]['created_at'] = $date->format('d/m/Y');
        }

        return [
            'newList' => $moreArticles,
        ];
    }

    public function checkLastPage($count)
    {
        $lastPage = false;

        $allArticles = $this->newRepository->getAllByParams();

        if (count($allArticles) == $count) {
            $lastPage = true;
        }

        return $lastPage;
    }
}
