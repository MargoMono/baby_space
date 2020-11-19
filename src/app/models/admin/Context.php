<?php

namespace App\Models\Admin;

use App\Modules\FileUploader;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;
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
        return $this->strategy->getIndexData($order);
    }

    /**
     * @param null $order
     * @return array
     */
    public function getShowCreatePageData($order = null): array
    {
        return $this->strategy->getShowCreatePageData($order);
    }

    public function validation($file, $params)
    {
        return $this->strategy->validation($file, $params);
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

        $newEntityId = $this->strategy->create($this->strategy->prepareData($params));

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
        return $this->strategy->getShowUpdatePageData($id);
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

        $newEntityId = $this->strategy->update($this->strategy->prepareData($params));

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
        return $this->strategy->getShowDeletePageData($id);
    }

    /**
     * @param $data
     * @return array
     */
    public function delete($data): array
    {
        $res['result'] = false;

        if ($this->strategy->delete($data['id'])) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = 'ошибка при удалении';

        return $res;
    }

    public function photoDelete($id, $photoId)
    {
        $res['result'] = false;

        $fileRepository = new FileRepository();
        $file = $fileRepository->getFileById($photoId);

        $fileUploader = new FileUploader();
        $fileUploader->deleteFile($file['alias'], $this->strategy->fileDirectory);

        if($this->strategy->deleteFileConnection($id, $photoId)){
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = 'ошибка при удалении статьи';

        return $res;
    }
}