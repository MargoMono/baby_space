<?php

namespace App\Middleware;

class AdminAuthenticationChecking
{
    public function handle()
    {
        $user = $_SESSION['user'];

        if (!isset($user)) {
            header('Location: /user/login');
        }
    }
}
