<?php

namespace App\Models\Admin;

use App\Components\Logger;
use App\Modules\FileUploader;
use App\Repository\FileRepository;

class Context
{
    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    private $logger;

    /**
     * @param Strategy $strategy
     */
    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
        $this->logger = Logger::getLogger(static::class);
        $this->fileUploader = new FileUploader();
        $this->fileRepository = new FileRepository();
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
     */
    public function create($file, $params): void
    {
        $res['result'] = false;

        if (!empty($file['file'])) {
            $params['file_id'] = $this->addFileConnection($file['file']);
        }

        $newEntityId = $this->strategy->create($this->strategy->prepareData($params));

        if (!empty($file['files'])) {
            $this->addFilesConnection($file['files'], $newEntityId);
        }
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
            $params['file_id'] = $this->updateFileConnection($file['file'], $params);
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
            $this->updateFilesConnection($file['files'], $newEntityId);
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
     */
    public function delete($data): void
    {
        $id = $data['id'];

        $file = $this->strategy->getFile($id);
        $files = $this->strategy->getFiles($id);

        $this->strategy->delete($id);

        if (empty($file)) {
            $error = "Unable to delete product id - $id";
            $this->logger->error($error);
            throw new \LogicException($error);
        }

        $this->fileUploader->deleteFile($file['file_alias'], $this->strategy->fileDirectory);

        if (!empty($files)) {
            foreach ($files as $file) {
                $this->fileUploader->deleteFile($file['file_alias'], $this->strategy->fileDirectory);
            }
        }
    }

    public function photoDelete($id, $photoId): void
    {
        $file = $this->fileRepository->getFileById($photoId);
        $this->fileUploader->deleteFile($file['alias'], $this->strategy->fileDirectory);
        $this->strategy->deleteFileConnection($id, $photoId);
    }

    public function addFileConnection($file): string
    {
        $alias = $this->fileUploader->uploadOne($file, $this->strategy->getFileDirectory());

        if (empty($alias)) {
            throw new \RuntimeException('Can\'t create file connection - information about uploaded file is empty');
        }

        return $this->fileRepository->createFile($alias);
    }

    private function addFilesConnection($files, $categoryId): void
    {
        $imageList = $this->fileUploader->uploadSeveral($files, $this->strategy->getFileDirectory());

        if (empty($imageList)) {
            throw new \RuntimeException('Can\'t create files connection - information about uploaded files is empty');
        }

        foreach ($imageList as $image) {
            $fileId = $this->fileRepository->createFile($image);
            $this->strategy->createFilesConnection($categoryId, $fileId);
        }
    }

    private function updateFileConnection($file, $params): string
    {
        $image = $this->fileUploader->uploadOne($file, $this->strategy->getFileDirectory());

        if (empty($image)) {
            throw new \RuntimeException('Can\'t create file connection - information about uploaded file is empty');
        }

        $this->fileUploader->deleteFile($params['file_alias'], $this->strategy->getFileDirectory());

        return $this->fileRepository->createFile($image);
    }

    private function updateFilesConnection($files, $categoryId): void
    {
        $imageList = $this->fileUploader->uploadSeveral($files, $this->strategy->getFileDirectory());

        if (empty($imageList)) {
            throw new \RuntimeException('Can\'t create files connection - information about uploaded files is empty');
        }

        foreach ($imageList as $image) {
            $fileId = $this->fileRepository->createFile($image);
            $this->strategy->createFilesConnection($categoryId, $fileId);
        }
    }
}