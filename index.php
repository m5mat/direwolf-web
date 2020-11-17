<?php
    // In case one is using PHP 5.4's built-in server
    $filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if (php_sapi_name() === 'cli-server' && is_file($filename)) {
        return false;
    }

    // Require composer autoloader
    require __DIR__ . '/vendor/autoload.php';

    // Require other useful files
    // require __DIR__ . '/tail.php';

    use App\SQLiteConnection as SQLiteConnection;
    use App\SQLiteCreateTables as SQLiteCreateTables;
    use App\SQLiteInsert as SQLiteInsert;
    use App\SQLiteFetch as SQLiteFetch;

    // Create a Router
    $router = new \Bramus\Router\Router();

    // Custom 404 Handler
    $router->set404(function () {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo '404, route not found!';
    });

    // Before Router Middleware
    $router->before('GET', '/.*', function () {
        header('X-Powered-By: bramus/router');
    });

    // Static route: / (homepage)
    $router->get('/', function () {
        echo '
          <h1>bramus/router</h1>
          <p>Try these routes:<p>
          <ul>
            <li>/test-db</li>
            <li>/log</li>
            <li>/blog</li>
            <li>/blog/<em>year</em></li>
            <li>/blog/<em>year</em>/<em>month</em></li>
            <li>/blog/<em>year</em>/<em>month</em>/<em>day</em></li>
            <li>/movies</li>
            <li>/movies/<em>id</em></li>
          </ul>';
    });

    /*
     * Database Maintenance stuff
     */

    // Static route: /db-test
    $router->get('/db-test', function () {
      $pdo = (new \App\SQLiteConnection())->connect();
      if ($pdo != null)
        echo 'Connected to the SQLite database successfully!';
      else
        echo 'Whoops, could not connect to the SQLite database!';
    });

    // Static route: /db-initialise
    $router->get('/db-initialise', function () {
      $sqlite = new SQLiteCreateTables((new SQLiteConnection())->connect());

      // create new tables
      $sqlite->createTables();

      // get the table list
      $tables = $sqlite->getTableList();

      echo "<h3>Tables</h3><ul>";
      foreach ($tables as $table) {
        echo "<li>" . $table . "</li>";
      }
      echo "</ul>";
    });

    // Static route: /db-insert
    // This is only really intended for testing!
    $router->get('/db-insert', function () {
      echo "<form action='/db-insert' method='post'>
		Channel <input type='text' name='channel' /><br />
                Timestamp <input type='text' name='timestamp' /><br />
                Source <input type='text' name='source' /><br />
                Heard <input type='text' name='heard' /><br />
                Audio Level <input type='text' name='audio_level' /><br />
                Error <input type='text' name='error' /><br />
                DTI <input type='text' name='dti' /><br />
                Object Name <input type='text' name='object_name' /><br />
                Symbol <input type='text' name='symbol' /><br />
                Latitude <input type='text' name='latitude' /><br />
                Longitude <input type='text' name='longitude' /><br />
                Speed <input type='text' name='speed' /><br />
                Course <input type='text' name='course' /><br />
                Altitude <input type='text' name='altitude' /><br />
                Frequency <input type='text' name='frequency' /><br />
                Offset <input type='text' name='offset' /><br />
                Tone <input type='text' name='tone' /><br />
                System <input type='text' name='system' /><br />
                Status <input type='text' name='status' /><br />
                Telemetry <input type='text' name='telemetry' /><br />
                Comment <input type='text' name='comment' /><br />
		<input type='submit' />
            </form>";
    });

    $router->post('/db-insert', function () {
      $pdo = (new SQLiteConnection())->connect();
      $sqlite = new SQLiteInsert($pdo);
      $sqlite->insertLog(
	$_POST['channel'], $_POST['timestamp'], $_POST['source'], $_POST['heard'],
	$_POST['audio_level'], $_POST['error'], $_POST['dti'], $_POST['object_name'],
	$_POST['symbol'], $_POST['latitude'], $_POST['longitude'], $_POST['speed'],
	$_POST['course'], $_POST['altitude'], $_POST['frequency'], $_POST['offset'],
	$_POST['tone'], $_POST['system'], $_POST['status'], $_POST['telemetry'], $_POST['comment']
      );
    });

    // Static route: /db-dump
    $router->get('/db-dump', function () {
      $pdo = (new SQLiteConnection())->connect();
      $sqlite = new SQLiteFetch($pdo);

      echo '<table><tr><th>ID</th><th>Channel</th><th>Timestamp</th><th>Source</th><th>Heard</th><th>Audio Level</th><th>Error</th><th>DTI</th><th>Object Name</th><th>Symbol</th><th>Latitude</th><th>Longitude</th><th>Speed</th><th>Course</th><th>Altitude</th><th>Frequency</th><th>Offset</th><th>Tone</th><th>System</th><th>Status</th><th>Telemetry</th><th>Comment</th></tr>';
      foreach ( $sqlite->fetchLog() as $log_entry ) {
	      echo "<tr><td>" . $log_entry->id . "</td>";
        echo "<td>" . $log_entry->channel . "</td>";
        echo "<td>" . $log_entry->timestamp . "</td>";
        echo "<td>" . $log_entry->source . "</td>";
        echo "<td>" . $log_entry->heard . "</td>";
        echo "<td>" . $log_entry->audio_level . "</td>";
        echo "<td>" . $log_entry->error . "</td>";
        echo "<td>" . $log_entry->dti . "</td>";
        echo "<td>" . $log_entry->object_name . "</td>";
        echo "<td>" . $log_entry->symbol . "</td>";
        echo "<td>" . $log_entry->latitude . "</td>";
        echo "<td>" . $log_entry->longitude . "</td>";
        echo "<td>" . $log_entry->speed . "</td>";
        echo "<td>" . $log_entry->course . "</td>";
        echo "<td>" . $log_entry->altitude . "</td>";
        echo "<td>" . $log_entry->frequency . "</td>";
        echo "<td>" . $log_entry->offset . "</td>";
        echo "<td>" . $log_entry->tone . "</td>";
        echo "<td>" . $log_entry->system . "</td>";
        echo "<td>" . $log_entry->status . "</td>";
        echo "<td>" . $log_entry->telemetry . "</td>";
        echo "<td>" . $log_entry->comment . "</td>";
      }
      echo '</table>';
    });

    // Static route: /stations
    $router->get('/stations', function () {
      $pdo = (new SQLiteConnection())->connect();
      $sqlite = new SQLiteFetch($pdo);
      $stations = [];
      foreach ( $sqlite->fetchStationList() as $station ) {
        $stations[] = '{
                          "type": "Feature",
                          "geometry": {
                              "type": "Point",
                              "coordinates": [' . $station->longitude .', ' . $station->latitude . ']
                          },
                          "properties": {
                              "id": ' . $station->id . ',
                              "name": "' . $station->source . '",
                              "symbol": "' . $station->symbol . '"
                          }
                        }';
      }
      echo "[" . implode ( ",", $stations ) . "]";
    });

    // Dynamic route: /stations/[min_latitude]/[min_longitude]/[max_latitude]/[max_longitude]

    // Dynamic route: /log
    $router->get('/log(/\d)?', function ($fromId = 0) {
      $pdo = (new SQLiteConnection())->connect();
      $sqlite = new SQLiteFetch($pdo);
      $logLines = [];
      foreach ( $sqlite->fetchLog($fromId) as $logLine ) {
        $logLines[] = $logLine->id . "," . $logLine->channel . "," . $logLine->timestamp . "," . $logLine->source . "," . $logLine->heard . "," . $logLine->audio_level . "," . $logLine->error . "," . $logLine->dti . "," . $logLine->object_name . "," . $logLine->symbol . "," . $logLine->latitude . "," . $logLine->longitude . "," . $logLine->speed . "," . $logLine->course . "," . $logLine->altitude . "," . $logLine->frequency . "," . $logLine->offset . "," . $logLine->tone . "," . $logLine->system . "," . $logLine->status . "," . $logLine->telemetry . "," . $logLine->comment;
      }
      echo implode("<br />", $logLines);
    });

    // Dynamic route: /hello/name
    $router->get('/hello/(\w+)', function ($name) {
        echo 'Hello ' . htmlentities($name);
    });

    // Dynamic route: /ohai/name/in/parts
    $router->get('/ohai/(.*)', function ($url) {
        echo 'Ohai ' . htmlentities($url);
    });

    // Dynamic route with (successive) optional subpatterns: /blog(/year(/month(/day(/slug))))
    $router->get('/blog(/\d{4}(/\d{2}(/\d{2}(/[a-z0-9_-]+)?)?)?)?', function ($year = null, $month = null, $day = null, $slug = null) {
        if (!$year) {
            echo 'Blog overview';

            return;
        }
        if (!$month) {
            echo 'Blog year overview (' . $year . ')';

            return;
        }
        if (!$day) {
            echo 'Blog month overview (' . $year . '-' . $month . ')';

            return;
        }
        if (!$slug) {
            echo 'Blog day overview (' . $year . '-' . $month . '-' . $day . ')';

            return;
        }
        echo 'Blogpost ' . htmlentities($slug) . ' detail (' . $year . '-' . $month . '-' . $day . ')';
    });

    // Subrouting
    $router->mount('/movies', function () use ($router) {

        // will result in '/movies'
        $router->get('/', function () {
            echo 'movies overview';
        });

        // will result in '/movies'
        $router->post('/', function () {
            echo 'add movie';
        });

        // will result in '/movies/id'
        $router->get('/(\d+)', function ($id) {
            echo 'movie id ' . htmlentities($id);
        });

        // will result in '/movies/id'
        $router->put('/(\d+)', function ($id) {
            echo 'Update movie id ' . htmlentities($id);
        });
    });

    // Thunderbirds are go!
    $router->run();

// EOF
