<?php
function get_option( $option_name ) {
    $sql = 'SELECT * FROM options WHERE option_name=?';
    $result = sql_q( $sql, array($option_name) );
    if ( count($result) )
        return $result[0];
    else
        return array();
}

function save_option( $option_name, $option_value='' ) {
    $option = get_option( $option_name );
    if ( count($option) > 0 ) {
        $sql = 'UPDATE options SET option_value=? WHERE option_name=?';
        return sql_e( $sql, array($option_value, $option_name) );
    } else {
        $get_autoincrement = sql_q(
            'SHOW TABLE STATUS WHERE Name=?',
            array('options')
        );
        $autoincrement = $get_autoincrement[0]['Auto_increment'];
        $sql = 'INSERT INTO options(option_name, option_value) VALUES(?, ?)';
        
        if ( sql_e( $sql, array( $option_name, $option_value ) ) )
            return $autoincrement;
        else
            return false;
    }
}
