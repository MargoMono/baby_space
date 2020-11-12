<?php

namespace App\Components;

use App\Repository\LanguageRepository;

class Language
{
    public $language;

    public function __construct()
    {
        if ($list = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']) : null) {
            if (preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $list, $list)) {
                $this->language = array_combine($list[1], $list[2]);
                foreach ($this->language as $n => $v) {
                    $this->language[$n] = $v ?: 1;
                }
                arsort($this->language, SORT_NUMERIC);
            }
        } else {
            $this->language = [];
        }
    }

    public function setLanguage($language)
    {
        $_SESSION['language'] = $language;
    }

    public function setContent()
    {
        if (empty($_SESSION['language'])) {
            $_SESSION['language'] = $this->getLanguage();
        }
    }

    public function getContent()
    {
        include_once 'assets/language/' . $_SESSION['language'] . '.php';
        return $_;
    }

    public function getLanguage()
    {
        $languageRepository = new LanguageRepository();
        $languages = $languageRepository->getAll();

        $defaultLanguage = '';
        $languagesAs = [];

        foreach ($languages as $key => $language) {
            $languageCode = explode(',', $language['code']);
            foreach ($languageCode as $code) {
                $languagesAs[strtok(strtolower(trim($code)), '-')] = $language['alias'];
            }
            if ($language['by_default'] == 1) {
                $defaultLanguage = $language['alias'];
            }
        }

        foreach ($this->language as $code => $v) {
            $s = strtok(strtolower(trim($code)), '-');
            if (isset($languagesAs[$s])) {
                return $languagesAs[$s];
            }
        }

        return $defaultLanguage;
    }
}

