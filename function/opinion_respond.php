<?php
/*
send_opinion
    傳送意見回應資料
傳入參數：
    $name：姓名，string
    $email：電子信箱，string
    $keynote：主旨，string
    $identity：身份，int
        0:作者.
        1:評閱者.
        2:編輯委員.
        3:其他.
    $content：內容，string
傳出參數：
    無
*/
function send_opinion( $name, $email, $keynote, $identity, $content ){
    $sql = "INSERT INTO opinion_respond(
                date,
                name,
                email,
                keynote,
                identity,
                content
            ) VALUES(?, ?, ?, ?, ?, ?)";
    $sql_array = array(
        date("Y-m-d"),
        $name,
        $email,
        $keynote,
        $identity,
        $content
    );

    return sql_e($sql, $sql_array);
}


function get_opinion_list( $view_rows=10 ) {
    $sql = 'SELECT * FROM opinion_respond ORDER BY opinion_id DESC';
    $result = sql_q_adv( $sql, array(), $view_rows );

    if ( count($result->data) > 0 )
        return $result;
    else
        return false;
}


function get_single_opinion_data( $opinion_id ) {
    $sql = 'SELECT * FROM opinion_respond WHERE opinion_id=?';
    $result = sql_q( $sql, array($opinion_id) );

    if ( count($result) > 0 )
        return $result[0];
    else
        return false;
}


function reply_opinion( $opinion_id, $reply_content ) {
    $sql  = 'UPDATE opinion_respond SET replied=?, reply_content=? WHERE opinion_id=?';
    $data = array(1, $reply_content, $opinion_id);

    return sql_e($sql, $data);
}


function delete_opinion( $opinion_id ) {
    if ( is_array($opinion_id) == false )
        $opinion_id = array($opinion_id);

    $query_string = acts($opinion_id, 1);
    $sql = 'DELETE FROM opinion_respond WHERE opinion_id IN('.$query_string.')';

    return sql_e($sql, array());
}

