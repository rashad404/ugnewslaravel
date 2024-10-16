<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Menu extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Get the menu item's name based on the current language.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        $locale = App::getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        if ($locale === 'az' && !empty($this->title_az)) {
            return $this->title_az;
        } elseif ($locale === 'ru' && !empty($this->title_ru)) {
            return $this->title_ru;
        } else {
            // Default to English or use fallback if English is empty
            return $this->title_en ?: $this->{'title_' . $fallbackLocale};
        }
    }

    /**
     * Build the category list for the menu.
     *
     * @return array
     */
    public static function buildCategoryList()
    {
        return self::where('status', 1)
            ->orderBy('position')
            ->get()
            ->map(function ($menu) {
                return [
                    'name' => $menu->name,
                    'url' => $menu->url,
                    'slug' => $menu->slug,
                ];
            })
            ->toArray();
    }
}