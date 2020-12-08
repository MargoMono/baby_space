<?php

include __DIR__ . '/vendor/autoload.php';

use App\Components\Rates;
use App\Components\Route;

session_start();

$rates = new Rates();
$rates->getRates();

$routing = new Route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
$routing->upload();
