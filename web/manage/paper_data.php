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
	<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	<!-- jQuery 1.7.1 -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript">window.jQuery || document.write('<script src="../js/js_spare/jquery.min.js"><\/script>')</script>
	<!-- jQuery validate plugin -->
	<script type="text/javascript" src="../js/jquery.validate.js"></script>
	<script type="text/javascript" src="../js/cmxforms.js"></script>
	<script type="text/javascript" src="../js/tab_page.js"></script>
	<script type="text/javascript">
		function file_open( file_link ){
			window.open("../uploads/"+file_link, "論文檔案", 'width=800,height=600,location=no,toolbar=no,status=no,scrollbars=yes');
		}

		function close_window(){
			window.parent.$('div#dialog').dialog('close');
		}

		$(document).ready(function(){
			$("form#commentForm").validate();

			$("div.authors_block input").click(function(){
				$("#ae_flag").attr('value', 1);
			});

			$('#view1').click(function(){
				$('#option1').slideToggle();
				$('#option2').slideUp();
				$('#option3').slideUp();
			});
			$('#view2').click(function(){
				$('#option2').slideToggle();
				$('#option1').slideUp();
				$('#option3').slideUp();
			});
			$('#view3').click(function(){
				$('#option3').slideToggle();
				$('#option1').slideUp();
				$('#option2').slideUp();
			});
		});
	</script>
	<link rel="stylesheet" type="text/css" href="general.css" />
	<link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		body {
			padding: 10px;
			background-color: #DEF;
		}

		label.error {
			color: #F00;
			font-size: 12px;
		}
		#packup1,#packup2,#packup3{
			cursor: pointer;
			color: blue;
			display: none;
		}
		div.paper_block {
			border: 0px dashed #888;
			border-top-width: 2px;
			padding: 10px;
		}
		#option1,#option2,#option3{
			display: none;
			background: white;
			height: 168px;
			margin-bottom: 400px;
		}
		.paper_summary {
			width: 500px;
			height: 280px;
			border: 2px dotted #555;
			background-color: white;
			padding: 2px;
			overflow: auto;
		}
		body{
			font: 100% 微軟正黑體,細明體,新細明體;
			margin: 0;
			padding: 0;
		}
		#view1,#view2,#view3{
			color: white;
			margin-bottom: 3%;
		}

		.authors_block td {
			width: 280px;
			text-align: left;
		}
		
		table.else_authors {
			margin-bottom: 10px;
		}

		td.table_border_top {
			height: 10px;
			border: 0px dotted #000;
			border-top-width: 2px;
		}
		table#current_file_list th, table#current_file_list td {
			border: 1px solid #555;
		}
		.parallel{
			-moz-column-count:2; 	/* Firefox */
			-webkit-column-count:2; /* Safari 和 Chrome */
			column-count:2;
			width:600px;
			margin-left: 10%;
		}
		#author{
			margin-left: 10%;
		}
		#tab2 h2{
			margin-left: 5%;
		}
		.txtarea{
			display: inline-block;
			width: 560px;
			text-align: left;
			overflow: hidden;
			word-break: break-all;
		}
		.information{
			margin: 20px;
		}
		.title{
			margin-left:75px;
			text-indent:-75px;
			color:green;
		}
		button#view2 ,button#view3 {
			margin-top: -45px;
		}

		.information > span {
			display: inline-block;
			padding: 2px;
			margin-right: 20px;
			width: 300px;
		}

		span.paper-data-summary {
			width: 600px;
			margin-bottom: 10px;
		}

		.paper-data-summary > span {
			display: inline-block;
		}

		.paper-data-summary .col-name {
			vertical-align: top;
		}

		.paper-data-summary textarea {
			width: 510px;
			height: 200px;
			resize: none;
		}

		span.col-name {
			display: inline-block;
			width: 80px;
			vertical-align: top;
			margin: 0;
		}

		span.paper_title,
		span.paper-keywords {
			display: inline-block;
			width: 520px;
			text-align: left;
			overflow: hidden;
			word-break: break-all;
		}

	</style>
