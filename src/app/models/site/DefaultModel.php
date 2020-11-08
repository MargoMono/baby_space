<?php

namespace App\Model\Site;

use App\Model\Model;


class DefaultModel extends Model
{
    public function getDefaultData()
    {
        return  $this->getIndexData();
    }
}
