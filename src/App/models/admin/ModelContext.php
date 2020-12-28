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

    /**
     * @param ModelStrategy $strategy
     */
    public function __construct(ModelStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->fileUploader = new FileUploaderHelper();
        $this->fileRepository = new FileRepository();
    }

    /**
     * @param $file
     * @param $params
     */
    public function create($file, $params): void
    {
        if (!empty($file['file']['name'])) {
            $params['file_id'] = $this->createFile($file['file']);
        }

        $id = $this->strategy->create($this->strategy->prepareData($params));

        if (!empty($file['files']['name'][0])) {
            $this->addFilesConnection($file['files'], $id);
        }
    }

    /**
     * @param $file
     * @param $params
     */
    public function update($file, $params)
    {
        if (!empty($file['file']['name'])) {
            $oldFile =  $this->fileRepository->getFileById($params['file_id']);
            $params['file_id'] = $this->updateFile($file['file'], $params);
        }

        $this->strategy->update($file, $this->strategy->prepareData($params));

        if (!empty($oldFile)) {
            $this->fileUploader->deleteFile($oldFile['alias'], $this->strategy->fileDirectory);
            $this->fileRepository->deleteById($oldFile['id']);
        }

        if (!empty($file['files']['name'][0])) {
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

        if (!empty($file['file']['name'])) {
            $this->fileRepository->deleteById($file['id']);
            $this->fileUploader->deleteFile($file['alias'], $this->strategy->fileDirectory);
        }

        if (!empty($file['files']['name'][0])) {
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

    private function updateFilesConnection($files, $id): void
    {
        $imageList = $this->fileUploader->uploadSeveral($files, $this->strategy->getFileDirectory());

        if (empty($imageList)) {
            throw new \RuntimeException('Can\'t create files connection - information about uploaded files is empty');
        }

        foreach ($imageList as $image) {
            $fileId = $this->fileRepository->createFile($image);
            $this->strategy->createFilesConnection($id, $fileId);
        }
    }
}