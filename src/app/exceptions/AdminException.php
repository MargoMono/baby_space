<?php

namespace App\Exceptions;

use RuntimeException;

class AdminException extends RuntimeException
{
    public const USER_PASSWORD_CONFIRM_ERROR = 10;
    public const USER_ALREADY_EXIST = 11;

    public function __construct($code) {
        $message = $this->codeToMessage($code);
        parent::__construct($message, $code);
    }

    private function codeToMessage($code)
    {
        switch ($code) {
            case self::USER_PASSWORD_CONFIRM_ERROR:
                $message = 'пароли не совпадают';
                break;
            case self::USER_ALREADY_EXIST:
                $message = 'пользователь с такой почтой уже существует';
                break;

            default:
                $message = 'Unknown error';
                break;
        }
        return $message;
    }
}