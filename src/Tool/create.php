<?php
define('UPDATE', '-u');
//define('FILE', '-0');
define('FILE_EXTENSION', '.trie');
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
    }

    static  public function updateFromFile($filePath)
    {
        if (!file_exists($filePath)){
            echo "file '{$filePath}' not exist!";
            die();
        }
    }

}

if ($argc < 2){
    echo '  please provide filename at least!';
    die();
}
if ($argc === 2 ){
    Trie::createFromFile($argv['1']);
}

if ($argv[2] == UPDATE && isset($argv[3])){
    $oldFile = $argv[3];
    Trie::updateFromFile($oldFile);
}else{
    $info = <<<info
 Please check the param '-f fileanme' exist or not ,if you want to create a new trie please use '-n'
info;
echo $info;
die();
}
