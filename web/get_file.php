<?php
	require_once( "../loader.php" );
	
	if ( !check_login_status(false) ) {
		echo "登入時效逾時，請關閉視窗後重新登入。<br/>";
		echo "<button onclick=\" window.parent.$('div#dialog').dialog('close'); \">關閉視窗</button>";
		exit;
	}


	if ( ! isset($_GET['pfc_id']) ){
		echo 'Access Denied.';
		exit;
	}

	$file_info = get_file_info($_GET['pfc_id']);

	// $file = $_GET['file'];

	# Get paper id
	// $pid = substr( $file, 2, 10);

	# File access permission authenticate
	if ( ( count($file_info) == 0 ) || (check_file_pms($file_info['paper_id']) == false) ){
		echo 'Permission Denied.';
		exit;
	}

	# File Missing.
	if ( get_file($file_info) == false )
		echo 'File is missing.';