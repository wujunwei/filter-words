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

    /**
     * @param $utf8_str
     * @return int
     */
    private function utf8ToUnicodeInt($utf8_str)
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

    /**
     * @param $string
     * @param int $len
     * @return array
     */
    private function mbStrSplit($string, $len=1) {
        $start = 0;
        $str_len = mb_strlen($string);
        $array = [];
        while ($str_len) {
            $array[] = mb_substr($string,$start,$len,"utf8");
            $start ++;
            $str_len --;
        }
        return $array;
    }

    /**
     * @param $str
     * @param int $threshold
     * @return array
     */
    public function detect($str, $threshold = 10)
    {
        $last_key = 0;
        $results = [];
        $temp = '';
        $str_arr = $this->mbStrSplit($str);
        $i = 0;
        $len = count($str_arr);
        for ($key = 0;$key < $len;$key ++){
            $value = $str_arr[$key];
            if (ctype_space($value) || empty($value)){
                continue;
            }
            $temp_value = $this->utf8ToUnicodeInt($value);
//            echo "$value : $temp_value\n";
            $j = $this->base[$i] + $temp_value;
            if (isset($this->base[$j]) && $this->check[$j] === $i){
                $temp .= $value;
                if ($this->base[$j] < 0 ){
                    $last_key = $key;
                    $results[] = $temp;
                    if(count($results) >= $threshold){
                        break;
                    }
                    $temp = '';
                    $i = 0;
                }else{
                    $i = $j;
                }
            }else{
                $key = ++ $last_key;
                $i = 0;
                $temp = '';
            }
        }
        return $results;
    }

    /**
     * @param $filePath
     * @return Trie
     * @throws \Exception
     */
    static public function createFromFile($filePath)
    {
        if (!file_exists($filePath) || !is_readable($filePath)){
            throw new \InvalidArgumentException("file {$filePath} not exist !");
        }
        $config = json_decode(file_get_contents($filePath), true);
        if ($config !== false && isset($config['base']) && isset($config['check'])){
            return new Trie($config['base'], $config['check']);
        }
        throw new \InvalidArgumentException("file resolve error, check and base config can not find !");
    }
}
