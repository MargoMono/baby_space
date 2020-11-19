<?php

var_dump(phpinfo());
die();

include __DIR__ . '/vendor/autoload.php';

use App\Components\Language;
use App\Components\Route;

session_start();

$languageDetect = new Language();
$languageDetect->setContent();

$routing = new Route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
$routing->upload();
