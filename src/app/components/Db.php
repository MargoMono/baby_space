<?php

namespace App\Components;

use PDO;

class Db
{
    public static function getConnection()
    {
        // Получаем параметры подключения из файла
        $paramsPath = 'config/db_params.php';
        $params = include($paramsPath);

        // Устанавливаем соединение
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // Задаем кодировку
        $db->exec("set names utf8");

        return $db;
    }
}

