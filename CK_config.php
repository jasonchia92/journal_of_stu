<?php
function get_dirName() {
	$path = str_replace( '\\', '/', dirname(__file__) );
	$arr = explode('/', $path);
	return $arr[count($arr)-1];
}

function get_baseUrl() {
	$dirName = get_dirName();
	$path_cn = strpos( $_SERVER['REQUEST_URI'], '/'.$dirName );
    $baseUrl = substr( $_SERVER['REQUEST_URI'], $path_cn, $path_cn+strlen($dirName)+1 ).'/web/cke_files/';

    return $baseUrl;
}

