<?php

namespace App;

/**
 * SQLite connnection
 */
class SQLiteCreateTables {
    /**
     * PDO instance
     * @var type
     */
    private $pdo;

    /**
     * connect to the SQLite database
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * create tables 
     */
    public function createTables() {
        $commands = ['CREATE TABLE IF NOT EXISTS log ( id INTEGER PRIMARY KEY, channel INTEGER NOT NULL, timestamp INTEGER NOT NULL, source TEXT NOT NULL, heard TEXT NOT NULL, audio_level INTEGER NOT NULL, error INTEGER NOT NULL, dti TEXT NOT NULL, object_name TEXT NOT NULL, symbol TEXT NOT NULL, latitude REAL, longitude REAL, speed INTEGER, course INTEGER, altitude INTEGER, frequency REAL, offset INTEGER, tone TEXT, system TEXT, status TEXT, telemetry TEXT, comment TEXT );'];
        // execute the sql commands to create new tables
        foreach ($commands as $command) {
            $this->pdo->exec($command);
        }
    }

    /**
     * get the table list in the database
     */
    public function getTableList() {

        $stmt = $this->pdo->query("SELECT name
                                   FROM sqlite_master
                                   WHERE type = 'table'
                                   ORDER BY name");
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tables[] = $row['name'];
        }

        return $tables;
    }
}
