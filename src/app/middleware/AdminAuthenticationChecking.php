<?php

namespace App\Middleware;

class AdminAuthenticationChecking
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /user/login');
        }
    }
}
