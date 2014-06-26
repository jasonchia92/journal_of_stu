<?php
	require_once("../../loader.php");
	
	$con = $_POST['content'];	
	$id = $_POST['id'];
	sql_e("UPDATE review_record SET opinion = ? WHERE paper_id = ?",array($con,$id));
//	execute_review( $_POST['id'], "", $_POST['opinion'], $_POST['result'] );


?>