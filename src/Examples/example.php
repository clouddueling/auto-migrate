<?php

require 'src/MySQL.php';

$params = array(
    'dbuser' => 'root',
    'dbpass' => 'root',
    'dbname' => 'diff',
    'dbhost' => 'localhost',
    'dumpxml' => ''
);

$files = scandir('example');

foreach ($files as $file) {
    if (in_array($file, ['.','..','.DS_Store'])) {
        continue;
    }

    var_dump($file);
    $params['dumpxml'] = 'example/' . $file;

    try {
        $diff = new CloudDueling\AutoMigrate\MySQL($params);
        $diff_lines = $diff->runSQLDiff();
    } catch(Exception $e) {
        echo $e->getMessage(); exit;
    }
}
