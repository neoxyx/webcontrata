<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';

// Conexión base de datos centralizada 
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => '138.128.162.35',
	'username' => 'admcontrata_admin',
	'password' => 'jhV_3103',
	'database' => 'admcontrata_contrata',
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
