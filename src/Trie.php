<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-11-23
 * Time: 下午 4:09
 */

namespace Brush;


class Trie
{
    /**
     * @var array
     */
    private $base;
    /**
     * @var array
     */
    private $check;

    public function __construct($base = [], $check = [])
    {
        $this->base = $base;
        $this->check = $check;
    }

    public function utf8ToUnicodeInt($utf8_str)
    {
        if (is_int($utf8_str)){
            $utf8_str = strval($utf8_str);
        }
        $unicode = (ord($utf8_str[0]) & 0x1F) << 12;
        if (isset($utf8_str[1])){
            $unicode |= (ord($utf8_str[1]) & 0x3F) << 6;
        }
        if (isset($utf8_str[2])){
            $unicode |= (ord($utf8_str[2]) & 0x3F);
        }
        return $unicode;
    }

    private function mbStrSplit($string, $len=1) {
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

    public function detect($str, $threshold = 1)
    {
        $result = '';
        $str_arr = mbStrSplit($str);
        $i = 0;
        foreach ($str_arr as $key => $value){
            $temp_value = $this->utf8ToUnicodeInt($value);
            $j = $this->base[$i] + $temp_value;
            if (isset($this->base[$j]) && $this->check[$j] === $i){
                $result .= $value;
                if ($this->base[$j] < 0 ){
                    echo $result;die();//todo get words
                }
            }
            $i = $j;
        }
        die('not found');
    }

    /**
     * @param $filePath
     * @return Trie
     */
    static public function createFromFile($filePath)
    {
        return new Trie();
    }
}