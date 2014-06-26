<?php
/* system
paper_data_id_convert( $data_array );
	id處理
傳入參數：
	$data_array：論文資料陣列，一維或二維皆可
傳出參數：
	將id與language合併後的論文資料一維陣列，依照傳入維度而傳出不同維度
*/
function paper_data_id_convert( $data_array ){
	if( count($data_array) == 0 ){
		$data_array = array();
	}else{
		if( isset( $data_array[0] ) ){
			foreach ($data_array as $key => $value) {
				$data_array[$key]['id'] = $data_array[$key]['language']."-".$data_array[$key]['id'];
				# unset($data_array[$key]['language']);
			}
		}else{
			$data_array['id'] = $data_array['language']."-".$data_array['id'];
			# unset($data_array['language']);
		}
	}

	return $data_array;
}


/* system
get_sys_pid( $str );
	取得論文系統id (12碼轉10碼)
傳入參數：
	$pid：論文id，string
傳出參數：
	轉換過後的pid，string
*/
function get_sys_pid( $pid ) {
	return strlen( $pid ) > 10 ? substr( $pid, 2 ) : $pid;
}


function get_max_paper_id(){
	$str = "SELECT MAX(id) 
		FROM papers 
		WHERE id LIKE ? ";
	$current_max_id = sql_q($str, array( date("y-m-d-").'%' ) );

	$new_id = date("y-m-d-");
	if( strcmp($current_max_id[0]['MAX(id)'], "") == 0 )
		$new_id .= "a";
	else
		$new_id .= chr( ( ord($current_max_id[0]['MAX(id)']{9}) + 1 ) );

	return $new_id;
}


/*
paper_add( $language, $ch_title, $en_title, $primary_author, $else_authors, $ch_summary, $en_summary, $keywords)
	新增論文
傳入參數：
	$language：語言(1:中文. 2:英文. 3:其他)
	$ch_title：中文標題
	$en_title：英文標題
	$primary_author：主要(第一)作者
	$else_authors：其他作者
	$ch_summary：中文摘要
	$en_summary：英文摘要
	$keywords：關鍵字
	$category：論文類別，int (Length:4)
	$submit_user：提交者，int
傳出參數：
	無
*/
function paper_add( $language, $ch_title, $en_title, $ch_summary, $en_summary, $keywords, $category, $submit_user ){
	$new_id = get_max_paper_id();

	$str = "INSERT INTO papers(
		id, 
		language, 
		ch_title, 
		en_title, 
		ch_summary, 
		en_summary, 
		keywords, 
		submit_date, 
		category,
		submit_user
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$sql_array = array(
		$new_id, 
		$language, 
		$ch_title, 
		$en_title, 
		$ch_summary, 
		$en_summary, 
		$keywords, 
		date("Y-m-d"), 
		$category,
		$submit_user
	);

	return ( sql_e($str, $sql_array) );
}


/*
paper_update( $id, $ch_title, $en_title, $primary_author, $else_authors, $ch_summary, $en_summary, $keywords)
	論文更新
傳入參數：
	$language：語言(1:中文. 2:英文. 3:其他)
	$ch_title：中文標題
	$en_title：英文標題
	$primary_author：主要(第一)作者
	$else_authors：其他作者
	$ch_summary：中文摘要
	$en_summary：英文摘要
	$keywords：關鍵字
	$category：論文類別，int (Length:4)
傳出參數：
	無
*/
function paper_update( $id, $ch_title, $en_title, $ch_summary, $en_summary, $keywords, $category ){
	$sql = "UPDATE papers SET
		ch_title=?,
		en_title=?,
		ch_summary=?,
		en_summary=?,
		keywords=?,
		category=?
		WHERE id=?";
	$sql_array = array(
		$ch_title, 
		$en_title, 
		$ch_summary, 
		$en_summary, 
		$keywords, 
		$category,
		$id
	);

	return sql_e($sql, $sql_array);
}


function paper_delete( $paper_id ){
	//id convert
	$paper_id = get_sys_pid($paper_id);

	// delete files
	$paper_files = glob(UPLOAD_DIR.'/*'.$paper_id.'*');
	foreach ($paper_files as $key => $value)
		if ( file_exists($value) ) unlink( $value );

	//delete data in the table-pfc_record
	$action_1 = sql_e( "DELETE FROM pfc_record WHERE paper_id=?", array($paper_id) );
	//delete data in the table-review_record
	$action_2 = sql_e( "DELETE FROM review_record WHERE paper_id=?", array($paper_id) );
	//delete data in the table-authors_data
	$action_3 = sql_e( "DELETE FROM authors_data WHERE paper_id=?", array($paper_id) );
	//delete data in the table-papers
	$action_4 = sql_e( "DELETE FROM papers WHERE id=?", array($paper_id) );

	return ( $action_1 && $action_2 && $action_3 && $action_4 );
}


