<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-11-13
 * Time: 上午 10:38
 */

if (!function_exists('getSensitiveFromFile')){
    /**
     * @param $filePath
     * @return array
     */
    function getSensitiveFromFile($filePath)
    {
        $data = file_get_contents($filePath);
        return array_map(function ($a){
            return trim($a);
        }, explode(',', $data));
    }
}

if (!function_exists('buildNode')){
    function buildNode(&$node, $chars, $i = 0)
    {
        if($i < count($chars)){
            $char = $chars[$i];
            if (!isset($node[$char])){
                $node[$char] = [];
            }
            buildNode($node[$char], $chars, $i + 1);
        }else{
            $node = null;
        }
    }
}

if (!function_exists('mbStrSplit')){
    function mbStrSplit($string, $len=1) {
        $start = 0;
        $str_len = mb_strlen($string);
        $array = [];
        while ($str_len) {
            array_push($array, mb_substr($string,$start,$len,"utf8"));
            $string = mb_substr($string, $len, $str_len,"utf8");
            $str_len = mb_strlen($string);
        }
        return $array;
    }
}