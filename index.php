<?

require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->config('debug', true);
$app->config('templates.path', './views');


$app->get('/', 'Index');

// RESTfull

$app->get('/weathers', 'getWeathers');
$app->get('/weathers/:id', 'getWeather');
$app->post('/weathers', 'addWeather');	
$app->delete('/weathers/:id', 'delWeather');
$app->put('/weathers/:id', 'updateWeather');


$app->run();


function Index() {
	$app = \Slim\Slim::getInstance();

	$app->render('header.php');
	$app->render('index.php');
	$app->render('footer.php');	
}

function throwJSONError($error_message) {
	echo '{"error":{"text": '.$error_message.'}}';
}

function getWeathers() {
	
	$sql="SELECT * FROM weathers ORDER BY id ASC;";
	try {
		$db = getDatabase();
		$stmt = $db->query($sql);
		$weathers = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		echo json_encode($weathers);
	} catch(PDOException $e) {
		throwJSONError($e->getMessage());
	}
}

function getWeather($id) {
	$sql="SELECT * FROM weathers WHERE id=:id";
	try {
		$db = getDatabase();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $id);
		$stmt->execute();

		$weather = $stmt->fetchObject();
		$db = null;
		
		if(!$weather) {
			throwJSONError("NOT FOUND");
		} else
			echo json_encode($weather);
	
	} catch(PDOException $e) {
		throwJSONError($e->getMessage());
	}
}

function delWeather($id) {
	$sql="DELETE FROM weathers WHERE id=:id";
	try {
		$db = getDatabase();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;

	} catch(PDOException $e) {
		throwJSONError($e->getMessage());
	}
}

function addWeather() {
	$app = \Slim\Slim::getInstance();
	$request = $app->request;
	
	$weather = json_decode($request->getBody());

	$sql = "INSERT INTO weathers(city) VALUES (:city)";
	try {
		$db = getDatabase();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("city", $weather->city);
		$stmt->execute();
		$weather->id = $db->lastInsertId();
		$db = null;
		echo json_encode($weather);
	} catch(PDOException $e) {
		throwJSONError($e->getMessage());
	}
}

function updateWeather($id) {
	$app = \Slim\Slim::getInstance();
	$request = $app->request;
	$weather = json_decode($request->getBody());
	$sql = "UPDATE weathers SET city=:city WHERE id=:id";
	try {
		$db = getDatabase();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("city", $weather->city);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($weather);
	}catch(PDOException $e) {
		throwJSONError($e->getMessage());
	}

}

function getDatabase() {
	$dbhost = "127.0.0.1";
	$dbuser = "rest";
	$dbpass = "restp4ss";
	$dbname = "rest";

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	// throw an exception
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	return $dbh;
}



?>
