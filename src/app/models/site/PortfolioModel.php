<?php

namespace App\Model\Site;

use App\Components\Model;
use App\NewsRepository;
use App\Repository\PortfolioRepository;
use DateTime;
use Exception;

class PortfolioModel extends Model
{
    const PHOTO_COUNT = 8;

    public function getIndexData()
    {
        $lastPage = 0;

        $portfolioRepository = new PortfolioRepository();
        $photoList = $portfolioRepository->getLastPhotos(self::PHOTO_COUNT);
        $fullPhotoList = $portfolioRepository->getPortfolioList();

        if (count($fullPhotoList) <= self::PHOTO_COUNT) {
            $lastPage = 1;
        }

        $params = [
            'photoList' => $photoList,
            'lastPage' => $lastPage,
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

        $portfolioRepository = new PortfolioRepository();
        $photoList = $portfolioRepository->getMorePhotos($count, self::PHOTO_COUNT);

        if (count($photoList) !== self::PHOTO_COUNT) {
            $lastPage = 1;
        }


        $params = [
            'photoList' => $photoList,
            'lastPage' => $lastPage,
        ];

        return $params;
    }
}

