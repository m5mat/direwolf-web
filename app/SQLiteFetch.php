<?php

namespace App;

class SQLiteFetch {

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * Initialize the object with a specified PDO object
     * @param \PDO $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     *
     */
    public function fetchLog($fromId) {
        $sql = 'SELECT id, channel, timestamp, source, heard, audio_level,
          			error, dti, object_name, symbol, latitude, longitude,
          			speed, course, altitude, frequency, offset, tone,
          			system, status, telemetry, comment FROM log WHERE id >= :id;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $fromId);
        $stmt->execute();
        
      	$log_entries = [];
        while ( $row = $stmt->fetchObject() ) {
      	  $log_entries[] = $row;
      	}

        return $log_entries;
    }

    /**
     *
     */
    public function fetchStationList() {
        $sql = 'SELECT id, source, latitude, longitude, symbol FROM log;';
        $stmt = $this->pdo->query($sql);

      	$stations = [];
        while ( $row = $stmt->fetchObject() ) {
      	  $stations[] = $row;
      	}

        return $stations;
    }

}
