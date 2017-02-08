<?php

/**
 * Sql dump table splitter
 * Принимает на вход поток с sql дампом из mysqldump и записывает каждую таблицу в отдельный sql файл
 */

$stdin = fopen('php://stdin', 'r');
$tableStream = fopen('_0_head.sql', 'w');

while (true) {
    $buffer = fgets($stdin);
    if ($buffer === false) {
        break;
    }
    $pattern = '/^-- Table structure for table `(.+)`$/';
    if (preg_match($pattern, $buffer, $m)) {
        $currentTable = $m[1];
        $tableStream = fopen($currentTable.'.sql', 'w');
        echo 'Extracting '.$currentTable."... \n";
    }
    fputs($tableStream, $buffer);
}
if (!feof($stdin)) {
    echo "Error: unexpected fgets() fail\n";
}
fclose($stdin);
