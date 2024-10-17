<?php
namespace App\Helpers;

class Seo {

    public static $add_text;
    
    public static $add_prefix;

    public static function init() {
        self::$add_text = ' | ' . config('app.name');
        self::$add_prefix = config('app.name') . ' | ';
    }

    public static function general(){
        $array['metaTitle'] = self::$add_prefix.' Xəbər Sosial Şəbəkəsi';
        $array['metaKeywords'] = self::$add_prefix.' xəbərlər, xeberler, en son xeber, bugun xeber, son deqiqe xeberleri, namaz, valyuta';
        $array['metaDescription'] = config('app.name') . ' Xəbər Sosial Şəbəkəsidir. Ən son xəbərlər fərqli formatda';
        $array['metaImg'] = 'logo/logo-fb.png';
        return $array;
    }
    public static function index(){
        $array['metaTitle'] = self::$add_prefix.' Xəbər Sosial Şəbəkəsi';
        $array['metaKeywords'] = self::$add_prefix.' xəbərlər, xeberler, en son xeber, bugun xeber, son deqiqe xeberleri, namaz, valyuta';
        $array['metaDescription'] = config('app.name') . ' Xəbər Sosial Şəbəkəsidir. Ən son xəbərlər fərqli formatda';
        $array['metaImg'] = 'logo/logo-fb.png';
        return $array;
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
    public static function namaz(){
        $array['metaTitle'] = self::$add_prefix.' Namaz vaxtı, Bakı Namaz vaxtı';
        $array['metaKeywords'] = self::$add_prefix.' namaz vaxti, namaz vaxtlari, bugun namaz, subh, zohr,esr,şam,xuften namazi vaxti, namaz teqvimi';
        $array['metaDescription'] = self::$add_prefix.'Namaz vaxtları, Aylıq namaz təqvimi';
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
