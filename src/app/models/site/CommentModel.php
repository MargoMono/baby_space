<?php

namespace App\Model\Site;

use App\Components\Model;
use App\Helper\FileHelper;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use App\Repository\FileRepository;
use DateTime;
use Exception;
use RuntimeException;

class CommentModel extends Model
{
    const COMMENTS_COUNT = 5;

    /**
     * @return array|void
     * @throws Exception
     */
    public function getIndexData()
    {
        $lastPage = 0;

        $commentRepository = new CommentRepository();
        $comments = $commentRepository->getLastAllowedComments(self::COMMENTS_COUNT);
        $allComments = $commentRepository->getAllAllowedComments();

        foreach ($comments as $key => $comment) {
            $date = new DateTime($comment['date']);
            $comments[$key]['created_at'] = $date->format('d/m/Y');
            $comments[$key]['photos'] = $commentRepository->getCommentPhotos($comment['id']);
        }

        if (count($allComments) <= self::COMMENTS_COUNT) {
            $lastPage = 1;
        }

        $comments = $commentRepository->getArrayWithIdAsKey($comments);

        $commentList = [];
        foreach ($comments as $key => &$comment) {
            if (empty($comment['parent_id'])) {
                $commentList[$key] = &$comment;
            } else {
                $comments[$comment['parent_id']]['children'][$key] = &$comment;
            }
        }

        $params = [
            'commentList' => $commentList,
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

        $commentRepository = new CommentRepository();
        $comments = $commentRepository->getMoreAllowedComments($count, self::COMMENTS_COUNT);

        foreach ($comments as $key => $comment) {
            $date = new DateTime($comment['date']);
            $comments[$key]['created_at'] = $date->format('d/m/Y');
            $comments[$key]['photos'] = $commentRepository->getCommentPhotos($comment['id']);
        }

        if (count($comments) !== self::COMMENTS_COUNT) {
            $lastPage = 1;
        }

        $params = [
            'commentList' => $comments,
            'lastPage' => $lastPage,
        ];

        return $params;
    }

    public function createComment($files, $params)
    {
        $res['result'] = false;

        $fileHelper = new FileHelper();

        try {
            $imagesName = $fileHelper->uploadFiles($files['files'], 'comment');
        } catch (RuntimeException $e) {
            $res['errors'][] = $e->getMessage();
            return $res;
        }

        $commentRepository = new CommentRepository();
        $previousOrder = $commentRepository->getClientByEmail($params['user_email']);

        if ($previousOrder) {
            $now = new DateTime();
            $previousOrderDate = new DateTime($previousOrder['created_at']);
            $interval = $now->diff($previousOrderDate);
            if ($interval->h < 1) {
                $res['errors'][] = 'Отзыв можно оставлять с частотой не более 1 раза в час';
                return $res;
            }
        }

        $newCommentId = $commentRepository->createComment($params);

        if (empty($newCommentId)) {
            $res['errors'][] = 'Не удалось создать комментарий';
            return $res;
        }

        if (!empty($imagesName)) {

            foreach ($imagesName as $imageName) {

                $fileRepository = new FileRepository();
                $fileId = $fileRepository->createFile($imageName);

                if (empty($fileId)) {
                    $res['errors'][] = 'Не удалось создать файл';
                    return $res;
                }

                $fileCommentConnection = $commentRepository->createFileCommentConnection($newCommentId, $fileId);

                if (empty($fileCommentConnection)) {
                    ;
                    $res['errors'][] = 'Не удалось создать связь между фото и комментарием';
                    return $res;
                }
            }

        }

        $res['result'] = true;
        return $res;
    }
}
