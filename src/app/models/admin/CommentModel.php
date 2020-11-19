<?php

namespace App\Models\Admin;

use App\Models\Model;
use App\Modules\FileUploader;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\FileRepository;
use DateTime;
use Exception;
use RuntimeException;

class CommentModel extends Model
{
    /**
     * @param $order
     * @return array|void
     * @throws Exception
     */
    public function getIndexData($order)
    {
        $commentRepository = new CommentRepository();
        $commentList = $commentRepository->getCommentList($order);

        foreach ($commentList as $key => $comment) {
            $date = new DateTime($comment['date']);
            $commentList[$key]['created_at'] = $date->format('d/m/Y');
            $commentList[$key]['photos'] = $commentRepository->getCommentPhotos($comment['id']);
        }

        $data['commentList'] = $commentList;

        return $data;
    }

    public function publish($id)
    {
        $res['result'] = false;

        $commentRepository = new CommentRepository();

        if (!$commentRepository->publishCommentById($id)) {
            $res['errors'][] = "Не удалось опубликовать отзыв";
            return $res;
        }

        $res['result'] = true;
        return $res;
    }


    /**
     * @param $id
     * @return array
     */
    public function getShowCreatePageData($id)
    {
        $categoryRepository = new CommentRepository();
        $data['comment'] = $categoryRepository->getCommentById($id);

        return $data;
    }

    public function create($files, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        $params['user_name'] = "Администратор";
        $params['user_email'] = $_SESSION['user']['email'];
        $params['allow'] = 1;

        if(!empty($files)){
            try {
                $imageList = $fileUploader->uploadSeveral($files, 'comment');
            } catch (RuntimeException $e) {
                $res['errors'][] = $e->getMessage();
                return $res;
            }

            if (empty($imageList)) {
                $res['errors'][] = 'Ошибка при загрузке изображения';
                return $res;
            }
        }

        $commentRepository = new CommentRepository();
        $newCommentId = $commentRepository->createComment($params);

        if (empty($newCommentId)) {
            $res['errors'][] = 'Не удалось создать комментарий';
            return $res;
        }


        if (!empty($imageList)) {

            foreach ($imageList as $image) {

                $fileRepository = new FileRepository();
                $fileId = $fileRepository->createFile($image);

                if (empty($fileId)) {
                    $res['errors'][] = 'Не удалось создать файл';
                    return $res;
                }

                $fileCommentConnection = $commentRepository->createFileCommentConnection($newCommentId, $fileId);

                if (empty($fileCommentConnection)) {

                    $res['errors'][] = 'Не удалось создать связь между фото и комментарием';
                    return $res;
                }
            }

        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new CommentRepository();
        $data['comment'] = $categoryRepository->getCommentById($id);

        return $data;
    }

    public function delete($data)
    {
        $res['result'] = false;

        $commentRepository = new CommentRepository();

        $childCommentList = $commentRepository->getChildCommentListById($data['id']);

        $childCategoryId = [];

        foreach ($childCommentList as $childComment) {
            $childCategoryId[] = $childComment['id'];
        }

        $childCategoryId = implode(', ', $childCategoryId);

        if (!empty($childCategoryId)) {
            $res['errors'][] = "невозможно удалить отзыв при наличии ответа на него - ($childCategoryId)";
            return $res;
        }

        if ($commentRepository->deleteCommentById($data['id'])) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении комментария";

        return $res;
    }
}
