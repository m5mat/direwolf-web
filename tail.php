<?php

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

use App\SQLiteConnection as SQLiteConnection;
use App\SQLiteCreateTables as SQLiteCreateTables;
use App\SQLiteInsert as SQLiteInsert;
use App\PHPTail as PHPTail;

/**
 * Initilize a new instance of PHPTail
 * @var PHPTail
 */

$tail = new PHPTail(array(
    "logfile" => $argv[1],
));

$lastSize = 0;

while ( true ) {
    $response = json_decode($tail->getNewLines("logfile", $lastSize, "", false));

    // If $response->data is not empty then ingest lines
    // HOWEVER, if $lastSize = 0 then don't ingest because we've probably restarted and we'll end up re-ingesting
    if ( sizeof($response->data) > 0 && $lastSize > 0) {

      $pdo = (new SQLiteConnection())->connect();
      $sqlite = new SQLiteInsert($pdo);

      foreach ( $response->data as $line ) {
        echo $line . "\n";
        $parts = explode(",", $line);
        $sqlite->insertLog($parts[0], $parts[1], $parts[3], $parts[4], $parts[5], $parts[6], $parts[7],$parts[8], $parts[9], $parts[10], $parts[11],$parts[12], $parts[13], $parts[14], $parts[15],$parts[16], $parts[17], $parts[18], $parts[19], $parts[20], $parts[21]);
      }
    }

    $lastSize = $response->size;

    sleep(3);
}
