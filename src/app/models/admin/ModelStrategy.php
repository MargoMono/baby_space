<?php

namespace App\Model\Admin;

interface ModelStrategy
{
    public function prepareData(array $params);
}