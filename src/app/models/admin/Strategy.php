<?php

namespace App\Model\Admin;

interface Strategy
{
    public function getRepository();

    public function modifyCreatePageData($data);

    public function prepareData($params);

    public function addFileConnection($file);

    public function addFilesConnection($files, $id);

    public function updateFileConnection($file, $params);

    public function updateFilesConnection($files, $id);

    public function modifyUpdatePageData($data, $id);
}
