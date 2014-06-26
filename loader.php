<?php
$root_dir = str_replace('\\', '/', dirname(__FILE__) );
if ( !file_exists($root_dir.'/config.php') ){
	echo 'The root dir are missing file "config.php", you can copy config.sample.php and rename to config.php, don\'t forget change config content!';
	exit;
}

require_once('config.php');
require_once('base_function.php');

# Set environment variable
define( 'DIR_NAME',	get_dir_name() );
define( 'OS_IS_WINDOWS', ( strcmp(PHP_OS, 'WINNT') == 0 ? true : false ) );

# Dir path
define( 'ROOT_DIR',	$root_dir );
define( 'PLUGIN_DIR', ROOT_DIR.'/plugin' );
define( 'UPLOAD_DIR', ROOT_DIR.'/web/uploads' );
define( 'PUBLISH_DIR', ROOT_DIR.'/web/publish' );
define( 'MAIL_SAMPLE_DIR', ROOT_DIR.'/mail_sample' );

# Url path
define( 'ROOT_URL',	get_root_url() );
define( 'PLUGIN_URL', ROOT_URL.'/plugin' );
define( 'JS_URL', ROOT_URL.'/web/js' );
define( 'CSS_URL', ROOT_URL.'/web/bar' );
define( 'IMAGE_URL', ROOT_URL.'/web/images' );

global $db;
$db = new PDO(
	'mysql:host='.DB_HOST.';dbname='.DB_NAME.';',
	DB_UID, DB_PWD,
	array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'" )
);

start_session( 1800 );
load_function();
load_plugin();
check_page_pms();
