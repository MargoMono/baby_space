<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Models\Model;
use App\Repository\BlogRepository;
use App\Repository\LanguageRepository;
use DateTime;
use Exception;

class BlogModel extends Model
{
    const ARTICLES_COUNT = 4;

    /**
     * @return array|void
     * @throws Exception
     */
    public function getIndexData()
    {
        $lastPage = 0;

        $language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());

        $blogRepository = new BlogRepository();
        $lastArticles = $blogRepository->getLastByLanguageId($language['id'], self::ARTICLES_COUNT);

        foreach ($lastArticles as $key => $article) {
            $date = new DateTime($article['created_at']);
            $lastArticles[$key]['created_at'] = $date->format('d/m/Y');
        }

        $allArticles = $blogRepository->getAllByLanguageId($language['id']);

        if (count($allArticles) <= self::ARTICLES_COUNT) {
            $lastPage = 1;
        }

        return [
            'articleList' => $lastArticles,
            'lastPage' => $lastPage,
        ];
    }

    public function getShowOneData($id)
    {
        $newsRepository = new BlogRepository();
        $new = $newsRepository->getById($id);

        $params = [
            'article' => $new,
        ];

        return $params;
    }

    /**
     * @param $count
     * @return array
     * @throws Exception
     */
    public function getShowMoreData($count)
    {
        $lastPage = 0;

        $newsRepository = new BlogRepository();
        $articles = $newsRepository->getMoreArticles($count, self::ARTICLES_COUNT);

        foreach ($articles as $key => $article) {
            $date = new DateTime($article['date']);
            $articles[$key]['created_at'] = $date->format('d/m/Y');
        }

        if (count($articles) !== self::ARTICLES_COUNT) {
            $lastPage = 1;
        }

        $params = [
            'articles' => $articles,
            'lastPage' => $lastPage,
        ];

        return $params;
    }
}
