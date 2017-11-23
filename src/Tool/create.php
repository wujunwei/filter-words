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

/**
 * Class Node
 * @deprecated use [] instead
 */
class Node
{

    public $char = '';

    /**
     * @var Node[]
     */
    private $children = [];

    public function __construct($ch = '')
    {
        $this->char = $ch;
    }

    public function find($char)
    {
        foreach ($this->children as &$child){
            if ($child->char === $char){
                return $child;
            }
        }
        return null;
    }

    public function addChild($char)
    {
        $this->children[] = new Node($char);
    }
}

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

        (new \Brush\Trie($base, $check))->detect('32451');
        echo "base:\n";
        foreach ($base as $key => $node){
            echo $key.'->'.$node."\t";
        }
        echo "\ncheck:\n";
        foreach ($check as $key => $node){
            echo $key.'->'.$node."\t";
        }
    }

    static  public function updateFromFile($filePath, $trie)
    {
        if (!file_exists($filePath)){
            echo "file '{$filePath}' not exist!";
            die();
        }
        if (!file_exists($trie)){
            echo "file '{$filePath}' not exist!";
            die();
        }

        $root = [];
        $words = getSensitiveFromFile($filePath);
        foreach ($words as $word){
            buildNode($root, mbStrSplit($word));
        }
    }

}

if ($argc < 2){
    echo '  please provide filename at least!';
    die();
}
if ($argc === 2 ){
    Trie::createFromFile($argv['1']);
    die();
}

if ($argv[2] == UPDATE && isset($argv[3])){
    $oldFile = $argv[3];
    Trie::updateFromFile($oldFile, $argv['1']);
}

echo 'done!';
