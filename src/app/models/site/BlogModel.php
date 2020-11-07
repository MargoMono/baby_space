<?php

namespace App\Model\Site;

use App\Components\Model;
use App\Repository\BlogRepository;
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

        $blogRepository = new BlogRepository();
        $articles = $blogRepository->getLastArticles(self::ARTICLES_COUNT);
        $allArticles = $blogRepository->getAllArticles();

        foreach ($articles as $key => $article) {
            $date = new DateTime($article['date']);
            $articles[$key]['created_at'] = $date->format('d/m/Y');
        }

        if (count($allArticles) <= self::ARTICLES_COUNT) {
            $lastPage = 1;
        }

        $params = [
            'articles' => $articles,
            'lastPage' => $lastPage,
        ];

        return $params;
    }

    public function getShowOneData($id)
    {
        $newsRepository = new BlogRepository();
        $new = $newsRepository->getNewById($id);

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
