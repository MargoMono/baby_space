<?php

namespace App\Helpers;

use App\Exceptions\UploadFileException;
use RuntimeException;

class FileUploaderHelper
{
    private $imagesFormat = [
        'image/gif',
        'image/jpeg',
        'image/png',
        'image/jfif',
        'application/pdf',
    ];

    const UPLOAD_ERR_NO_FILE = 4;

    /**
     * @param $file
     * @return string
     * @throws UploadFileException
     */
    public function uploadOne($file, $type)
    {
        if ($file['error'] !== UPLOAD_ERR_OK &&
            $file['error'] !== UPLOAD_ERR_NO_FILE) {
            throw new UploadFileException($file['error']);
        }

        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (!is_uploaded_file($file["tmp_name"])) {
            throw new RuntimeException('Ошибка при загрузке файла');
        }

        if (($file["size"]) > 1024 * 3 * 1024) {
            throw new RuntimeException('Большой размер файла');
        }

        if (!in_array($file["type"], $this->imagesFormat)) {
            throw new RuntimeException('Неправильный формат файла');
        }

        $alias = time() . $file["name"];

        move_uploaded_file($file["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/" . $type . "/" . $alias);

        $p = strrpos($file["name"], '.');
        $filename = substr($file["name"], 0, $p);

        $image['alias'] = $alias;
        $image['name'] = $filename;
        $image['type'] = $file["type"];

        return $image;
    }

    public function uploadSeveral($fileList, $type)
    {
        $imageList = [];

        if (empty($fileList)) {
            return null;
        }

        for ($i = 0, $iMax = count($fileList['name']); $i < $iMax; $i++) {

            if ($fileList['error'][$i] === UPLOAD_ERR_NO_FILE) {
                return null;
            }

            if (!is_uploaded_file($fileList["tmp_name"][$i])) {
                throw new RuntimeException('Ошибка при загрузке файла');
            }

            if (($fileList["size"][$i]) > 1024 * 3 * 1024) {
                throw new RuntimeException('Большой размер файла');
            }

            if (!in_array($fileList["type"][$i], $this->imagesFormat)) {
                throw new RuntimeException('Большой размер файла');
            }

            $alias = time() . $fileList["name"][$i];

            move_uploaded_file($fileList["tmp_name"][$i],
                $_SERVER['DOCUMENT_ROOT'] . '/upload/images/' . $type . "/" . $alias);

            $p = strrpos($fileList["name"][$i], '.');
            $filename = substr($fileList["name"][$i], 0, $p);


            $imageList[$i]['alias'] = $alias;
            $imageList[$i]['name'] = $filename;
            $imageList[$i]['type'] = $fileList["type"][$i];
        }

        return $imageList;
    }

    public function deleteFile($fileName, $type)
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . '/upload/images/' . $type . '/' . $fileName;

        if (!empty($fileName) && file_exists($file)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/upload/images/' . $type . '/' . $fileName);
        }
    }
}