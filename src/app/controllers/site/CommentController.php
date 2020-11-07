<?php

namespace App\Controller\Site;

use App\Components\Controller;
use App\Mailer;
use App\Model\Site\CommentModel;

class CommentController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new CommentModel();
    }

    public function index()
    {
        $data = $this->model->getIndexData();
        $data['page'] = 'company';
        $this->view->generate('/site/comment/index.twig', $data);
    }

    public function showMore($count)
    {
        $data = $this->model->getShowMoreData($count);
        $this->view->generate('site/comment/showMore.twig', $data);
    }

    public function createComment()
    {
        $data = $this->model->createComment($_FILES, $_POST);

        if (empty($data['errors'])) {
            $emailParams = [
                'user_name' => $_POST['user_name'],
                'base_url' => $_SERVER['HTTP_HOST'],
            ];

            $mailModel = new MailController();
            $body = $mailModel->getTemplate('newComment.twig', $emailParams);
            $subject = 'Новый отзыв сайте Кдф-трейдинг.рф';

            $mailer = new Mailer($subject, $body, 'kdf_16@mail.ru', 'КДФ');
            $mailer->send();
        }

        $this->view->generateAjax($data);
        return;
    }
}
