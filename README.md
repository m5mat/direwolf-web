# direwolf-web

## Installation

 1. git clone to destination directory
 1. Pull in dependencies `composer update` or `php composer.phar update`.
 1. Start the server `./start-server.sh` or `start-server.bat`

## Log Ingesting

### With swatch

Config file:

`watchfor /.*/ exec php insert.php $0`

Start with:

`swatch --config-file=swatch.config --tail-file=/var/log/direwolf/direwolf.log`

## Log format

 1. Radio Channel
 2. Unix Timestamp
 3. Timestamp
 4. Source (sending station callsign)
 5. Heard
 6. Audio Level
 7. Error (0=CRC correct, 1=able to correct CRC by changing one bit, >2=don't trust)
 8. DTI
 9. Object Name (or sending station callsign)
 10. Symbol
 11. Latitude
 12. Longitude
 13. Speed
 14. Course
 15. Altitude
 16. Frequency
 17. Offset
 18. Tone
 19. System
 20. Status
 21. Telemetry
 22. Comment

### Log Table SQL

CREATE TABLE IF NOT EXISTS log (
    id            INTEGER PRIMARY KEY,
    channel       INTEGER NOT NULL,
    timestamp     INTEGER NOT NULL,
    source        TEXT NOT NULL,
    heard         TEXT NOT NULL,
    audio_level   INTEGER NOT NULL,
    error         INTEGER NOT NULL,
    dti           TEXT NOT NULL,
    object_name   TEXT NOT NULL,
    symbol        TEXT NOT NULL,
    latitude      REAL,
    longitude     REAL,
    speed         INTEGER,
    course        INTEGER,
    altitude      INTEGER,
    frequency     REAL,
    offset        INTEGER,
    tone          TEXT,
    system        TEXT,
    status        TEXT,
    telemetry     TEXT,
    comment       TEXT
);

### Example Log

0,1433462411,2015-06-05T00:00:11Z,W8EH,KI6SZ-2?,46(22/16),0,=,W8EH,/_,39.507500,-84.366333,,,,,,,UIview 32 bit apps,,,
0,1433462414,2015-06-05T00:00:14Z,WC8EMA,W8BLV,48(22/18),0,=,WC8EMA,S#,39.462000,-84.153000,,,,146.865,,,UIview 32 bit apps,,,2/146.865MHz T118 netMon830pm
0,1433462415,2015-06-05T00:00:15Z,WC8EMA,KI6SZ-2,50(22/15),0,=,WC8EMA,S#,39.462000,-84.153000,,,,146.865,,,UIview 32 bit apps,,,2/146.865MHz T118 netMon830pm
0,1433462416,2015-06-05T00:00:16Z,W8EH,W8VFR-10?,36(15/15),0,=,W8EH,/_,39.507500,-84.366333,,,,,,,UIview 32 bit apps,,,
0,1433462417,2015-06-05T00:00:17Z,WC8EMA,W8VFR-10?,36(16/15),0,=,WC8EMA,S#,39.462000,-84.153000,,,,146.865,,,UIview 32 bit apps,,,2/146.865MHz T118 netMon830pm
0,1433462419,2015-06-05T00:00:19Z,WC8EMA,WIDE2-2,32(12/13),0,=,WC8EMA,S#,39.462000,-84.153000,,,,146.865,,,UIview 32 bit apps,,,2/146.865MHz T118 netMon830pm
0,1433462424,2015-06-05T00:00:24Z,W8SAI-7,W8BLV?,48(22/17),0,`,W8SAI-7,/j,39.352833,-84.327333,0.0,132.0,262.0,,,,Yaesu FT1D,In Service,,
0,1433462424,2015-06-05T00:00:24Z,W8SAI-7,KI6SZ-2?,47(22/15),0,`,W8SAI-7,/j,39.352833,-84.327333,0.0,132.0,262.0,,,,Yaesu FT1D,In Service,,
0,1433462426,2015-06-05T00:00:26Z,W8SAI-7,W8VFR-10?,36(15/15),0,`,W8SAI-7,/j,39.352833,-84.327333,0.0,132.0,262.0,,,,Yaesu FT1D,In Service,,
0,1433462452,2015-06-05T00:00:52Z,KS9N-10,W8VFR-1,36(14/15),0,!,KS9N-10,/#,39.821500,-84.890167,,,,,,,>40 APRSmax,,,W1 Digi Omni Richmond
0,1433462458,2015-06-05T00:00:58Z,KS9N-10,W8VFR-10?,36(15/15),0,!,KS9N-10,/#,39.821500,-84.890167,,,,,,,>40 APRSmax,,,W1 Digi Omni Richmond

## Database

SQLite tutorial here: https://www.sqlitetutorial.net/sqlite-php/connect/
