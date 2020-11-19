<?php

namespace App\Models;

use App\Components\Language;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\NewRepository;

class Model
{
    public function __construct()
    {
        $language = $_SESSION['language'];
    }

    public function getDefaultData()
    {
        $categoryRepository = new CategoryRepository();
        $mainCategoryList = $categoryRepository->getMainCategoryList();

        foreach ($mainCategoryList as $key => $mainCategory) {
            $mainCategoryList[$key]['childCategoryList'] = $categoryRepository->getEnableChildCategoryListById($mainCategory['id']);
        }

        if (!empty($_SESSION['comparison_product'])) {
            $data['comparison_product_count'] = count($_SESSION['comparison_product']);
        } else {
            $data['comparison_product_count'] = 0;
        }

        $newRepository = new NewRepository();
        $lastNew = $newRepository->getLastNew();

        $data['lastNew'] = $lastNew;
        $data['footerCategoryList'] = $mainCategoryList;

        $languagesRepository = new LanguageRepository();
        $data['languages'] = $languagesRepository->getAll();

        $language = new Language();
        $content = $language->getContent();

        return array_merge($data, $content);
    }

    protected function getBreadcrumbs($categoryId)
    {
        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getAll();
        $categoryList = $categoryRepository->getArrayWithIdAsKey($categoryList);

        $breadcrumbs = array();

        for ($i = 0; $i < count($categoryList); $i++) {
            if ($i == 3) {
                unset($breadcrumbs[0]);
            }
            if ($categoryId != 0 and !empty($categoryList[$categoryId])) {
                $breadcrumbs[] = [
                    'id' => $categoryList[$categoryId]['id'],
                    'name' => $categoryList[$categoryId]['name'],
                    'alias' => $categoryList[$categoryId]['alias'],
                ];
                $categoryId = $categoryList[$categoryId]['parent_id'];
            } else {
                break;
            }
        }

        return array_reverse($breadcrumbs);
    }
}