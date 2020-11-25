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

$parts = explode(",", $argv[1]);

$pdo = (new SQLiteConnection())->connect();
$sqlite = new SQLiteInsert($pdo);
$sqlite->insertLog($parts[0], $parts[1], $parts[3],$parts[4], $parts[5], $parts[6], $parts[7],$parts[8], $parts[9], $parts[10], $parts[11],$parts[12], $parts[13], $parts[14], $parts[15],$parts[16], $parts[17], $parts[18], $parts[19], $parts[20], $parts[21]);
echo "Values:\n";
echo "  Channel: " . $parts[0] . "\n";
echo "  Timestamp: " . $parts[1] . "\n";
echo "  Source: " . $parts[3] . "\n";
echo "  Heard: " . $parts[4] . "\n";
echo "  Audio Level: " . $parts[5] . "\n";
echo "  Error: " . $parts[6] . "\n";
echo "  DTI: " . $parts[7] . "\n";
echo "  Object Name: " . $parts[8] . "\n";
echo "  Symbol: " . $parts[9] . "\n";
echo "  Latitude: " . $parts[10] . "\n";
echo "  Longitude: " . $parts[11] . "\n";
echo "  Speed: " . $parts[12] . "\n";
echo "  Course: " . $parts[13] . "\n";
echo "  Altitude: " . $parts[14] . "\n";
echo "  Frequency: " . $parts[15] . "\n";
echo "  Offset: " . $parts[16] . "\n";
echo "  Tone: " . $parts[17] . "\n";
echo "  System: " . $parts[18] . "\n";
echo "  Status: " . $parts[19] . "\n";
echo "  Telemetry: " . $parts[20] . "\n";
echo "  Comment: " . $parts[21] . "\n\n";

