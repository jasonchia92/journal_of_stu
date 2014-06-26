<!-- Paper Review Manage - detialed paper master schedule -->
<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>詳細論文總表::論文審查管理系統 - 樹德科技大學學報</title>
	<script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
	<link href="../bar/style.css" rel="stylesheet" type="text/css" />
	<link href="general.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		#data {
			margin-top: 20px;
		}

		#data th, #data td {
			border: 1px solid #555;
		}

		.button {
			font:bold 16px Century Gothic, sans-serif;
			font-style:normal;
			color:#eeeeee;
			background:#9ea86b;
			border:1px solid #d5dbb6;
			text-shadow:0px -1px 1px #084d0c;
			box-shadow:0px 0px 3px #252b01;
			-moz-box-shadow:0px 0px 7px #252b01;
			-webkit-box-shadow:0px 0px 7px #252b01;
			border-radius:10px;
			-moz-border-radius:5px;
			-webkit-border-radius:20px;
			width:60px;
			padding:1px 0px;
			margin: 3px 0px;
			cursor:pointer;
		}

		.button:hover {
			background: #d5dbb6;
		}

		.button:active {
			cursor:pointer;
			position:relative;
			top:2px;
		}
	</style>
</head>
<body class="thrColLiqHdr">
	<div id="container">
		<?php include ("../bar/header.php");?>
		<?php include ("../bar/menu_top.php");?>
		
		<div id="left">
			<?php include ("menu_left.php");?>
		</div><!--left-->
		<div id="main">
			<div id="category_select">
				請選擇您要查詢的類別
				<form action="" method="get">
					<input type="checkbox" name="category[]" value="1" />&nbsp;管理&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="category[]" value="2" />&nbsp;資訊&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="category[]" value="3" />&nbsp;設計&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="category[]" value="4" />&nbsp;幼保.性學.外文&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="category[]" value="5" />&nbsp;通識教育&nbsp;&nbsp;&nbsp;&nbsp;
					<br /><br />
					<input type="submit" value="查詢" class="cssbtn" />
					<input type="reset" value="重設"  class="cssbtn" />
				</form>
			</div>
			<div id="data">
				<?php
					if ( isset($_GET['category']) ) {
						//array date convert to string
						$cago_str = acts($_GET['category'], 1);
						$data = sql_q("SELECT * FROM papers WHERE category IN(".$cago_str.") ORDER BY category", array());

						if ( count($data) > 0 ) {
							$temp = $data;
							$data = array();
							global $reviewer_data;
							$reviewer_data = array();

							function get_rid( $rid ) {
								global $reviewer_data;
								if ( $rid != 0 )
									$reviewer_data[$rid] = $rid;
							}
							
							foreach ($temp as $key => $value) {
								$data[ $value['id'] ] = $value;
								get_rid( $value['reviewer_1'] );
								get_rid( $value['reviewer_2'] );
								get_rid( $value['reviewer_3'] );
							}



							$ri_qs = acts($reviewer_data, 1);
							$rev_query = array();
							if ( count($reviewer_data) > 0 )
								$rev_query = sql_q("SELECT rid, name FROM users WHERE rid IN (".$ri_qs.")", array());

							foreach ($rev_query as $key => $value)
								$reviewer_data[ $value['rid'] ] = $value['name'];

							$paper_id_array = array();
							foreach ($data as $key => $value)
								$paper_id_array[] = $value['id'];
							//paper id query string
							$pi_qs = acts($paper_id_array, 1);

							$paper_data = sql_q("SELECT id, language, ch_title, primary_author FROM papers WHERE id IN(".$pi_qs.")", array());
							$author_id_array = array();
							foreach ($paper_data as $key => $value) {
								$paper_id = $value['id'];
								$data[$paper_id]['paper_id'] = $value['language']."-".$paper_id;
								$data[$paper_id]['ch_title'] = $value['ch_title'];
								$data[$paper_id]['primary_author'] = $value['primary_author'];
								$author_id_array[] = $value['primary_author'];
							}

							//authors data query string
							$ad_qs = acts($author_id_array, 1);
							$temp = sql_q("SELECT id, ch_name FROM authors_data WHERE id IN(".$ad_qs.")", array());
							global $authors_data;
							$authors_data = array();
							foreach ($temp as $key => $value)
								$authors_data[ $value['id'] ] = $value['ch_name'];

							echo "
								<table>
									<thead><tr>
										<th>論文編號</th>
										<th>審稿委員1</th>
										<th>推薦與否</th>
										<th>審稿委員2</th>
										<th>推薦與否</th>
										<th>審稿委員3</th>
										<th>推薦與否</th>
										<th>論文名稱</th>
										<th>主要作者</th>
										<th>論文分類</th>
									</tr></thead>
									<tbody>
							";

							function get_name( $id, $type ) {
								$name = " ";
								global $authors_data;
								global $reviewer_data;
								if ( $id != 0 ) {
									switch( $type ){
										case 'author':
											if ( isset($authors_data[$id]) )
												$name = $authors_data[$id];
										break;

										case 'reviewer':
											if ( isset($reviewer_data[$id]) )
												$name = $reviewer_data[$id];
										break;
									}
									$name = dop($name);
								}
								return $name;
							}

							$cago_arr = array("未分類", "管理", "資訊", "設計", "幼保.性學.外文", "通識教育");
							foreach ($data as $key => $value) {
								echo "
									<tr>
										<td>".$value['paper_id']."</td>
										<td>".get_name( $value['reviewer_1'], 'reviewer' )."</td>
										<td>".$value['review_result_1']."</td>
										<td>".get_name( $value['reviewer_2'], 'reviewer' )."</td>
										<td>".$value['review_result_2']."</td>
										<td>".get_name( $value['reviewer_3'], 'reviewer' )."</td>
										<td>".$value['review_result_3']."</td>
										<td>".dop( $value['ch_title'] )."</td>
										<td>".get_name( $value['primary_author'], 'author' )."</td>
										<td>".$cago_arr[ $value['category'] ]."</td>
									</tr>
								";
							}
							echo "</tbody></table>";
						} else {
							echo "查無資料";
						}
					}
				?>
			</div>
		</div><!--main-->
		<div id="foot">
		</div><!--foot-->
	</div><!--container-->
	<?php include ("../bar/end.php");?>
	
</body>
</html>
