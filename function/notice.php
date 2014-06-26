<?php
function get_notice( $notice_id=null ){
	if ( $notice_id != null ) {
		$result = sql_q("SELECT * FROM notice WHERE notice_id=? ", array($notice_id) );
		if ( count($result) > 0 )
			$result = $result[0];
	} else {
		$result = sql_q("SELECT * FROM notice ORDER BY notice_id DESC ", array() );
	}

    return $result;
}


function add_notice( $title, $content ){
	$sql = "INSERT INTO notice(
			title,
			content,
			post_by,
			date
		) VALUES(?, ?, ?, ?) ";

	$auth_data = get_auth_data();
	$array = array(
		$title,
		$content,
		$auth_data['id'],
		date("Y-m-d")
	);

    return sql_e( $sql, $array );
}


function update_notice( $notice_id, $title, $content ){
	$sql = "UPDATE notice SET
			title=?,
			content=?
		WHERE notice_id=? ";
	
	$array = array(
		$title,
		$content,
		$notice_id
	);

    return sql_e( $sql, $array );
}


function delete_notice( $notice_id ) {
	return sql_e("DELETE FROM notice WHERE notice_id=?", array($notice_id) );
}


function cke_output( $str ) {
	$str = str_replace('\"', '"', $str);
	$str = str_replace('<script', '&lt;script', $str);
	$str = str_replace('</script', '&lt;/script', $str);

	return $str;
}