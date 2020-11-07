<?php

namespace App\Model\Admin;

use App\Components\Model;
use App\Helper\FileHelper;
use App\Components\AdminBase;
use App\Repository\FileRepository;
use App\Repository\NewRepository;
use RuntimeException;

class NewModel extends Model
{
    private $fileDirectory = 'new';

    public function getIndexData($order)
    {
        $newRepository = new NewRepository();
        $data['newList'] = $newRepository->getNewList($order);

        return $data;
    }

    public function getShowCreatePageData()
    {
        $newRepository = new NewRepository();
        $data['newList'] = $newRepository->getNewList();
        return $data;
    }

    public function create($file, $params)
    {
        $res['result'] = false;

        $fileHelper = new FileHelper();

        try {
            $alias = $fileHelper->uploadFile($file,  $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();
        $params['file_id'] = $fileRepository->createFile($alias);

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $newRepository = new NewRepository();
        $new = $newRepository->createNew($this->prepareData($params));

        if (empty($new)) {
            $res['errors'][] = 'Ошибка сохранения новости';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowUpdatePageData($id)
    {
        $categoryRepository = new NewRepository();
        $data['new'] = $categoryRepository->getNewById($id);

        return $data;
    }

    public function update($file, $params)
    {
        $res['result'] = false;

        $fileHelper = new FileHelper();

        try {
            $alias = $fileHelper->uploadFile($file,  $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e->getMessage();
            return $res;
        }

        if (!empty($alias)) {
            $fileHelper->deleteFile($params['file_alias'], $this->fileDirectory);

            $fileRepository = new FileRepository();
            $params['file_id'] = $fileRepository->createFile($alias);
        }

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $categoryRepository = new NewRepository();
        $newCategory = $categoryRepository->updateNew($this->prepareData($params));

        if (empty($newCategory)) {
            $res['errors'][] = 'Ошибка сохранения статьи';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new NewRepository();
        $data['new'] = $categoryRepository->getNewById($id);

        return $data;
    }

    public function delete($data)
    {
        $res['result'] = false;

        $categoryRepository = new NewRepository();

        if ($categoryRepository->deleteCategoryById($data['id'])) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении статьи";

        return $res;
    }

    private function prepareData($params)
    {
        $data = [
            'id' => $params['id'],
            'name' => $params['name'],
            'description' => $params['description'],
            'content' => $params['content'],
            'file_id' => $params['file_id'],
            'alias' => AdminBase::getTranslit($params['name']),
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];

        return $data;
    }
}

