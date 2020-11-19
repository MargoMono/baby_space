<?php

namespace App\Models\Site;

use App\Models\Model;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PortfolioRepository;
use DateTime;
use Exception;

class IndexModel extends Model
{
    /**
     * @return array|void
     * @throws Exception
     */
    public function getMainPageData()
    {
        $newsRepository = new BlogRepository();
        $news = $newsRepository->getLastArticles(4);

        $commentRepository = new CommentRepository();
        $comments = $commentRepository->getLastComments(3);

        foreach ($comments as $key => $comment) {
            $date = new DateTime($comment['date']);
            $comments[$key]['created_at'] = $date->format('d/m/Y');
            $comments[$key]['photos'] = $commentRepository->getLimitCommentPhotos($comment['id'], 1);
        }

        $portfolioRepository = new PortfolioRepository();
        $photos = $portfolioRepository->getLastPhotos(8);

        $categoryRepository = new CategoryRepository();
        $mainCategoryList = $categoryRepository->getMainCategoryList();

        foreach ($news as $key => $new) {
            $date = new DateTime($new['date']);
            $news[$key]['date'] = $date->format('d/m/Y');
        }

        $params = [
            'news' => $news,
            'mainCategoryList' => $mainCategoryList,
            'comments' => $comments,
            'photos' => $photos,
        ];

        return $params;
    }
}
