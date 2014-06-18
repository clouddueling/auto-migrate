<?php

require 'src/MySQLDiff.php';

use CloudDueling\MySQLAutoMigrator;

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
        $diff = new MySQLDiff($params);
        $diff_lines = $diff->runSQLDiff();
    } catch(Exception $e) {
        echo $e->getMessage(); exit;
    }
}
