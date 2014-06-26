<?php
	require_once('../../loader.php');

	if ( check_login_status() ) {
		$pri_name = $_POST['pri_name'];
		$pri_id = $_POST['pri_id'];

	/*	$str = "UPDATE admins SET name = '$pri_name' WHERE id = '$pri_id'";
		$result = mysql_query($str,$link) or die("fail");
		if($result == true){
			echo '修改成功 , 1秒後自動跳頁'; 
			echo '<meta http-equiv=REFRESH CONTENT=1;url=update_personal.php>';
		}
		else{
			echo '發生錯誤 , 請通知負責人員'; 
		}
	*/
		$update =  update_admin_pms( $pri_id, $pri_name);
		if($update == true){
			echo '修改成功 , 1秒後自動跳頁'; 
			echo '<meta http-equiv=REFRESH CONTENT=1;url=update_personal.php>';
		}
		else{
			echo '發生錯誤 , 請通知負責人員'; 
		}
	}
?>