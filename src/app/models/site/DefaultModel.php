<?php

namespace App\Model\Site;

use DateTime;
use Exception;

class DefaultModel extends Model
{
    public function getDefaultData()
    {
        return  $this->getIndexData();
    }
}
