<?php
	require_once('../../loader.php');
	define( 'FILE_DIR', ROOT_DIR.'/web/publish/' );
	$raw = sql_q("SELECT upload_file,upload_picture FROM publish WHERE id=?",array($_POST['id']));
	$delf = "";
	foreach ($raw as $value) {
		$delf = $value['upload_file'];
		$delp = $value['upload_picture'];
	}
	$clean_file = iconv("utf-8//ignore", "big5", $delf);
	$clean_pic = iconv("utf-8//ignore", "big5", $delp);
    unlink(FILE_DIR.$clean_file);  #刪除舊的檔案
    unlink(FILE_DIR.$clean_pic);  #刪除舊的檔案
    sql_e("DELETE FROM publish WHERE id=?",array($_POST['id']));
?>