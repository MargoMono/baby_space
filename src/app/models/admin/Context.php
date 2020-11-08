<?php

namespace App\Model\Admin;

use App\Repository\Repository;

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
    public function getShowCreatePageData(): array
    {
        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();
        $data = $repository->getAll();
        $data = $this->strategy->modifyCreatePageData($data);

        return $data;
    }

    /**
     * @param $file
     * @param $params
     * @return array
     */
    public function create($file, $params): array
    {
        $res['result'] = false;

        if (!empty($file['file'])) {
            $params['file_id'] = $this->strategy->addFileConnection($file['file']);
            if (empty($params['file_id'])) {
                $res['errors'][] = 'Ошибка сохранения файла';
                return $res;
            }
        }
        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();
        $newEntityId = $repository->create($this->strategy->prepareData($params));

        if (empty($newEntityId)) {
            $res['errors'][] = 'Ошибка сохранения';
            return $res;
        }

        if (!empty($file['files'])) {
            $this->strategy->addFilesConnection($file['files'], $newEntityId);
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

        if (!empty($file['file'])) {
            $params['file_id'] = $this->strategy->updateFileConnection($file['file'], $params);
            if (empty($params['file_id'])) {
                $res['errors'][] = 'Ошибка сохранения файла';
                return $res;
            }
        }

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        /**
         * @var Repository $repository
         */
        $repository = $this->strategy->getRepository();
        $newEntityId = $repository->updateById($this->strategy->prepareData($params));

        if (empty($newEntityId)) {
            $res['errors'][] = 'Ошибка сохранения';
            return $res;
        }

        if (!empty($file['files'])) {
            $this->strategy->updateFilesConnection($file['files'], $newEntityId);
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