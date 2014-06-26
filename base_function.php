<?php
function get_dir_name() {
    $path = str_replace( '\\', '/', dirname(__file__) );
    $arr = explode('/', $path);
    return $arr[count($arr)-1];
}


function get_root_url() {
    $full_url = get_full_url();
    return substr( $full_url, 0, strpos( $full_url, DIR_NAME)+strlen( DIR_NAME ) );
}


function get_web_path_url() {
    return substr( $_SERVER['REQUEST_URI'], 0, strpos( $_SERVER['REQUEST_URI'], DIR_NAME)+strlen( DIR_NAME ) );
}


function get_full_url() {
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    return
        ($https ? 'https://' : 'http://').
        (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
        (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
        ($https && $_SERVER['SERVER_PORT'] === 443 ||
        $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
        substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
}


function start_session( $expire = 0 ) {
    if ( $expire == 0 ) {
        $expire = ini_get( 'session.gc_maxlifetime' );
    } else {
        ini_set( 'session.gc_maxlifetime', $expire );
    }

    if ( empty($_COOKIE['PHPSESSID']) ) {
        session_set_cookie_params( $expire, get_web_path_url() );
        session_start();
    } else {
        session_start();
        setcookie( 'PHPSESSID', session_id(), time() + $expire );
    }
}


function check_login_status( $redirect = true ){
    if ( isset( $_SESSION[ session_id() ]['id'] ) ) {
        setcookie( session_name(), $_COOKIE[ session_name() ], time()+1800, get_web_path_url() );
        return true;
    } else {
        if ( $redirect ) header('Location: '.ROOT_URL.'/web/login.php');
        return false;
    }
}


function load_function() {
    $func_arr = glob( ROOT_DIR.'/function/*.php' );
    foreach ( $func_arr as $key => $value )
        require_once( $value );
}


function sprint_r( $array ) {
    echo '<pre style="text-align: left; border: 1px dashed #888; padding: 10px; background-color: #EEE; color: #222;">';
    print_r($array);
    echo '</pre>';
}


// function make_log( $msg ) {
//     $file = fopen(ROOT_DIR.'/log.txt', 'a+');
//     $write_msg = "[".date('Y-m-d H:i:s')."] ".$msg."\n";
//     fwrite($file, $write_msg);
//     fclose($file);
// }


function load_plugin() {
    require_once(PLUGIN_DIR.'/wp-page-numbers/wp-page-numbers.php');
    require_once(PLUGIN_DIR.'/PHPMailer/class.phpmailer.php');
}


function func_queue_add( $func_name, $func_params=array() ) {
    global $func_queue;

    if ( !is_array($func_params) )
        $func_params = array( $func_params );

    $func_queue[] = array(
        'func_name' => $func_name,
        'func_params' => $func_params
    );
}


function func_queue_handler_start() {
    global $func_queue;

    if ( count($func_queue) > 0 ) {
        foreach ($func_queue as $value)
            call_user_func_array( $value['func_name'], $value['func_params'] );
    }
}