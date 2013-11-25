<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(realpath(__DIR__.'/app/Model')), $isDevMode, realpath(__DIR__.'/proxies'));
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
/*$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
);*/

$services_json = json_decode(getenv("VCAP_SERVICES"),true);
$mysql_config = $services_json["mysql-5.1"][0]["credentials"];

$username = $mysql_config["username"];
$password = $mysql_config["password"];
$hostname = $mysql_config["hostname"];
$port = $mysql_config["port"];
$db = $mysql_config["name"];

$conn = array(
    'driver' => 'pdo_mysql',
    'dbname' => $mysql_config["name"],
    'user' => $mysql_config["username"],
    'password' => $mysql_config["password"],
    'host' => $mysql_config["hostname"],
    'port' => $mysql_config["port"],
	'charset' => 'utf8'
);

// obtaining the entity manager
$em = EntityManager::create($conn, $config);

$platform = $em->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('enum', 'string');
$platform->registerDoctrineTypeMapping('set', 'string');

$GLOBALS['em'] = $em;
