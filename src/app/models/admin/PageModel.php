<?php

namespace App\Model\Admin;

use App\Model\Model;
use App\Modules\FileUploader;
use App\Repository\FileRepository;
use App\Repository\PageRepository;
use RuntimeException;

class PageModel extends Model
{
    private $fileDirectory = 'page';

    public function getIndexData($order)
    {
        $pageRepository = new PageRepository();
        $data['pageList'] = $pageRepository->getPageList($order);

        return $data;
    }

    public function getShowUpdatePageData($id)
    {
        $pageRepository = new PageRepository();
        $data['page'] = $pageRepository->getPageById($id);

        return $data;
    }

    public function update($file, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        try {
            $alias = $fileUploader->uploadOne($file, $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        if (!empty($alias)) {
            $fileRepository = new FileRepository();
            $params['file_id'] = $fileRepository->createFile($alias);

            if (empty($params['file_id'])) {
                $res['errors'][] = 'Ошибка сохранения файла';
                return $res;
            }
        }

        $pageRepository = new PageRepository();
        $new = $pageRepository->updatePage($this->prepareData($params));

        if (empty($new)) {
            $res['errors'][] = 'Ошибка сохранения статьи';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    private function prepareData($params)
    {
        $data = [
            'id' => $params['id'],
            'name' => $params['name'],
            'content' => $params['content'],
            'file_id' => $params['file_id'],
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];

        return $data;
    }
}

