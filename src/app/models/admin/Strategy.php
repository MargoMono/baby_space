<?php

namespace App\Model\Admin;

interface Strategy
{
    public function getRepository();

    public function prepareData($params);
}
