<?php

namespace App\Model\Site;

use App\Components\Model;
use App\Controller\Site\MailController;
use App\Mailer;
use App\Repository\UserRepository;
use PHPMailer\PHPMailer\Exception;

class UserModel extends Model
{
    public function actionLogin()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userRepository = new UserRepository;

        $res['result'] = false;

        $user = $userRepository->getUserByEmail($email);

        if (md5(md5($password) . $user['salt']) == $user['password']) {
            $_SESSION['user'] = $user;
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "Неправильный логин или пароль";

        return $res;
    }

    public function actionRestorePassword($data)
    {
        $userRepository = new UserRepository;

        $res['result'] = false;

        $user = $userRepository->getUserByEmail($data['email']);

        if (empty($user)) {
            $res['errors'][] = "Почты с таким пользователем не существует";
        }

        $emailParams = [
            'name' => $user['name'],
            'active_hex' => $user['active_hex'],
            'base_url' => $_SERVER['HTTP_HOST'],
        ];


        $mailModel = new MailController();
        $body = $mailModel->getTemplate('resetPassword.twig', $emailParams);
        $subject = "Восстановление пароля на сайте Кдф-трейдинг.рф";

        $mailer = new Mailer($subject, $body, $user['email'], $user['name']);

        try {
            $mailer->send();
            $res['result'] = true;
            $res['success'] = true;
            return $res;
        } catch (Exception $e) {
            return $res;
        }
    }

    public function showUpdatePasswordPage($activeHex)
    {
        $userRepository = new UserRepository;

        $res['result'] = false;

        $user = $userRepository->getUserByActiveHex($activeHex);

        if (empty($user)) {
            $res['errors'][] = "При запросе на обновление пароля произошла ошибка, обратитесь к администатору";
            return $res;
        }

        $res['result'] = true;
        $res['active_hex'] = $activeHex;

        return $res;
    }

    public function updatePassword($params)
    {
        $userRepository = new UserRepository;

        $res['result'] = false;

        $user = $userRepository->getUserByActiveHex($params['active_hex']);

        if ($params['password'] != $params['confirm_password']) {
            $res['errors'][] = "Пароли не совпадают";
            return $res;
        }

        if (empty($user)) {
            $res['errors'][] = "При запросе на обновление пароля произошла ошибка, обратитесь к администатору";
            return $res;
        }

        $salt = $this->salt();

        $data = [
            'password' => md5(md5($_POST['password']) . $salt),
            'salt' => $salt,
            'new_active_hex' => md5($salt),
            'old_active_hex' => $params['active_hex'],
        ];

        if (!$userRepository->updateUserPassword($data)) {
            $res['errors'][] = 'Ошибка обноления пароля, обратитесь к администатору';
            return $res;
        }

        $res['result'] = true;
        $res['success'] = true;
        return $res;
    }

    private function salt()
    {
        return substr(md5(uniqid()), -8);
    }
}
