<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;
/*$tnsname = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = "10.201.31.3")(PORT = 1520))
		(CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = lottdllo)))';*/

// Cadena conexión bbdd oracle desarrollo
$tnsname = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = "10.201.32.5")(PORT = 1524))
(CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = lottipru)))';


// Conexión base de datos centralizada 
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'lottired-qa.cvdkn6zyxdwz.us-east-1.rds.amazonaws.com',
	'username' => 'lottired',
	'password' => 'L0TT1R3D2019.',
	'database' => 'cms-lottired',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

// Conexión base de datos oracle desarrollo
$db['oracle'] = array(
	'dsn'	=> '',
	'hostname' => $tnsname,
	'username' => 'portal_dml',
	'password' => 'sdcd5bf',
	'database' => '',
	'dbdriver' => 'oci8',
	'dbprefix' => '',
	'pconnect' => TRUE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