/*
get_paper_inf( $id );
	取得單一論文資訊
傳入參數：
	$id：論文id，string
傳出參數：
	論文完整資訊(資料庫上所有欄位紀錄)，一維陣列
*/
function get_paper_inf( $id ){
	$str = "SELECT * FROM papers WHERE id=?";
	$sql_array = array( get_sys_pid($id) );
	$result = sql_q($str, $sql_array);
	if( count($result) > 0 )
		$result = paper_data_id_convert( $result[0] );

	return $result;
}


/*
get_papers_list( $type, $page_num, $slice );
	取得一頁的資料
傳入參數：
	$type：選取類型，int
		-1:所有論文.
		0:等待預審.
		1:待提交複審稿件.
		2:等待複審.
		3:複審中.
		4:複審完成. (附加review_recode欄位紀錄)
		5:流程結束.
	$page_num：頁數，int
	$slice：資料需求筆數，int
傳出參數：
	二維陣列
*/
function get_papers_list( $type=-1 ){
	$sql = "SELECT a.*, b.ch_name, b.ch_serve_unit
		FROM papers AS a LEFT JOIN authors_data AS b
		ON a.id=b.paper_id 
		AND b.author_type=? ";
	$array = array( 1 );

	if( $type != -1 ){
		$sql .= "WHERE status=? ";
		$array[] = $type;
	}

	$sql .= "ORDER BY id DESC ";

	return paper_data_id_convert( sql_q( $sql, $array ) );
}


/* 未使用
get_papers_list_by_user( $type, $id, $page_num, $slice );
	以使用者id取得一頁的資料
傳入參數：
	$type：選取類型，int
		-1:所有論文.
		0:等待預審.
		1:待提交複審稿件.
		2:等待複審.
		3:複審中.
		4:複審完成. (附加review_recode欄位紀錄)
		5:流程結束.
	$id：使用者ID，int
	$page_num：頁數，int
	$slice：資料需求筆數，int
傳出參數：
	二維陣列
*/
function get_papers_list_by_user( $type, $id ){
	$result = array();
	if( $type == -1 ){
		$str = "SELECT id, language, ch_title, en_title, submit_date, status, category 
			FROM papers 
			WHERE submit_user=? ";
		$result = sql_q( $str, array($id) );
	}else{
		$str = "SELECT id, language, ch_title, en_title, submit_date, status
			FROM papers 
			WHERE submit_user=? AND status=? ";
		$sql_array = array( $id, $type);
		$result = sql_q( $str, $sql_array );
	}

	return paper_data_id_convert( $result );
}


/* 未使用
get_papers_list_by_category( $category );
	以類別取得論文清單
傳入參數：
	$id：類別代碼，int
	$page_num：頁數，int
	$slice：資料需求筆數，int
傳出參數：
	二維陣列
*/
function get_papers_list_by_category( $category ){
	$str = "SELECT id, language, ch_title, en_title 
		FROM papers 
		WHERE category=? ";
	$result = sql_q( $str, array($category) );
	return paper_data_id_convert( $result );
}


/* 待修改
get_papers_by_user( $id, $type );
	由使用者id取得論文基本資料
傳入參數：
	$id：使用者id，int
	$type：選取類型，int
		-1:所有論文.
		0:等待預審.
		1:待提交複審稿件.
		2:等待複審.
		3:複審中.
		4:複審完成. (附加review_recode欄位紀錄)
		5:流程結束.
傳出參數：
	二維陣列
*/
function get_papers_by_user( $id, $type ){
	$result = array();
	if( $type != -1 ) {
		$str = "SELECT id, language, ch_title, en_title, submit_date, status, category 
			FROM papers 
			WHERE submit_user=? AND status=?";
		$result = sql_q( $str , array($id, $type) );
	} else {
		$str = "SELECT id, language, ch_title, en_title, submit_date, status
			FROM papers 
			WHERE submit_user=?";
		$result = sql_q( $str , array($id) );
	}

	return paper_data_id_convert( $result );
}

