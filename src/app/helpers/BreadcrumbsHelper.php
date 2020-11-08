<?php

namespace App\Helper;

use App\Repository\CategoryRepository;

class BreadcrumbsHelper
{
    public static function getBreadcrumbsInString($categoryId) {

        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getAll();
        $categoryList = $categoryRepository->getArrayWithIdAsKey($categoryList);

        $breadcrumbs = array();

        for ($i = 0, $iMax = count($categoryList); $i < $iMax; $i++) {
            if ($categoryId !== 0 && !empty($categoryList[$categoryId])) {
                $breadcrumbs[] = $categoryList[$categoryId]['name'];
                $categoryId = $categoryList[$categoryId]['parent_id'];
            } else {
                break;
            }
        }

        return implode(' -> ', array_reverse($breadcrumbs));
    }
}