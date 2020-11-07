<?php

namespace App\Model\Admin;

use App\Helper\FileHelper;
use App\Repository\FileRepository;
use App\Repository\Repository;

class ModelContext
{
    private $strategy;
    private $repository;

    public function __construct(ModelStrategy $strategy, Repository $repository)
    {
        $this->strategy = $strategy;
        $this->repository = $repository;
    }

    public function setStrategy(ModelStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param $request
     * @return array
     */
    public function getIndexData($request)
    {
        return $this->repository->getCollection($request);
    }

    /**
     * @param $request
     * @return array
     */
    public function getCreatePageData($request)
    {
        return $this->repository->getCollection($request);
    }

    /**
     * @param $file
     * @param $request
     * @return array
     */
    public function create($file, $request)
    {
        $res['result'] = false;

        $fileHelper = new FileHelper();

        try {
            $image = $fileHelper->uploadFile($file['file'], $this->strategy->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();
        $request['file_id'] = $fileRepository->createFile($image);

        if (empty($request['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $new = $this->repository->create($this->strategy->prepareData($request));

        if (empty($new)) {
            $res['errors'][] = 'Ошибка сохранения новости';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    /**
     * @param $id
     * @return array
     */
    public function getUpdatePageData($id)
    {
        return $this->repository->getById($id);
    }

    public function update($file, $params)
    {
        $res['result'] = false;

        $fileHelper = new FileHelper();

        try {
            $alias = $fileHelper->uploadFile($file['file'],  $this->strategy->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e->getMessage();
            return $res;
        }

        if (!empty($alias)) {
            $fileHelper->deleteFile($params['file_alias'], $this->strategy->fileDirectory);

            $fileRepository = new FileRepository();
            $params['file_id'] = $fileRepository->createFile($alias);
        }

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $new = $this->repository->update($this->strategy->prepareData($params));

        if (empty($new)) {
            $res['errors'][] = 'Ошибка сохранения статьи';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    /**
     * @param $id
     * @return array
     */
    public function getDeletePageData($id)
    {
        return $this->repository->getById($id);
    }

    public function delete($data)
    {
        $res['result'] = false;

        if ($this->repository->deleteById($data['id'])) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = 'ошибка при удалении статьи';

        return $res;
    }
}