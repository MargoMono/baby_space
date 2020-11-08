<?php

namespace App\Model\Admin;

use App\Helper\TextHelper;
use App\Repository\NewRepository;

class NewStrategy implements Strategy
{
    public $fileDirectory = 'new';

    public function getRepository()
    {
        return new NewRepository();
    }

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'name' => $params['name'],
            'description' => $params['description'],
            'content' => $params['content'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name']),
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];
    }
}