<?php

namespace App\Model\Admin;

use App\Components\Model;
use App\Modules\FileUploader;
use App\Repository\CoatingRepository;
use App\Repository\CommentRepository;
use App\Repository\FileRepository;
use App\Repository\BlogRepository;
use DateTime;
use RuntimeException;

class CoatingModel extends Model
{
    private $fileDirectory = 'coating';

    public function getIndexData($order)
    {
        $newRepository = new CoatingRepository();
        $data['coatingList'] = $newRepository->getCoatingList($order);

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

        $coatingRepository = new CoatingRepository();
        $newCoatingId = $coatingRepository->createCoating($params);

        if (empty($newCoatingId)) {
            $res['errors'][] = 'Не удалось создать коллекцию пленок';
            return $res;
        }

        foreach ($imageList as $image) {

            $fileRepository = new FileRepository();
            $fileId = $fileRepository->createFile($image);

            if (empty($fileId)) {
                $res['errors'][] = 'Не удалось создать файл';
                return $res;
            }

            $fileCoatingConnection = $coatingRepository->createFileCoatingConnection($newCoatingId, $fileId);

            if (empty($fileCoatingConnection)) {
                $res['errors'][] = 'Не удалось создать коллекцию пленок';
                return $res;
            }
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowUpdatePageData($id)
    {
        $coatingRepository = new CoatingRepository();
        $data['coating'] = $coatingRepository->getCoatingById($id);
        $data['coating']['photos'] = $coatingRepository->getCoatingPhotos($id);

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

        $coatingRepository = new CoatingRepository();

        if (!empty($imageList)) {
            foreach ($imageList as $image) {

                $fileRepository = new FileRepository();
                $fileId = $fileRepository->createFile($image);

                if (empty($fileId)) {
                    $res['errors'][] = 'Не удалось создать файл';
                    return $res;
                }

                $fileCoatingConnection = $coatingRepository->createFileCoatingConnection($params['id'], $fileId);

                if (empty($fileCoatingConnection)) {
                    $res['errors'][] = 'Не удалось создать связь между фото и комментарием';
                    return $res;
                }
            }
        }

        $newCategory = $coatingRepository->updateCoating($this->prepareData($params));

        if (empty($newCategory)) {
            $res['errors'][] = 'Ошибка обовления коллекции пленок';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new CoatingRepository();
        $data['coating'] = $categoryRepository->getCoatingById($id);

        return $data;
    }

    public function photoDelete($id, $photoId)
    {
        $res['result'] = false;

        $fileRepository = new FileRepository();
        $file = $fileRepository->getFileById($photoId);

        $fileUploader = new FileUploader();
        $fileUploader->deleteFile($file['alias'], $this->fileDirectory);

        $coatingRepository = new CoatingRepository();
        if ($coatingRepository->deleteFileCoatingConnection($id, $photoId)) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении статьи";

        return $res;
    }

    public function delete($data)
    {
        $res['result'] = false;

        $categoryRepository = new CoatingRepository();
        $photoIds = $categoryRepository->getCoatingPhotos($data['id']);

        foreach ($photoIds as $photo) {
            $fileUploader = new FileUploader();
            $fileUploader->deleteFile($photo['file_alias'], $this->fileDirectory);

            $coatingRepository = new CoatingRepository();
            if (!$coatingRepository->deleteFileCoatingConnection($data['id'], $photo['file_id'])) {
                $res['errors'][] = "ошибка при удалении фото";
                return $res;
            }
        }

        if ($categoryRepository->deleteCoatingById($data['id'])) {
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

