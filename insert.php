<?php

/*
 * Ingest direwolf log entries into the direwolf-web database
 */

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Require other useful files
// require __DIR__ . '/tail.php';

use App\SQLiteConnection as SQLiteConnection;
use App\SQLiteCreateTables as SQLiteCreateTables;
use App\SQLiteInsert as SQLiteInsert;

$parts = explode($argv, ",");

$pdo = (new SQLiteConnection())->connect();
$sqlite = new SQLiteInsert($pdo);
$sqlite->insertLog($parts[0], $parts[1], $parts[2], $parts[3],$parts[4], $parts[5], $parts[6], $parts[7],$parts[8], $parts[9], $parts[10], $parts[11],$parts[12], $parts[13], $parts[14], $parts[15],$parts[16], $parts[17], $parts[18], $parts[19], $parts[20]);
