<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class LanguageService
{
    protected $languages;
    protected $defaultLanguage;

    public function __construct()
    {
        $this->languages = $this->loadLanguages();
        $this->defaultLanguage = $this->loadDefaultLanguage();
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    protected function loadLanguages()
    {
        return Cache::remember('available_languages', 60 * 24, function () {
            // You can replace this with a database query if you store languages in the database
            return Config::get('languages.available', ['en' => 'English']);
        });
    }

    protected function loadDefaultLanguage()
    {
        return Cache::remember('default_language', 60 * 24, function () {
            // You can replace this with a database query if you store the default language in the database
            return Config::get('languages.default', 'en');
        });
    }

    public function isValidLanguage($locale)
    {
        return array_key_exists($locale, $this->languages);
    }

    public function getLanguageName($locale)
    {
        return $this->languages[$locale] ?? null;
    }
}