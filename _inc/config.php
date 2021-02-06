<?php

// show all errors
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);


// require stuff
if( !session_id() ) @session_start();
require_once 'vendor/autoload.php';


// constants & settings
define( 'BASE_URL', 'http://localhost:8888/blog' );
define( 'APP_PATH', realpath(__DIR__ . '/../') );


// configurations
$config = [

	'db' => [
		'type'     => 'mysql',
		'name'     => 'blog',
		'server'   => 'localhost:8889',
		'username' => 'root',
		'password' => 'root',
		'charset'  => 'utf8'
	]

];



// connect to db
$db = new PDO(
	"{$config['db']['type']}:host={$config['db']['server']};
	dbname={$config['db']['name']};charset={$config['db']['charset']}",
	$config['db']['username'], $config['db']['password']
);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);



// global functions
require_once 'functions-general.php';
require_once 'functions-string.php';
require_once 'functions-auth.php';
require_once 'functions-post.php';



// auth
include("vendor/PHPAuth-master/Config.php");
include("vendor/PHPAuth-master/Auth.php");



$auth_config = new PHPAuth\Config($db);
$auth   = new PHPAuth\Auth($db, $auth_config);

//$auth_config = new Config( $db );
//$auth = new Auth( $db, $auth_config, $lang );