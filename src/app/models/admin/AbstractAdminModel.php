<?php

namespace App\Model\Admin;

use App\Modules\FileUploader;
use App\Repository\FileRepository;

abstract class AbstractAdminModel implements Strategy
{
    public function addFileConnection($file)
    {
        $fileUploader = new FileUploader();

        try {
            $alias = $fileUploader->uploadOne($file, $this->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();

        return $fileRepository->createFile($alias);
    }

    public function addFilesConnection($files, $categoryId)
    {
        $fileUploader = new FileUploader();

        try {
            $imageList = $fileUploader->uploadSeveral($files, $this->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();

        if (empty($imageList)) {
            $res['result'] = true;
            return $res;
        }

        foreach ($imageList as $image) {

            $fileId = $fileRepository->createFile($image);

            if (empty($fileId)) {
                $res['errors'][] = 'Не удалось создать файл';
                return $res;
            }

            $filesConnection = $this->createFilesConnection($categoryId, $fileId);

            if (empty($filesConnection)) {
                $res['errors'][] = 'Не удалось создать связь между фото и категорей';
                return $res;
            }
        }

        return null;
    }

    public function updateFileConnection($file, $params)
    {
        $fileUploader = new FileUploader();

        try {
            $image = $fileUploader->uploadOne($file, $this->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        if (!empty($image)) {
            $fileUploader->deleteFile($params['file_alias'], $this->fileDirectory);
        }

        $fileRepository = new FileRepository();

        return $fileRepository->createFile($image);
    }

    public function updateFilesConnection($files, $categoryId)
    {
        $fileUploader = new FileUploader();

        try {
            $imageList = $fileUploader->uploadSeveral($files, $this->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();

        if (empty($imageList)) {
            $res['result'] = true;
            return $res;
        }

        foreach ($imageList as $image) {

            $fileId = $fileRepository->createFile($image);

            if (empty($fileId)) {
                $res['errors'][] = 'Не удалось создать файл';
                return $res;
            }

            $filesConnection = $this->createFilesConnection($categoryId, $fileId);

            if (empty($filesConnection)) {
                $res['errors'][] = 'Не удалось создать связь между фото и категорей';
                return $res;
            }
        }

        return null;
    }
}
