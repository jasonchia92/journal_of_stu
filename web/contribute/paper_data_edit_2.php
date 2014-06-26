<?php
	require_once('../../loader.php');
	if ( !check_login_status(false) ) {
		echo "登入時效逾時，請關閉視窗後重新登入。<br/>";
		echo "<button onclick=\" window.parent.$('div#dialog').dialog('close'); \">關閉視窗</button>";
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>編輯論文資料::論文管理系統 - 樹德科技大學學報</title>
	<style type="text/css">
		body {
			padding: 0px;
			text-align: center;
		}

		div {
			margin-top: 50%;
		}

		pre {
			text-align: left;
		}
	</style>
</head>
<body>
	<?php
		# format to sys_id
		$pid = get_sys_pid($_POST['paper_id']);

		# 刪除作者資料
		if ( isset($_POST['delete_authors']) && count($_POST['delete_authors']) ) {
			echo '<br/>Delete authors data......';
			# 尋找 change_authors 的重複值，避免與change_authors 衝突
			foreach ($_POST['delete_authors'] as $key => $value)
				$_POST['change_authors'] = str_replace($value.',', '', $_POST['change_authors']);
			
			sql_e( "DELETE FROM authors_data WHERE id IN (".acts($_POST['delete_authors'], 1).") ", array() );
			echo 'Success!<br/>';
		}

		# 作者資料變更過時 update
		if ( strlen($_POST['change_authors']) ) {
			echo '<br/>Update authors data......';
			$queue = explode(',', $_POST['change_authors']);
			unset( $queue[ count($queue)-1 ] );
			foreach ($queue as $key => $value) {
				$_POST['authors_data'][$value]['id'] = $value;
				$sql = "UPDATE authors_data SET 
					ch_name=?,
                    en_name=?,
                    ch_titles=?,
                    en_titles=?,
                    ch_serve_unit=?,
                    en_serve_unit=?,
                    phone=?,
                    email=?
                    WHERE id=?
				";

				# array key rebind
				$array = array();
				foreach ($_POST['authors_data'][$value] as $key => $value)
					$array[] = $value;

				sql_e( $sql, $array );
			}
			echo 'Success!<br/>';
		}

		#無資料時不進行動作
		if( isset($_POST['new_ch_name']) ){
			echo '<br/>Create authors data......';
			# 跑迴圈寫入新加入的作者資料
			for( $i=0 ; $i<count($_POST['new_ch_name']) ; $i++ ){
				$bln = add_author_data(
					$pid,
					0, # 其他作者
					$_POST['new_ch_name'][$i],
					$_POST['new_en_name'][$i],
					$_POST['new_ch_serve_unit'][$i],
					$_POST['new_en_serve_unit'][$i],
					$_POST['new_ch_titles'][$i],
					$_POST['new_en_titles'][$i],
					$_POST['new_email'][$i],
					$_POST['new_phone'][$i]
				);

				if ( false === $bln ) throw new Exception('Create authors data has been error at web/contribute/paper_data_edit_2.php line 79.');
				sleep(0.5);
			}
			echo 'Success!<br/>';
		}

		$addon_syntax = "";
		if ( isset($_POST['modify_finish']) && $_POST['modify_finish'] )
			$addon_syntax = ", paper_file_check='0', smu_edit_enable='0' ";

		$sql = "UPDATE papers SET
			ch_title=?,
			en_title=?,
			ch_summary=?,
			en_summary=?,
			keywords=?
			".$addon_syntax."
			WHERE id=?";
		$array = array(
			$_POST['ch_title'], 
			$_POST['en_title'], 
			$_POST['ch_summary'], 
			$_POST['en_summary'], 
			$_POST['keywords'], 
			$pid
		);

		# pfcr template proccess
		pfcr_file_uploader(0);

		# 判斷修改結果
		if ( sql_e($sql, $array) )
			echo "修改完成!";
		else
			echo "發生預期外錯誤，請聯絡系統管理員！";
	?>
	
	<br/><button onclick=" window.parent.location.reload().$('#dialog').dialog('close'); ">關閉視窗</button>
</body>
</html>