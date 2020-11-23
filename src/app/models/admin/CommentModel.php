<?php

namespace App\Models\Admin;

use App\Helpers\FileUploaderHelper;
use App\Repository\CommentRepository;

class CommentModel implements ModelStrategy
{
    public $fileDirectory = 'comment';

    private $commentRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
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
    }

    public function create($data)
    {
    }

    public function getShowUpdatePageData($id)
    {
        $data['comment'] = $this->commentRepository->getById($id);
        $data['answer'] = $this->commentRepository->getAnswerByCommentId($id);
        $data['commentImages'] = $this->commentRepository->getFilesByCommentId($id);

        return $data;
    }

    public function update($data)
    {
        $this->commentRepository->updateById($data['comment']);

        if (empty($this->commentRepository->getAnswerByCommentId($data['comment']['id']))) {
            $this->commentRepository->createAnswer($data['comment_answer']);
        } else {
            $this->commentRepository->updateAnswerById($data['comment_answer']);
        }
    }

    public function getShowDeletePageData($id)
    {
        $data['comment'] = $this->commentRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        return $this->commentRepository->deleteById($id);
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

    public function prepareData($params)
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
                'comment_id' => $params['id'],
                'description' => $params['answer_description'],
            ]
        ];
    }
}
