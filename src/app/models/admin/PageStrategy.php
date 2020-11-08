<?php

namespace App\Model\Admin;

use App\Modules\FileUploader;
use App\Repository\FileRepository;
use App\Repository\PageRepository;

class PageStrategy implements Strategy
{
    public $fileDirectory = 'page';

    public function getRepository()
    {
        return new PageRepository();
    }

    public function modifyIndexData($data)
    {
        return $data;
    }

    public function modifyCreatePageData($data)
    {
        return $data;
    }

    public function modifyUpdatePageData($data, $id)
    {
        return $data;
    }

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

    public function addFilesConnection($files, $id)
    {

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

    public function updateFilesConnection($files, $id)
    {

    }

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'name' => $params['name'],
            'content' => $params['content'],
            'file_id' => $params['file_id'],
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];
    }
}
