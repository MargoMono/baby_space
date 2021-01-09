<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Exceptions\SiteException;
use App\Helpers\FileUploaderHelper;
use App\Repository\CommentAnswerDescriptionRepository;
use App\Repository\CommentDescriptionRepository;
use App\Repository\CommentRepository;
use App\Repository\FileRepository;
use App\Repository\LanguageRepository;
use DateTime;
use Exception;

class CommentModel
{
    const COMMENTS_COUNT = 1;
    /**
     * @var mixed
     */
    private $language;
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var CommentDescriptionRepository
     */
    private $commentDescriptionRepository;
    /**
     * @var CommentAnswerDescriptionRepository
     */
    private $commentAnswerDescriptionRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->commentRepository = new CommentRepository();
        $this->commentDescriptionRepository = new CommentDescriptionRepository();
        $this->commentAnswerDescriptionRepository = new CommentAnswerDescriptionRepository();
    }

    /**
     * @return array|void
     * @throws Exception
     */
    public function getIndexData()
    {
        $comments = $this->commentRepository->getAllByParams([
            'language_id' => $this->language['id'],
        ], self::COMMENTS_COUNT);

        foreach ($comments as $key => $comment) {
            $date = new DateTime($comment['date']);
            $comments[$key]['created_at'] = $date->format('d/m/Y');
            $comments[$key]['images'] = $this->commentRepository->getFilesByCommentId($comment['id']);
            $answer = $this->commentRepository->getAnswerByCommentId($comment['id']);
            $answerDate = new DateTime($comment['date']);
            $comments[$key]['answer']['images'] = $this->commentRepository->getAnswerFilesByAnswerCommentId($answer['id']);
            $comments[$key]['answer']['created_at'] = $answerDate->format('d/m/Y');
            $description = $this->commentAnswerDescriptionRepository->getByIdAndLanguageId(
                $answer['id'],
                $this->language['id']
            );
            $comments[$key]['answer']['description'] = $description['description'];
        }

        return [
            'commentList' => $comments
        ];
    }

    public function checkLastPage($params)
    {
        $lastPage = false;

        $allComments = $this->commentRepository->getAllByParams([
            'language_id' => $this->language['id'],
        ]);

        if (count($allComments) == $params['count']) {
            $lastPage = true;
        }

        return $lastPage;
    }

    public function getShowMoreData($params)
    {
        $params['language_id'] = $this->language['id'];

        $comments = $this->commentRepository->getAllByParams(
            $params,
            self::COMMENTS_COUNT,
            $params['count']
        );

        foreach ($comments as $key => $comment) {
            $date = new DateTime($comment['date']);
            $comments[$key]['created_at'] = $date->format('d/m/Y');
            $comments[$key]['images'] = $this->commentRepository->getFilesByCommentId($comment['id']);
            $answer = $this->commentRepository->getAnswerByCommentId($comment['id']);
            $answerDate = new DateTime($comment['date']);
            $comments[$key]['answer']['images'] = $this->commentRepository->getAnswerFilesByAnswerCommentId($answer['id']);
            $comments[$key]['answer']['created_at'] = $answerDate->format('d/m/Y');
            $description = $this->commentAnswerDescriptionRepository->getByIdAndLanguageId(
                $answer['id'],
                $this->language['id']
            );
            $comments[$key]['answer']['description'] = $description['description'];
        }

        return [
            'commentList' => $comments
        ];
    }

    public function createComment($files, $params)
    {
        $fileUploader = new FileUploaderHelper();

        $previousComment = $this->commentRepository->getByParams([
            'user_email' => $params['user_email']
        ]);

        if ($previousComment) {
            $now = new DateTime();
            $previousOrderDate = new DateTime($previousComment['created_at']);
            $interval = $now->diff($previousOrderDate);
            if ($interval->h < 1) {
                throw new SiteException(SiteException::TOO_FREQUENT_COMMENT);
            }
        }

        $newCommentId = $this->commentRepository->create([
            'user_name' => $params['user_name'],
            'user_email' => $params['user_email'],
            'status' => 0
        ]);

        $this->commentDescriptionRepository->create($newCommentId, [
            'language_id' => $this->language['id'],
            'description' => $params['description']
        ]);

        $imagesName = $fileUploader->uploadSeveral($files['files'], 'comment');

        if (!empty($imagesName)) {

            foreach ($imagesName as $imageName) {

                $fileRepository = new FileRepository();
                $fileId = $fileRepository->createFile($imageName);
                $this->commentRepository->createFilesConnection($newCommentId, $fileId);
            }
        }
    }
}
