<?php

namespace App\Models\Admin;

use App\Helpers\FileUploaderHelper;
use App\Repository\CommentRepository;
use App\Repository\FileRepository;

class CommentModel implements ModelStrategy
{
    public $fileDirectory = 'comment';

    private $commentRepository;
    private $fileUploader;
    private $fileRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
        $this->fileUploader = new FileUploaderHelper();
        $this->fileRepository = new FileRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null): array
    {
        $data['commentList'] = $this->commentRepository->getAll($sort);

        if ($sort['desc'] == 'DESC') {
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        return $data;
    }

    public function getShowCreatePageData($sort = null)
    {
        return null;
    }

    public function create($data)
    {
        return null;
    }

    public function getShowUpdatePageData($id)
    {
        $data['comment'] = $this->commentRepository->getById($id);
        $data['commentImages'] = $this->commentRepository->getFilesByCommentId($id);

        $commentAnswer = $this->commentRepository->getAnswerByCommentId($id);

        $data['commentAnswer'] = $commentAnswer;
        $data['commentAnswerImages'] = $this->commentRepository->getAnswerFilesByAnswerCommentId($commentAnswer['id']);

        return $data;
    }

    public function update($file, $data)
    {
        $this->commentRepository->updateById($data['comment']);

        if (empty($data['comment_answer']['id'])) {
            $answerId = $this->commentRepository->createAnswer($data['comment_answer']);
        } else {
            $answerId = $data['comment_answer']['id'];
            $this->commentRepository->updateAnswerById($data['comment_answer']);
        }

        if (!empty($file['files_answer']['name'][0])) {
            $imageList = $this->fileUploader->uploadSeveral($file['files_answer'], $this->getFileDirectory());

            if (empty($imageList)) {
                throw new \RuntimeException('Can\'t create files connection - information about uploaded files is empty');
            }

            foreach ($imageList as $image) {
                $fileId = $this->fileRepository->createFile($image);
                $this->commentRepository->createCommentAnswerFile($answerId, $fileId);
            }
        }
    }

    public function getShowDeletePageData($id)
    {
        $data['comment'] = $this->commentRepository->getById($id);

        return $data;
    }

    public function commentAnswerImageDelete($commentId, $commentAnswerId, $imageId)
    {
        try {
            $file = $this->fileRepository->getFileById($imageId);
            $this->fileUploader->deleteFile($file['alias'], $this->fileDirectory);
            $this->commentRepository->deleteAnswerFileConnection($commentAnswerId, $imageId);
            $_SESSION['success'] = 'Изображение удалено успешно';
            header("Location: /admin/$this->fileDirectory/update/$commentId");
        } catch (\Exception $exception) {
            $_SESSION['error_warning'] = 'Не удалось удалить изображение, обратитесь к разработчику';
            header("Location: /admin/$this->fileDirectory/update/$commentId");
        }
    }

    public function delete($id)
    {
        $this->commentRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
        $this->commentRepository->createFilesConnection($id, $fileId);
    }

    public function deleteFileConnection($id, $imageId)
    {
        $this->commentRepository->deleteFileConnection($id, $imageId);
    }

    public function getFile($id)
    {
        return null;
    }

    public function getFiles($id)
    {
        return $this->commentRepository->getFilesByCommentId($id);
    }

    public function prepareData($params): array
    {
        return [
            'comment' => [
                'id' => $params['id'],
                'user_name' => $params['user_name'],
                'user_email' => $params['user_email'],
                'description' => $params['description'],
                'status' => $params['status'],
            ],
            'comment_answer' => [
                'id' => $params['answer_id'],
                'comment_id' => $params['id'],
                'description' => $params['answer_description'],
            ]
        ];
    }
}
