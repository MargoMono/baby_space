<?php

namespace App\Models\Site;

use App\Models\Models;


class DefaultModel extends Model
{
    public function getDefaultData()
    {
        return  $this->getIndexData();
    }
}
