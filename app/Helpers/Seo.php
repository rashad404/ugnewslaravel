<?php
namespace App\Helpers;

class Seo {

    public static $add_text;
    public static $add_prefix;
    public static $host;

    public static function init() {
        self::$add_text = ' | ' . config('app.name');
        self::$add_prefix = config('app.name') . ' | ';
        self::$host = $_SERVER['HTTP_HOST'];
    }

    public static function general() {
        $array['metaTitle'] = self::$add_prefix.' Son Xeberler, Xeberler';
        $array['metaKeywords'] = self::$add_prefix.' son xeberler, xeberler, son deqiqe xeberleri, namaz vaxti, valyuta';
        $array['metaDescription'] = 'Son xeberler, namaz vaxtlari, valyuta';
        $array['metaImg'] = 'logo/logo-fb.png';
        return $array;
    }
    public static function index() {
        return self::general();
    }

    public static function create_channel(){
        $array['metaTitle'] = self::$add_prefix.'Xəbər Kanalı yarat, xəbər saytı, internetden pul qazan';
        $array['metaKeywords'] = self::$add_prefix.'Xəbər Kanalı yarat, xəbər saytı, internetden pul qazan';
        $array['metaDescription'] = self::$add_prefix.'Xəbər Kanalı yarat, xəbər saytı, internetden pul qazan';
        $array['metaImg'] = 'logo/logo-fb.png';
        return $array;
    }


    public static function coronavirus(){
        $array['metaTitle'] = self::$add_prefix.' Koronavirus statistikası, koronavirus xəbərləri';
        $array['metaKeywords'] = self::$add_prefix.' en son koronavirus statistikaları, koronavirus xeberleri, son koronavirus yenilikleri, koronavirus baki, koronavirus azerbaycan';
        $array['metaDescription'] = self::$add_prefix.'Ən son koronavirus statistikaları, canlı statistika';
        $array['metaImg'] = 'logo/logo-fb.png';
        return $array;
    }

    public static function city($name=''){
        $array['metaTitle'] = $name.' xəbərləri, '.$name.' son xəbərlər';
        $array['metaKeywords'] = $name.' xəbərləri, '.$name.' son xəbərlər';
        $array['metaDescription'] = $name.' xəbərləri, '.$name.' son xəbərlər';
        $array['metaImg'] = 'logo/logo-fb.png';
        return $array;
    }

    public static function currencies() {
        $array['metaTitle'] = ' Valyuta məzənnələri, Dollar məzənnəsi, Avro məzənnəsi';
        $array['metaKeywords'] = ' valyuta məzənnələri, dolların məzənnəsi, avronun məzənnəsi, xarici valyutalar, manat qarşısında valyutalar, bugünkü valyuta məzənnələri';
        $array['metaDescription'] = ' Valyuta məzənnələri, bugünkü valyuta dəyişmələri, Azərbaycan manatına qarşı xarici valyutaların məzənnələri.';
        $array['metaImg'] = 'logo/logo-fb.png';
        return $array;
    }
    
    public static function namaz_times() {
        $array['metaTitle'] = 'Namaz vaxtları, ' . date('d.m.Y') . ' namaz vaxtı';
        $array['metaKeywords'] = 'namaz vaxtları, imsak vaxtı, sübh azanı, zohr azanı, əsr azanı, gün batar vaxtı, işa azanı, bugünkü namaz vaxtları';
        $array['metaDescription'] = 'Namaz vaxtları, bugünkü namaz vaxtları, imsak, sübh, gün çıxır, zohr, əsr, gün batar, işa və gecə yarısı vaxtları.';
        $array['metaImg'] = 'logo/logo-fb.png';
        return $array;
    }
    
    public static function weather() {
        $array['metaTitle'] = 'Hava proqnozu, ' . date('d.m.Y') . ' üçün hava məlumatı';
        $array['metaKeywords'] = 'hava proqnozu, bugünkü hava, sabahki hava, Bakı hava proqnozu, Gəncə hava proqnozu, Şəki hava proqnozu, Lənkəran hava proqnozu';
        $array['metaDescription'] = 'Hava proqnozu, bugünkü və sabahki hava məlumatı. Bakı, Gəncə, Şəki, Lənkəran və digər şəhərlər üçün cari hava şəraiti və proqnozlar.';
        $array['metaImg'] = 'logo/logo-fb.png'; // Path to a relevant image for sharing
        return $array;
    }
    
    public static function weather_city($city) {
        $array['metaTitle'] = $city . ' Hava proqnozu, ' . date('d.m.Y') . ' üçün '.$city.' hava məlumatı';
        $array['metaKeywords'] = $city . ' hava proqnozu, '.$city.' bugünkü hava, '.$city.' sabahki hava, '.$city.' hava proqnozu';
        $array['metaDescription'] = $city . ' Hava proqnozu, '.$city.' bugünkü və sabahki hava məlumatı. '.$city.' şəhəri üçün cari hava şəraiti və proqnozlar.';
        $array['metaImg'] = 'logo/logo-fb.png'; // Path to a relevant image for sharing
        return $array;
    }

}