</head>
<body>
	<?php
		$paper_data = get_paper_inf( $_GET['id'] );
		$pa_data = get_paper_authors_data( $_GET['id'], 1 );
		$ea_data = get_paper_authors_data( $_GET['id'], 0 );
		//過濾多餘維度
		if( count($pa_data) == 0 ){
			$pa_data = array(
				'ch_name' => "",
				'en_name' => "",
				'ch_serve_unit' => "",
				'en_serve_unit' => "",
				'ch_titles' => "",
				'en_titles' => "",
				'email' => "",
				'phone' => ""
			);
		}else{
			$pa_data = $pa_data[0];
		}
	?>
	<div class="abgne_tab">
		<ul class="tabs">
			<li><a href="#tab1">論文資料</a></li>
			<li><a href="#tab2">作者資料</a></li>
			<li><a href="#tab3">審查稿件</a></li>
			<li><a href="#tab4">上傳稿件</a></li>
		</ul>
		<div class="tab_container">
		<div id="tab1" class="tab_content">	

			<?php
				$lang = array("中文", "英文", "其他");
				$option_name = array("管理", "資訊", "設計", "幼保.外文.性學", "通識教育");
			?>
			<div class='information'>
				<span>論文ID：<?php echo dop($paper_data['id']); ?></span>
				<span>語言：<?php echo $lang[$paper_data['id']{0}-1]; ?></span><br/>
				<span>類別：<?php echo $option_name[ $paper_data['category'] ]; ?></span>
				<span>提交日期：<?php echo dop($paper_data['submit_date']); ?></span><br/>
				<span class="col-name">關鍵字：</span><span class="paper-keywords"><?php echo dop($paper_data['keywords']); ?></span><br/>
				<span class="col-name">中文標題：</span><span class="paper_title"><?php echo dop($paper_data['ch_title']); ?></span><br/>
				<span class="col-name">英文標題：</span><span class="paper_title"><?php echo dop($paper_data['en_title']); ?></span><br/>
				<span class="paper-data-summary">
					<span class="col-name">中文摘要：</span>
					<span><textarea disabled><?php echo dop($paper_data['ch_summary']); ?></textarea></span>
				</span><br/>
				<span class="paper-data-summary">
					<span class="col-name">英文摘要：</span>
					<span><textarea disabled><?php echo dop($paper_data['en_summary']); ?></textarea></span><br/>
				</span><br/>
			</div>

		</div>
		<div id="tab2" class="tab_content authors_block">
			<div><h3>[主要作者]</h3>
				<?php print_author_data( $pa_data, 0, '', 1 ); ?>
			</div>
			<div><h3>[其他作者]</h3>
				<?php
					if ( count($ea_data) > 0 )
						print_author_data( $ea_data );
					else
						echo "此篇論文無其他作者.";
				?>
			</div>
		</div>
		<div id="tab3" class="tab_content">
			<?php	
				$result = sql_q("SELECT * FROM review_record  WHERE paper_id = ?",array( $_GET['id']) );
				$status = array("","邀稿中","審稿中","評閱完成","拒絕審稿");
				$res = array("","推薦刊登","修改後刊登","修改後再審","不推薦");
				$chk_stat = array( '', 'checked' );
						$chk_str = array(
							"內容充實，見解創新",
							"研究方法恰當，推理嚴謹",
							"所獲結論具學術或實用價值",
							"觀點正確，有學理依據",
							"取材豐富，組織嚴謹",
							"研究能力佳",
							"無特殊創見",
							"學術或研究價值不高",
							"析論欠深入",
							"內容不完整",
							"研究方法及理論基礎均若",
							"有抄襲之嫌"
						);
				$review_number = 1;
				foreach ($result as $value) {
					$value['status'] = $status{$value['status']};
					$value['result'] = $res{$value['result']};

						echo '<button id="view'.$review_number.'" class="btn btn-large btn-block btn btn-info" type="button">審稿委員'.$review_number.'</button>';
						echo "<div id='option".$review_number."'>";
						echo '<table class="table table-striped">';
						echo '<thead><td>委員</td><td>稿件狀態</td><td>審稿結果</td></thead>';
						echo '<td>'.dop($value['rid']).'</td>'.'<td>'.dop($value['status']).'</td>'
						.'<td>'.dop($value['result']).'</td></table>';					
							$option = $value['opinion'];
							$str = $value['score_option'];					
						echo "評閱結果<br /><div class='parallel'>";
						for ( $i=0 ; $i<6 ; $i++ )
							echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $str{$i} ].' disabled />&nbsp;'.$chk_str[$i].'<br/>';
						echo '<br />';
						for ( $i=6 ; $i<12 ; $i++ )
							echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $str{$i} ].' disabled />&nbsp;'.$chk_str[$i].'<br/>';
						echo "</div>";
						echo "評閱意見<br />";
						echo '<textarea cols="60" rows="8" class="txtarea" name="opinion" id="content" disabled>'.dop($option).'</textarea><br />';
						echo "</div>";
						echo "<br />";					
				
					echo "<br />";
					$review_number++;
				}
				if($review_number == 3)
					echo "審稿委員3‧‧‧‧‧‧無分派資料<br />";
				else if($review_number == 2){
					echo "審稿委員2‧‧‧‧‧‧無分派資料<br />";
					echo "審稿委員3‧‧‧‧‧‧無分派資料<br />";
				}
				else if($review_number == 1){
					echo "審稿委員1‧‧‧‧‧‧無分派資料<br />";
					echo "審稿委員2‧‧‧‧‧‧無分派資料<br />";
					echo "審稿委員3‧‧‧‧‧‧無分派資料<br />";
				}
			/*		
				$result = sql_q("SELECT * FROM papers WHERE id = ?",array( $_GET['id']) );
				$status = array("","邀稿中","審稿中","評閱完成","拒絕審稿");
				$res = array("","推薦刊登","修改後刊登","修改後再審","不推薦");
				$chk_stat = array( '', 'checked' );
						$chk_str = array(
							"內容充實，見解創新",
							"研究方法恰當，推理嚴謹",
							"所獲結論具學術或實用價值",
							"觀點正確，有學理依據",
							"取材豐富，組織嚴謹",
							"研究能力佳",
							"無特殊創見",
							"學術或研究價值不高",
							"析論欠深入",
							"內容不完整",
							"研究方法及理論基礎均若",
							"有抄襲之嫌"
						);
				foreach ($result as $value) {
					$value['review_status_1'] = $status{$value['review_status_1']};
					$value['review_status_2'] = $status{$value['review_status_2']};
					$value['review_status_3'] = $status{$value['review_status_3']};
					$value['review_result_1'] = $res{$value['review_result_1']};
					$value['review_result_2'] = $res{$value['review_result_2']};
					$value['review_result_3'] = $res{$value['review_result_3']};
					$value['final_result'] = $status{$value['final_result']};
					if($value['reviewer_1'] == 0){
						$value['reviewer_1'] = "";
					}
					if($value['reviewer_2'] == 0){
						$value['reviewer_2'] = "";
					}
					if($value['reviewer_3'] == 0){
						$value['reviewer_3'] = "";
					}
					if($value['reviewer_1'] != 0){
						$score = sql_q("SELECT opinion,score_option FROM review_record WHERE paper_id=? and rid=?",array($_GET['id'],$value['reviewer_1']));
						echo '<button id="view1" class="btn btn-large btn-block btn btn-info" type="button">審稿委員1</button>';
						echo "<div id='option1'>";
						echo '<table class="table table-striped">';
						echo '<thead><td>委員</td><td>稿件狀態</td><td>審稿結果</td></thead>';
						echo '<td>'.dop($value['reviewer_1']).'</td>'.'<td>'.dop($value['review_status_1']).'</td>'
						.'<td>'.dop($value['review_result_1']).'</td></table>';

						foreach ($score as $value2) {
							$option = $value2['opinion'];
							$str = $value2['score_option'];
						}
						echo "評閱結果<br /><div class='parallel'>";
						for ( $i=0 ; $i<6 ; $i++ )
							echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $str{$i} ].' disabled />&nbsp;'.$chk_str[$i].'<br/>';
						echo '<br />';
						for ( $i=6 ; $i<12 ; $i++ )
							echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $str{$i} ].' disabled />&nbsp;'.$chk_str[$i].'<br/>';
						echo "</div>";
						echo "評閱意見<br />";
						echo '<textarea cols="60" rows="8" class="txtarea" name="opinion" id="content" disabled>'.dop($option).'</textarea><br />';
						echo "</div>";
						echo "<br />";
					}
					else{
						echo "審稿委員1‧‧‧‧‧‧無分派資料<br />";
					}
					if($value['reviewer_2'] != 0){
						$score = sql_q("SELECT opinion,score_option FROM review_record WHERE paper_id=? and rid=?",array($_GET['id'],$value['reviewer_2']));
						echo '<button id="view2" class="btn btn-large btn-block btn btn-info" type="button">審稿委員2</button>';
						echo "<div id='option2'>";
						echo '<table class="table table-striped">';
						echo '<thead><td>委員</td><td>稿件狀態</td><td>審稿結果</td></thead>';
						echo '<td>'.dop($value['reviewer_2']).'</td>'.'<td>'.dop($value['review_status_2']).'</td>'
						.'<td>'.dop($value['review_result_2']).'</td></table>';

						foreach ($score as $value2) {
							$option = $value2['opinion'];
							$str = $value2['score_option'];
						}
						echo "評閱結果<br /><div class='parallel'>";
						for ( $i=0 ; $i<6 ; $i++ )
							echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $str{$i} ].' />&nbsp;'.$chk_str[$i].'<br/>';
						echo '<br />';
						for ( $i=6 ; $i<12 ; $i++ )
							echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $str{$i} ].' />&nbsp;'.$chk_str[$i].'<br/>';
						echo "</div>";
						echo "評閱意見<br />";
						echo '<textarea cols="60" rows="8" class="txtarea" name="opinion" id="content" disabled>'.dop($option).'</textarea><br />';
						echo "</div>";
						echo "<br />";
					}
					else{
						echo "審稿委員2‧‧‧‧‧‧無分派資料<br />";
					}
					if($value['reviewer_3'] != 0){
						$score = sql_q("SELECT opinion,score_option FROM review_record WHERE paper_id=? and rid=?",array($_GET['id'],$value['reviewer_2']));
						echo "<div id='option3'>";
						echo '<table class="table table-striped">';
						echo '<thead><td>委員</td><td>稿件狀態</td><td>審稿結果</td></thead>';
						echo '<td>'.dop($value['reviewer_3']).'</td>'.'<td>'.dop($value['review_status_3']).'</td>'
						.'<td>'.dop($value['review_result_3']).'</td></table>';

						foreach ($score as $value2) {
							$option = $value2['opinion'];
							$str = $value2['score_option'];
						}
						echo "評閱結果<br /><div class='parallel'>";
						for ( $i=0 ; $i<6 ; $i++ )
							echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $str{$i} ].' />&nbsp;'.$chk_str[$i].'<br/>';
						echo '<br />';
						for ( $i=6 ; $i<12 ; $i++ )
							echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $str{$i} ].' />&nbsp;'.$chk_str[$i].'<br/>';
						echo "</div>";
						echo "評閱意見<br />";
						echo '<textarea cols="60" rows="8" class="txtarea" name="opinion" id="content" disabled>'.dop($option).'</textarea><br />';
						echo "</div>";
						echo "<br />";
					}
					else{
						echo "審稿委員3‧‧‧‧‧‧無分派資料<br />";
					}
					echo "<br />";
				}			
			*/
			?>
		</div>
		<div id="tab4" class="tab_content">
			現有檔案：<br/>
			<?php
				print_pfcr( $paper_data['id'], 4 );
			?>
		</div>
	</div>
		<div style=" text-align:center; ">
			<?php
				if ( $paper_data['status']!=8 ) {
					?>
					<input type="button" value="修改論文" class="btn btn-primary" onclick="<?php echo "window.location.href='paper_data_edit.php?pid=".substr($paper_data['id'], 2)."'"; ?>;"/>
					<?php
				}
			?>
			<input type="button" value="關閉視窗" class="btn btn-primary" onclick=" close_window(); " />
		</div>
	</div>
	</form>
</body>
</html>