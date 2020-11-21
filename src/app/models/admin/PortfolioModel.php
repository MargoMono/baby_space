<?php

namespace App\Models\Admin;

use App\Models\Model;
use App\Helpers\FileUploaderHelper;
use App\Helper\TextHelper;
use App\Repository\PortfolioRepository;
use App\Repository\FileRepository;
use App\Repository\BlogRepository;
use RuntimeException;

class PortfolioModel extends Model
{
    private $fileDirectory = 'portfolio';

    public function getIndexData($sort)
    {
        $portfolioRepository = new PortfolioRepository();
        $data['portfolioList'] = $portfolioRepository->getPortfolioList($sort);


        return $data;
    }

    public function create($file, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploaderHelper();

        try {
            $alias = $fileUploader->uploadOne($file, $this->fileDirectory);
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

        $newRepository = new PortfolioRepository();
        $new = $newRepository->createPortfolio($this->prepareData($params));

        if (empty($new)) {
            $res['errors'][] = 'Ошибка сохранения фотографии';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowUpdatePageData($id)
    {
        $categoryRepository = new PortfolioRepository();
        $data['photo'] = $categoryRepository->getPhotoById($id);

        return $data;
    }

    public function update($file, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploaderHelper();

        try {
            $alias = $fileUploader->uploadOne($file, $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e->getMessage();
            return $res;
        }

        if (!empty($alias)) {
            $fileUploader->deleteFile($params['file_alias'], $this->fileDirectory);

            $fileRepository = new FileRepository();
            $params['file_id'] = $fileRepository->createFile($alias);
        }

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $categoryRepository = new PortfolioRepository();
        $newCategory = $categoryRepository->updatePortfolio($this->prepareData($params));

        if (empty($newCategory)) {
            $res['errors'][] = 'Ошибка сохранения статьи';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new PortfolioRepository();
        $data['photo'] = $categoryRepository->getPhotoById($id);

        return $data;
    }

    public function delete($data)
    {
        $res['result'] = false;

        $categoryRepository = new PortfolioRepository();

        if ($categoryRepository->deletePhotoById($data['id'])) {
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
            'content' => $params['content'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name']),
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];

        return $data;
    }
}

