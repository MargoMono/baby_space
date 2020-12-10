<?php

namespace App\Models\Admin;

use App\Helpers\FileUploaderHelper;
use App\Repository\CommentAnswerDescriptionRepository;
use App\Repository\CommentDescriptionRepository;
use App\Repository\CommentRepository;
use App\Repository\FileRepository;
use App\Repository\LanguageRepository;

class CommentModel implements ModelStrategy
{
    public $fileDirectory = 'comment';

    private $languageRepository;
    private $commentRepository;
    private $commentDescriptionRepository;
    private $commentAnswerDescriptionRepository;
    private $fileUploader;
    private $fileRepository;

    public function __construct()
    {
        $this->languageRepository = new LanguageRepository();
        $this->commentRepository = new CommentRepository();
        $this->commentDescriptionRepository = new CommentDescriptionRepository();
        $this->commentAnswerDescriptionRepository = new CommentAnswerDescriptionRepository();
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
        $languages = $this->languageRepository->getAll();

        $comment = $this->commentRepository->getById($id);

        foreach ($languages as $key => $language) {
            $languages[$key]['comment'] = $this->commentDescriptionRepository->getByIdAndLanguageId($comment['id'],
                $language['id']);
        }

        $data['comment'] = $comment;
        $data['commentImages'] = $this->commentRepository->getFilesByCommentId($id);

        $commentAnswer = $this->commentRepository->getAnswerByCommentId($id);

        foreach ($languages as $key => $language) {
            $languages[$key]['commentAnswer'] = $this->commentAnswerDescriptionRepository->getByIdAndLanguageId($commentAnswer['id'],
                $language['id']);
        }

        $data['commentAnswer'] = $commentAnswer;
        $data['commentAnswerImages'] = $this->commentRepository->getAnswerFilesByAnswerCommentId($commentAnswer['id']);

        $data['languages'] = $languages;

        return $data;
    }

    public function update($file, $data)
    {
        $this->commentRepository->updateById($data['comment']);

        foreach ($data['comment']['description'] as $description) {
            $productDescriptionExist = $this->commentDescriptionRepository->getByIdAndLanguageId($data['comment']['id'],
                $description['language_id']);

            if (empty($productDescriptionExist)) {
                $this->commentDescriptionRepository->create($data['comment']['id'], $description);
            } else {
                $this->commentDescriptionRepository->updateById($description);
            }
        }

        if (empty($data['comment_answer']['id'])) {
            $answerId = $this->commentRepository->createAnswer($data['comment_answer']);
        } else {
            $answerId = $data['comment_answer']['id'];
        }

        foreach ($data['comment_answer']['description'] as $description) {
            $productDescriptionExist = $this->commentAnswerDescriptionRepository->getByIdAndLanguageId($data['comment_answer']['id'],
                $description['language_id']);

            if (empty($productDescriptionExist)) {
                $this->commentAnswerDescriptionRepository->create($data['comment_answer']['id'], $description);
            } else {
                $this->commentAnswerDescriptionRepository->updateById($description);
            }
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
        $languages = $this->languageRepository->getAll();

        $paramsCommentDescription = [];

        foreach ($languages as $language) {
            $paramsCommentDescription[$language['id']] = [
                'language_id' => $language['id'],
                'id' => $params['id-' . $language['id']],
                'description' => $params['description-' . $language['id']],
            ];
        }

        $paramsCommentAnswerDescription = [];

        foreach ($languages as $language) {
            $paramsCommentAnswerDescription[$language['id']] = [
                'language_id' => $language['id'],
                'id' => $params['answer_id-' . $language['id']],
                'description' => $params['answer_description-' . $language['id']],
            ];
        }

        return [
            'comment' => [
                'id' => $params['id'],
                'user_name' => $params['user_name'],
                'user_email' => $params['user_email'],
                'status' => $params['status'],
                'description' => $paramsCommentDescription,
            ],
            'comment_answer' => [
                'id' => $params['answer_id'],
                'comment_id' => $params['id'],
                'description' => $paramsCommentAnswerDescription,
            ]
        ];
    }
}
