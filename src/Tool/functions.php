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
     * @param string $delimiter
     * @return array
     */
    function getSensitiveFromFile($filePath, $delimiter = "\n")
    {
        $data = file_get_contents($filePath);
        return array_map(function ($a){
            return trim($a);
        }, explode($delimiter, $data));
    }
}

if (!function_exists('buildNode')){
    function buildNode(&$node, $chars, $i = 0)
    {
        if($i < count($chars)){
            $char =  $chars[$i];
            if (!isset($node[$char])){
                $node[$char] = null;
            }
            buildNode($node[$char], $chars, $i + 1);
        }else{
            if (!is_array($node)){
                $node = null;
            }

        }
    }
}

if (!function_exists('mbStrSplit')){
    function mbStrSplit($string, $len=1) {
        $start = 0;
        $str_len = mb_strlen($string);
        $array = [];
        while ($str_len) {
            array_push($array, utf8ToUnicodeInt(mb_substr($string,$start,$len,"utf8")));
            $start ++;
            $str_len --;
        }
        return $array;
    }
}

if (!function_exists('utf8ToUnicodeInt')){
    function utf8ToUnicodeInt($utf8_str)
    {
        if (is_int($utf8_str)){
            $utf8_str = strval($utf8_str);
        }
        if (isset($utf8_str[1])){
            $unicode = (ord($utf8_str[0]) & 0x1F) << 12;
            $unicode |= (ord($utf8_str[1]) & 0x3F) << 6;
        }else{
            $unicode = ord($utf8_str[0]);
        }
        if (isset($utf8_str[2])){
            $unicode |= (ord($utf8_str[2]) & 0x3F);
        }
        return $unicode;
    }
}

if (!function_exists('buildTrie')){
    function buildTrie($root, &$base = [], &$check = [])
    {
        $base[0] = 1;
        $check[0] = 0;
        //先建立第一层节点
        foreach ($root as $key => $value){
            $next = $base[0] + $key;
            if (is_array($value)){
                $base[$next] = $next + 1;
            }else{
                $base[$next] = $next * -1;
            }

            $check[$next] = 0;
        }

        //再插入剩余节点
        foreach ($root as $key => $value){
            if (is_array($value)){
                insertTrie($value, $base, $check, $base[0] + $key);
            }
        }
    }
}

if (!function_exists('insertTrie')){
    function insertTrie($root, &$base = [], &$check = [], $i = 0)
    {
        //若发生冲突。则重新寻找合适的位置
        for (;;){
            $pass = true;
            foreach ($root as $k => $v){
                if (isset($base[$base[$i] + $k]) ){
                    $pass = false;
                    break;
                }
            }
            if ($pass){
                break;
            }else{
                $base[$i] += 1;
            }
        }
        foreach ($root as $key => $value){
            $next = $base[$i] + $key;
            $check[$next] = $i;
            if (is_array($value)){
                $base[$next] = $next + 1;
                insertTrie($value, $base, $check, $next);
            }else{
                $base[$next] = $i * -1;
            }
        }
    }
}
