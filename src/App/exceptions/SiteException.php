<?php

namespace App\Exceptions;

use RuntimeException;

class SiteException extends RuntimeException
{
    public const TOO_FREQUENT_COMMENT = 10;

    public function __construct($code) {
        $message = $this->codeToMessage($code);
        parent::__construct($message, $code);
    }

    private function codeToMessage($code)
    {
        include_once 'language/' . $_SESSION['language'] . '.php';

        switch ($code) {
            case self::TOO_FREQUENT_COMMENT :
                $message = $_['too_frequent_comment'] ;
                break;

            default:
                $message = 'unknown_error';
                break;
        }
        return $message;
    }
}