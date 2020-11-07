<?php

namespace App\Model\Site;

use App\Components\Model;
use App\Repository\NewRepository;
use DateTime;
use Exception;

class NewModel extends Model
{
    const NEWS_COUNT = 4;

    /**
     * @return array|void
     * @throws Exception
     */
    public function getIndexData()
    {
        $lastPage = 0;

        $newRepository = new NewRepository();
        $newList = $newRepository->getLastNewList(self::NEWS_COUNT);
        $allArticles = $newRepository->getAllNewList();

        foreach ($newList as $key => $article) {
            $date = new DateTime($article['date']);
            $newList[$key]['created_at'] = $date->format('d/m/Y');
        }

        if (count($allArticles) <= self::NEWS_COUNT) {
            $lastPage = 1;
        }

        $params = [
            'newList' => $newList,
            'lastPage' => $lastPage,
        ];

        return $params;
    }

    public function getShowOneData($id)
    {
        $newsRepository = new NewRepository();
        $new = $newsRepository->getNewById($id);

        $params = [
            'new' => $new,
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

        $newsRepository = new NewRepository();
        $moreNews = $newsRepository->getMoreNews($count, self::NEWS_COUNT);

        foreach ($moreNews as $key => $article) {
            $date = new DateTime($article['date']);
            $moreNews[$key]['created_at'] = $date->format('d/m/Y');
        }

        if (count($moreNews) !== self::NEWS_COUNT) {
            $lastPage = 1;
        }

        $params = [
            'newList' => $moreNews,
            'lastPage' => $lastPage,
        ];

        return $params;
    }
}
