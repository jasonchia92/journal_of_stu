<?php

/*
add_author_data( $ch_name, $en_name, $ch_serve_unit, $en_serve_unit, $ch_titles, $en_titles, $email, $phone );
	新增作者資料
傳入參數：
	$paper_id：該作者對應論文id，string
	$author_type：作者類型
		0：其他作者
		1：主要作者
	$ch_name：姓名(中文)，string
	$en_name：姓名(英文)，string
	$ch_serve_unit：服務單位(中文)，string
	$en_serve_unit：服務單位(英文)，string
	$ch_titles：職稱(中文)，string
	$en_titles：職稱(英文)，string
	$email：電子郵件，string
	$phone：連絡電話，int
傳出參數：
	該作者資料所屬id，int
*/
function add_author_data( $paper_id, $author_type, $ch_name, $en_name, $ch_serve_unit, $en_serve_unit, $ch_titles, $en_titles, $email, $phone ){
	if( strcmp($email, "") == 0 ){
		return false;
	}else{
		$str = "INSERT INTO authors_data(
			paper_id, 
			author_type, 
			ch_name, 
			en_name, 
			ch_serve_unit, 
			en_serve_unit, 
			ch_titles, 
			en_titles, 
			email, 
			phone ) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$array = array(
			$paper_id, 
			$author_type, 
			$ch_name, 
			$en_name, 
			$ch_serve_unit, 
			$en_serve_unit, 
			$ch_titles, 
			$en_titles, 
			$email, 
			$phone );
		return sql_e( $str, $array );
	}
}


/*
update_author_data( $author_id, $ch_name, $en_name, $ch_serve_unit, $en_serve_unit, $ch_titles, $en_titles, $email, $phone );
	修改作者資料
傳入參數：
	$author_id
	$ch_name：姓名(中文)，string
	$en_name：姓名(英文)，string
	$ch_serve_unit：服務單位(中文)，string
	$en_serve_unit：服務單位(英文)，string
	$ch_titles：職稱(中文)，string
	$en_titles：職稱(英文)，string
	$email：電子郵件，string
	$phone：連絡電話，int
傳出參數：
	true or false
*/
function update_author_data( $author_id, $ch_name, $en_name, $ch_serve_unit, $en_serve_unit, $ch_titles, $en_titles, $email, $phone ){
	$str = "UPDATE authors_data SET 
		ch_name=?, 
		en_name=?, 
		ch_serve_unit=?, 
		en_serve_unit=?, 
		ch_titles=?, 
		en_titles=?, 
		email=?, 
		phone=? 
		WHERE id=?";
	$array = array(
		$ch_name, 
		$en_name, 
		$ch_serve_unit, 
		$en_serve_unit, 
		$ch_titles, 
		$en_titles, 
		$email, 
		$phone,
		$author_id );
	return sql_e( $str, $array );
}


/*
get_author_data( $id );
	取得作者資料 (以作者id取得)
傳入參數：
	$id：作者id，int 或 array 皆可
傳出參數：
	二維陣列
*/
function get_author_data( $id ){
	$str = "";
	if( is_array($id) )
		$str = acts($id, 1); //array convert to string
	else
		$str = sqh($id);

	return sql_q("SELECT * FROM authors_data WHERE id IN('".$str."') ", array() );
}


/*
get_paper_authors_data( $pid [ ,$type=-1 ] );
	取得作者資料 (以論文ID取得)
傳入參數：
	$pid：作者id，int 或 array 皆可
	$type：作者類型
		-1：全部 (Default)
		0：其他作者
		1：主要作者
傳出參數：
	二維陣列
*/
function get_paper_authors_data( $pid, $type=-1 ){
	$pid = get_sys_pid($pid);
	if ( !is_array($pid) )
		$pid = array($pid);
	$arr_acts = acts($pid, 1);
	$sql = "SELECT * FROM authors_data WHERE paper_id IN(".$arr_acts.") ";
	$array = array();
	if ( $type != -1 ) {
		$sql .= "AND author_type=? ";
		$array[] = $type;
	}
	
	return sql_q( $sql, $array );
}


/*
delete_author_data( $author_id );
	刪除作者資料

Parameters:
	$author_id

Returns:
	True
*/
function delete_author_data( $author_id ) {
	return sql_e( "DELETE FROM authors_data WHERE id=?", array( $author_id ) );
}

