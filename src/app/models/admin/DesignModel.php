<?php

namespace App\Model\Admin;

use App\Components\Model;
use App\Modules\FileUploader;
use App\Repository\CoatingRepository;
use App\Repository\CommentRepository;
use App\Repository\DesignRepository;
use App\Repository\FileRepository;
use App\Repository\BlogRepository;
use DateTime;
use RuntimeException;

class DesignModel extends Model
{
    private $fileDirectory = 'design';

    public function getIndexData($order)
    {
        $newRepository = new DesignRepository();
        $data['designList'] = $newRepository->getDesignList($order);

        return $data;
    }


    public function create($files, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        try {
            $imageList = $fileUploader->uploadSeveral($files, $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        if (empty($imageList)) {
            $res['errors'][] = 'Ошибка при загрузке изображения';
            return $res;
        }

        $designRepository = new DesignRepository();
        $newDesignId = $designRepository->createDesign($params);

        if (empty($newDesignId)) {
            $res['errors'][] = 'Не удалось создать коллекцию дизайнов';
            return $res;
        }

        foreach ($imageList as $image) {

            $fileRepository = new FileRepository();
            $fileId = $fileRepository->createFile($image);

            if (empty($fileId)) {
                $res['errors'][] = 'Не удалось создать файл';
                return $res;
            }

            $fileCoatingConnection = $designRepository->createFileDesignConnection($newDesignId, $fileId);

            if (empty($fileCoatingConnection)) {
                $res['errors'][] = 'Не удалось создать коллекцию дизайнов';
                return $res;
            }
        }


        $res['result'] = true;
        return $res;
    }

    public function getShowUpdatePageData($id)
    {
        $coatingRepository = new DesignRepository();
        $data['coating'] = $coatingRepository->getDesignById($id);
        $data['coating']['photos'] = $coatingRepository->getDesignPhotos($id);

        return $data;
    }

    public function update($files, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        try {
            $imageList = $fileUploader->uploadSeveral($files, $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $coatingRepository = new DesignRepository();

        if (empty($imageList)) {
            $res['errors'][] = 'Ошибка при загрузке изображения';
            return $res;
        }

        if (!empty($imageList)) {
            foreach ($imageList as $image) {

                $fileRepository = new FileRepository();
                $fileId = $fileRepository->createFile($image);

                if (empty($fileId)) {
                    $res['errors'][] = 'Не удалось создать файл';
                    return $res;
                }

                $fileCoatingConnection = $coatingRepository->createFileDesignConnection($params['id'], $fileId);

                if (empty($fileCoatingConnection)) {
                    $res['errors'][] = 'Не удалось создать связь между фото и комментарием';
                    return $res;
                }
            };
        }

        $newCategory = $coatingRepository->updateDesign($this->prepareData($params));

        if (empty($newCategory)) {
            $res['errors'][] = 'Ошибка обовления коллекции пленок';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new DesignRepository();
        $data['coating'] = $categoryRepository->getDesignById($id);

        return $data;
    }

    public function photoDelete($id, $photoId)
    {
        $res['result'] = false;

        $fileRepository = new FileRepository();
        $file = $fileRepository->getFileById($photoId);

        $fileUploader = new FileUploader();
        $fileUploader->deleteFile($file['alias'], $this->fileDirectory);

        $coatingRepository = new DesignRepository();
        if ($coatingRepository->deleteFileDesignConnection($id, $photoId)) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении статьи";

        return $res;
    }

    public function delete($data)
    {
        $res['result'] = false;

        $categoryRepository = new DesignRepository();
        $photoIds = $categoryRepository->getDesignPhotos($data['id']);

        foreach ($photoIds as $photo) {
            $fileUploader = new FileUploader();
            $fileUploader->deleteFile($photo['file_alias'], $this->fileDirectory);

            $coatingRepository = new CoatingRepository();
            if (!$coatingRepository->deleteFileCoatingConnection($data['id'], $photo['file_id'])) {
                $res['errors'][] = "ошибка при удалении фото";
                return $res;
            }
        }

        if ($categoryRepository->deleteDesignById($data['id'])) {
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
        ];

        return $data;
    }
}

