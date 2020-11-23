<?php

namespace App\Models\Admin;

use App\Components\Logger;
use App\Helpers\FileUploaderHelper;
use App\Repository\FileRepository;

class ModelContext
{
    /**
     * @var ModelStrategy
     */
    private $strategy;

    /**
     * @var FileUploaderHelper
     */
    private $fileUploader;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    private $logger;

    /**
     * @param ModelStrategy $strategy
     */
    public function __construct(ModelStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->logger = Logger::getLogger(static::class);
        $this->fileUploader = new FileUploaderHelper();
        $this->fileRepository = new FileRepository();
    }

    /**
     * @param ModelStrategy $strategy
     */
    public function setStrategy(ModelStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param $file
     * @param $params
     */
    public function create($file, $params): void
    {
        if (!empty($file['file']) && $file['file']['error'] != FileUploaderHelper::UPLOAD_ERR_NO_FILE) {
            $params['file_id'] = $this->createFile($file['file']);
        }

        $newEntityId = $this->strategy->create($this->strategy->prepareData($params));

        if (!empty($file['files'] && $file['files']['error'][0] != FileUploaderHelper::UPLOAD_ERR_NO_FILE)) {
            $this->addFilesConnection($file['files'], $newEntityId);
        }
    }

    /**
     * @param $file
     * @param $params
     */
    public function update($file, $params)
    {
        if (!empty($file['file']) && $file['file']['error'] != FileUploaderHelper::UPLOAD_ERR_NO_FILE) {
            $oldFileId = $params['file_id'];
            $params['file_id'] = $this->updateFile($file['file'], $params);
        }

        $this->strategy->update($this->strategy->prepareData($params));

        if (!empty($oldFileId)) {
            $this->fileRepository->deleteById($oldFileId);
        }

        if (!empty($file['files'] && $file['files']['error'][0] != FileUploaderHelper::UPLOAD_ERR_NO_FILE)) {
            $this->updateFilesConnection($file['files'], $params['id']);
        }
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

        if (!empty($file)) {
            $this->fileRepository->deleteById($file['id']);
            $this->fileUploader->deleteFile($file['alias'], $this->strategy->fileDirectory);
        }

        if (!empty($files)) {
            foreach ($files as $file) {
                $this->fileRepository->deleteById($file['id']);
                $this->fileUploader->deleteFile($file['alias'], $this->strategy->fileDirectory);
            }
        }
    }

    public function imageDelete($id, $photoId): void
    {
        $file = $this->fileRepository->getFileById($photoId);
        $this->fileUploader->deleteFile($file['alias'], $this->strategy->fileDirectory);
        $this->strategy->deleteFileConnection($id, $photoId);
    }

    public function createFile($file): string
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

    private function updateFile($file, $params): string
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