/*
print_acd
	輸出作者欄位資料 (Print author column data)

Parameters:
	$col_name: 欄位名稱
	$col_data: 欄位資料
	$change_line: 換行 (Default: false，不換行)
	$enable_input: 啟用欄位輸入 (Default: 0，關閉)
	$author_id (若啟用enable_input則需要指定此參數)
	$input_col_name: input tag attribute - name (若啟用enable_input則需要指定此參數)

Returns:
	None
*/
function print_acd( $col_name, $col_data, $change_line=0, $enable_input=0, $author_id=0, $input_col_name='' ) {
	?>
	<span class="col_name"><?php echo $col_name; ?></span>
	<span class="col_data">
		<?php 
		if ( $enable_input ) {
			?>
			<input type="text" name="<?php echo 'authors_data['.$author_id.'][\''.$input_col_name.'\']'; ?>" size="32" value="<?php echo dop($col_data); ?>" />
			<?php
		} else {
			echo dop( $col_data );
		}
	?>
	</span>

	<?php
	if ( $change_line ) {
		?>
			<br/>
		<?php
	}
}


/*
print_author_data
	輸出作者資料

Parameters:
	$author_data
	$enable_input：啟用欄位輸入
		0: 關閉 (Default)
		1: 開啟
	$class：自訂class (Default: else_authors)
	$primary_author：主要作者
		0: 否 (Defalut)
		1: 是

Returns:
	None
*/
function print_author_data( $author_data, $enable_input=0, $class="else_authors", $primary_author=0 ) {
	if ( !count('author_data') )
		return false;

	if ( isset($author_data['id']) )
		$author_data = array($author_data);

	?>
	<style type="text/css">
		.table_border_top {
			height: 10px;
			border-top: 1px solid #C5C5C5;
		}

		.adt_col_left {
			display: inline-block;
			width: 50px;
			height: 113px;
			vertical-align: middle;
		}

		.adt_col_right {
			display: inline-block;
		}

		.col_name,
		.col_data {
			display: inline-block;
			padding: 2px;
			vertical-align: top;
		}

		.col_name {
			width: 120px;
			text-align: center;
			border-right: 1px solid #D7D7D7;
		}

		.col_data {
			width: 198px;
			text-align: left;
			overflow: hidden;
			word-break: break-all;
		}

		.col_data > input {
			width: 185px;
		}
	</style>
	<?php
	foreach ($author_data as $key => $value) {
		?>
		<div class="<?php echo $class; ?>">
			<input type="hidden" class="adt-author_id" value="<?php echo $value['id']; ?>" />
			<?php
				if ( $enable_input && !$primary_author  ) {
					?>
					<div class="adt_col_left">
						<input type="checkbox" name="delete_authors[]" value="<?php echo $value['id']; ?>" class="delete_author" />
					</div>
					<?php
				}
			?>
			<div class="adt_col_right">
				<div class="table_border_top"></div>
				<?php
					if ( $enable_input ) {
						print_acd( '中文姓名',		$value['ch_name'],		0, 1, $value['id'], 'ch_name' );
						print_acd( '英文姓名',		$value['en_name'],		1, 1, $value['id'], 'en_name' );
						print_acd( '職稱(中文)',	$value['ch_titles'],	0, 1, $value['id'], 'ch_titles' );
						print_acd( '職稱(英文)',	$value['en_titles'],	1, 1, $value['id'], 'en_titles' );
						print_acd( '服務單位(中文)',$value['ch_serve_unit'],0, 1, $value['id'], 'ch_serve_unit' );
						print_acd( '服務單位(英文)',$value['en_serve_unit'],1, 1, $value['id'], 'en_serve_unit' );
						print_acd( '聯絡電話',		$value['phone'],		0, 1, $value['id'], 'phone' );
						print_acd( 'E-mail',		$value['email'],		1, 1, $value['id'], 'email' );
					} else {
						print_acd( '中文姓名',		$value['ch_name'] );
						print_acd( '英文姓名',		$value['en_name'], 1 );
						print_acd( '職稱(中文)',	$value['ch_titles'] );
						print_acd( '職稱(英文)',	$value['en_titles'], 1 );
						print_acd( '服務單位(中文)',$value['ch_serve_unit'] );
						print_acd( '服務單位(英文)',$value['en_serve_unit'], 1 );
						print_acd( '聯絡電話',		$value['phone'] );
						print_acd( 'E-mail',		$value['email'], 1 );
					}
				?>
			</div>
		</div> <!-- end $class -->
		<?php
	}
}


# 未使用
function get_author_name( $author_id ){
	$result = sql_q("SELECT ch_name FROM authors_data WHERE id=? ", array($author_id));
	$name = "-";
	if( count($result) > 0 )
		$name = $result[0]['ch_name'];
	return $name;
}

