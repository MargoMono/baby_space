<?php

namespace App\Model\Admin;

use App\Modules\FileUploader;
use App\Repository\FileRepository;
use App\Repository\Repository;
use RuntimeException;

class Context
{
    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * @param Strategy $strategy
     */
    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param Strategy $strategy
     */
    public function setStrategy(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param null $order
     * @return array
     */
    public function getIndexData($order = null): array
    {
        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();

        return $repository->getAll($order);
    }

    /**
     * @return array
     */
    public function getShowCreatePageData()
    {
        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();

        return $repository->getAll();
    }

    /**
     * @param $file
     * @param $params
     * @return array
     */
    public function create($file, $params): array
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        try {
            $alias = $fileUploader->uploadOne($file, $this->strategy->fileDirectory);
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

        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();
        $newEntity = $repository->create($this->strategy->prepareData($params));

        if (empty($newEntity)) {
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
    public function getShowUpdatePageData($id): array
    {
        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();

        return $repository->getById($id);
    }

    /**
     * @param $file
     * @param $params
     * @return array
     */
    public function update($file, $params): array
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        try {
            $alias = $fileUploader->uploadOne($file, $this->strategy->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e->getMessage();
            return $res;
        }

        if (!empty($alias)) {
            $fileUploader->deleteFile($params['file_alias'], $this->strategy->fileDirectory);

            $fileRepository = new FileRepository();
            $params['file_id'] = $fileRepository->createFile($alias);
        }

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();
        $newCategory = $repository->updateById($this->strategy->prepareData($params));

        if (empty($newCategory)) {
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
    public function getShowDeletePageData($id)
    {
        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();

        return $repository->getById($id);
    }

    /**
     * @param $data
     * @return array
     */
    public function delete($data): array
    {
        $res['result'] = false;

        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();

        if ($repository->deleteById($data['id'])) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = 'ошибка при удалении';

        return $res;
    }
}