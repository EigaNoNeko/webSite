<?php
class GFunction {
    private $CMS;

    private $seedString = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    function __construct($CMS){
        $this->CMS = $CMS;

        $CMS->addModule('OmsyString');
        $CMS->addModule('OmsyJSON');
    }

    function getSeed() { return $this->seedString; }

    public function slugify($text, string $divider = '-') { return __OmsyString->slugify($text, $divider); }
    public function webSubstr($string, $int = 255, $end = "...") { return __OmsyString->subStr($string, $int, $end); }
    public function web_substr($string, $int = 255, $end = "...") { return __OmsyString->subStr($string, $int, $end); }
    public function htmlDecode($string) { return __OmsyString->htmlDecode($string); }
    public function RandomString($length = 10) { return __OmsyString->rand($length); }
    public function RandomCode($length = 10) { return __OmsyString->rand($length, '0123456789'); }
    public function jsonDecode($string) { return __OmsyJSON->decode($string); }

    public function is_bot($str = null) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if(isset($str)) $userAgent = $str;

        if(stripos($userAgent, 'bot') || stripos($userAgent, 'python')) return true;
        return false;
    }

    public function get_ip(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function get_agent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    function shuffle_assoc(&$array) {
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }

    // ChatGPT ??
    public function convertDate($datetimeStr, $started = 'Il y a ') {
        if (!$datetimeStr) return "Unknow";

        $date = DateTime::createFromFormat('Y-m-d H:i:s', $datetimeStr, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Europe/Paris'));
        if (!$date) {
            return "Format de date invalide";
        }

        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $diffInSeconds = abs($now->getTimestamp() - $date->getTimestamp());

        // Gérer le passé uniquement
        if ($diffInSeconds < 0) return "Unknow";

        if ($diffInSeconds < 60) return "$started$diffInSeconds seconde" . ($diffInSeconds > 1 ? "s" : "");

        $diffInMinutes = floor($diffInSeconds / 60);
        if ($diffInMinutes < 60) return "$started$diffInMinutes minute" . ($diffInMinutes > 1 ? "s" : "");

        $diffInHours = floor($diffInMinutes / 60);
        if ($diffInHours < 24) return "$started$diffInHours heure" . ($diffInHours > 1 ? "s" : "");

        $diffInDays = floor($diffInHours / 24);
        if ($diffInDays <= 7) return "$started$diffInDays jour" . ($diffInDays > 1 ? "s" : "");

        $jours = [
            'Sunday' => 'Dimanche',
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi'
        ];
        $mois = [
            'January' => 'Janvier',
            'February' => 'Février',
            'March' => 'Mars',
            'April' => 'Avril',
            'May' => 'Mai',
            'June' => 'Juin',
            'July' => 'Juillet',
            'August' => 'Août',
            'September' => 'Septembre',
            'October' => 'Octobre',
            'November' => 'Novembre',
            'December' => 'Décembre'
        ];

        $jour = $jours[$date->format('l')] ?? $date->format('l');
        $moisNom = $mois[$date->format('F')] ?? $date->format('F');

        return "$jour, " . $date->format('j') . " $moisNom " . $date->format('Y');
    }


    
    public function mimeEpxlore($pathFull) {
        if(
            str_contains($pathFull, '.css') ||
            str_contains($pathFull, '.js') ||
            str_contains($pathFull, '.jpg')  ||
            str_contains($pathFull, '.jpeg') ||
            str_contains($pathFull, '.png')  ||
            str_contains($pathFull, '.svg') ||
            str_contains($pathFull, '.ico') ||
            str_contains($pathFull, '.webp') ||
            str_contains($pathFull, '.mp4')
        ) {
            return true;
        }

        return false;
    }
}
