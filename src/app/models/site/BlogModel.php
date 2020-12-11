<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Repository\BlogRepository;
use App\Repository\LanguageRepository;
use DateTime;
use Exception;

class BlogModel
{
    const ARTICLES_COUNT = 3;

    private $language;
    private $blogRepository;

    public function __construct()
    {
        $this->language =(new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->blogRepository =  new BlogRepository();
    }

    /**
     * @return array|void
     * @throws Exception
     */
    public function getIndexData() : array
    {
        $lastArticles = $this->blogRepository->getLastByLanguageId($this->language['id'], self::ARTICLES_COUNT);

        foreach ($lastArticles as $key => $article) {
            $date = new DateTime($article['created_at']);
            $lastArticles[$key]['created_at'] = $date->format('d/m/Y');
        }

        return [
            'articleList' => $lastArticles,
        ];
    }

    /**
     * @param $offset
     * @return array
     * @throws Exception
     */
    public function getShowMoreData($offset): array
    {
        $moreArticles = $this->blogRepository->getMoreByLanguageId($this->language['id'], self::ARTICLES_COUNT, $offset);

        foreach ($moreArticles as $key => $article) {
            $date = new DateTime($article['date']);
            $articles[$key]['created_at'] = $date->format('d/m/Y');
        }

        return [
            'articleList' => $moreArticles,
        ];
    }


    public function checkLastPage($count)
    {
        $lastPage = false;

        $allArticles = $this->blogRepository->getAllByLanguageId($this->language['id']);

        if (count($allArticles) == $count) {
            $lastPage = true;
        }

        return $lastPage;
    }

    public function getShowOneData($id)
    {
        return [
            'article' => $this->blogRepository->getByIdAndLanguageId($id, $this->language['id']),
        ];
    }
}
