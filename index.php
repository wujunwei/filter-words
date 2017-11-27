<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-11-27
 * Time: 下午 12:31
 */
$test = <<<heredoc
incestjiangdongrijilihongzhimakingminghuiminghuinewsnacbnaivenmispaperpeacehallplayboyrenminbaorenmingbaorfasafewebsexsimplesvdctaiptibetalktriangletriangleboyUltraSurfunixboxu
heredoc;
require "vendor/autoload.php";
use Brush\Trie;
$t = Trie::createFromFile('CensorWords.txt.trie');
echo microtime(true).PHP_EOL;
var_dump($t->detect($test, 100));
echo microtime(true).PHP_EOL;

//$words = explode("\n", file_get_contents('CensorWords.txt'));
//echo count($words);
//echo microtime(true).PHP_EOL;
//$result = [];
//foreach ($words as $word){
//    if (!empty($word) && strpos($test,  $word) !==false){
//        $result[] = $word;
//    }
//}
////var_dump($result);
//echo microtime(true).PHP_EOL;
