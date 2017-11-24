<?php
if (PHP_SAPI  != 'cli'){
    die('you are not in cli mode');
}
define('UPDATE', '-u');
define('FILE_EXTENSION', '.trie');
require 'functions.php';
require "../../vendor/autoload.php";
/**
 *  Trie test.txt -u tdsfa.trie
 */


class Trie
{
    private function __construct()
    {
    }

    static public function createFromFile($filePath)
    {
        if (!file_exists($filePath)){
            echo "file '{$filePath}' not exist!";
            die();
        }
        $root = [];
        $words = getSensitiveFromFile($filePath);
        foreach ($words as $word){
            buildNode($root, mbStrSplit($word));
        }
        //init double-array trie
        buildTrie($root, $base, $check);
        file_put_contents($filePath.FILE_EXTENSION, json_encode(['check' => $check, 'base' => $base], true));
    }

}

if ($argc < 2){
    echo '  please provide filename at least!';
    die();
}
if ($argc === 2 ){
    Trie::createFromFile($argv['1']);
}

echo 'done!';
