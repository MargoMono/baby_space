<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\LanguageRepository;
use App\Repository\NewRepository;

class NewModel implements ModelStrategy
{
    public $fileDirectory = 'new';

    private $newRepository;


    public function __construct()
    {
        $this->newRepository = new NewRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['newList'] = $this->newRepository->getAll($sort);

        if($sort['desc'] == 'DESC'){
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;


        return $data;
    }

    public function getShowCreatePageData($sort = null)
    {
        $data['newList'] = $this->newRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->newRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['new'] = $this->newRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        return $this->newRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['new'] = $this->newRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        return $this->newRepository->deleteById($id);
    }

    public function getFile($id)
    {
        return $this->newRepository->getFileByEntityId($id);
    }

    public function getFiles($id)
    {
        // TODO: Implement getFiles() method.
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

    public function validation($file, $params)
    {
        // TODO: Implement validation() method.
    }
}