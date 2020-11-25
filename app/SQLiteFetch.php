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
//        $sql = 'SELECT id, source, latitude, longitude, symbol FROM log GROUP BY source ORDER BY timestamp DESC;';
        $sql = 'SELECT log.id, log.source, log.latitude, log.longitude, log.symbol FROM (SELECT source, MAX(timestamp) AS timestamp FROM log GROUP BY source) AS latest_positions INNER JOIN log ON log.source = latest_positions.source AND log.timestamp = latest_positions.timestamp;';
        $stmt = $this->pdo->query($sql);

      	$stations = [];
        while ( $row = $stmt->fetchObject() ) {
      	  $stations[] = $row;
      	}

        return $stations;
    }

    /*
     * Get the track of a station
     * defaults to 6 hours
     */
    public function fetchTrack($callsign, $max_time = (6 * 60 * 60)) {
        $sql = "SELECT timestamp, latitude, longitude FROM log WHERE source = :callsign AND timestamp > (strftime('%s', 'now') - :interval) ORDER BY timestamp ASC;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':callsign', $callsign);
        $stmt->bindValue(':interval', $max_time);
        $stmt->execute();

        $log_entries = [];
        while ( $row = $stmt->fetchObject() ) {
          $log_entries[] = [ (float)$row->longitude, (float)$row->latitude ];
        }

        return $log_entries;

    }
}
