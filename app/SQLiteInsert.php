<?php

namespace App;

class SQLiteInsert {

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
     * Insert a new project into the projects table
     * @param string $projectName
     * @return the id of the new project
     */
    public function insertLog(
	$channel, $timestamp, $source, $heard, $audio_level, 
	$error, $dti, $object_name, $symbol, $latitude, $longitude, 
	$speed, $course, $altitude, $frequency, $offset, $tone, 
	$system, $status, $telemetry, $comment) {
          $sql = 'INSERT INTO log(channel, timestamp, source, heard, audio_level,
			error, dti, object_name, symbol, latitude, longitude,
			speed, course, altitude, frequency, offset, tone, 
			system, status, telemetry, comment)
		  VALUES(:channel, :timestamp, :source, :heard, :audio_level,
                        :error, :dti, :object_name, :symbol, :latitude, :longitude,
                        :speed, :course, :altitude, :frequency, :offset, :tone,
                        :system, :status, :telemetry, :comment)';
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':channel', $channel);
	  $stmt->bindValue(':timestamp', $timestamp);
          $stmt->bindValue(':source', $source);
          $stmt->bindValue(':heard', $heard);
          $stmt->bindValue(':audio_level', $audio_level);
          $stmt->bindValue(':error', $error);
          $stmt->bindValue(':dti', $dti);
          $stmt->bindValue(':object_name', $object_name);
          $stmt->bindValue(':symbol', $symbol);
          $stmt->bindValue(':latitude', $latitude);
          $stmt->bindValue(':longitude', $longitude);
          $stmt->bindValue(':speed', $speed);
          $stmt->bindValue(':course', $course);
          $stmt->bindValue(':altitude', $altitude);
          $stmt->bindValue(':frequency', $frequency);
          $stmt->bindValue(':offset', $offset);
          $stmt->bindValue(':tone', $tone);
          $stmt->bindValue(':system', $system);
          $stmt->bindValue(':status', $status);
          $stmt->bindValue(':telemetry', $telemetry);
          $stmt->bindValue(':comment', $comment);
          $stmt->execute();

        return $this->pdo->lastInsertId();
    }

}
