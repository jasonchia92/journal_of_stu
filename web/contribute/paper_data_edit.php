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

		function cancel_edit(){
			window.parent.$('#dialog').html('').dialog('close');
		}

		$(document).ready(function(){
			$('#commentForm').validate();

			$('#new_btn').click(function(){
				$("#author_add").append("<div class=\"else_authors\"><div class=\"adt_col_left\"><button class=\"remove_author_block\">移除<\/button><\/div><div class=\"adt_col_right\"><table><tbody><tr><td class=\"table_border_top\" colspan=\"4\"><\/td><\/tr><tr><td>中文姓名<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_ch_name[]\" size=20 /><\/td><td>英文姓名<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_en_name[]\" size=20 /><\/td><\/tr><tr><td>職稱(中文)<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_ch_titles[]\" size=20 /><\/td><td>職稱(英文)<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_en_titles[]\" size=20 /><\/td><\/tr><tr><td>服務單位(中文)<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_ch_serve_unit[]\" size=20 /><\/td><td>服務單位(英文)<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_en_serve_unit[]\" size=20 /><\/td><\/tr><tr><td>聯絡電話<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_phone[]\" class=\"required number\" size=20 /><\/td><td>E-mail<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_email[]\" class=\"required email\" size=20 /><\/td><\/tr><\/tbody><\/table><\/div><\/div>");
				$('.remove_author_block').bind("click", function(){
					$(this).parent().parent().remove();
				});
			});

			var col_tmp = "";
			$('div.authors_block input').focus(function(){
				col_tmp = $(this).val();
				$(this).blur(function(){
					// 有修改時將其author_id加入#change_authors，送出變更時會update這筆資料 (primary_author與else_authors皆適用)
					if( $(this).val() != col_tmp ) {
						var author_id = $(this).parent().parent().siblings('input').val();
						var change_authors = $("#change_authors").val();
						if ( change_authors.search( author_id+',' ) == -1 )
							$("#change_authors").val( change_authors+author_id+',' );
					}
				});
			});
		});
	</script>
	<!-- 暫時延用 -->
	<link rel="stylesheet" type="text/css" href="../manage/general.css" />
	<link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		body {
			padding: 10px;
			background-color: #DEF;
		}

		.error {
			color: #F00;
			font-size: 12px;
		}

		.paper_block {
			border: 0px dashed #888;
			border-top-width: 2px;
			padding: 10px;
		}

		.paper_block table th, .paper_block table td {
			text-align: center;
			padding: 0px 5px;
		}

		.authors_block table .align_left {
			text-align: left;
		}

		.else_authors {
			margin-bottom: 10px;
		}

		#current_file_list , #current_file_list td {
			border: 1px solid #555;
		}

		.func_disabled {
			color: #888;
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

	<form id="commentForm" class="cmxform" method="post" action="paper_data_edit_2.php" enctype="multipart/form-data">
		<div class="abgne_tab">
			<ul class="tabs">
				<li><a href="#tab1">論文資料</a></li>
				<li><a href="#tab2">作者資料</a></li>
				<li><a href="#tab3">稿件檔案</a></li>
			</ul>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				論文ID：<?php echo $paper_data['id']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				語言：
					<?php
						$option_name = array("中文", "英文", "其他");
						echo $option_name[$paper_data['id']{0}-1];
					?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				類別：
				<?php
					$categoryArr = array("1.管理", "2.資訊", "3.設計", "4.幼保.外文.性學", "5.通識教育");
					echo $categoryArr[ $paper_data['category']-1 ];
				?>
				<br/><br/>
				中文標題：<input type="text" name="ch_title" size="50" class="required" value=<?php echo '"'.dop($paper_data['ch_title']).'"'; ?> /><br/>
				英文標題：<input type="text" name="en_title" size="50" class="required" value=<?php echo '"'.dop($paper_data['en_title']).'"'; ?> /><br/>
				關鍵字&nbsp;&nbsp;&nbsp;&nbsp;：<input type="text" name="keywords" size="50" class="required" value=<?php echo '"'.dop($paper_data['keywords']).'"'; ?> /><br/>
				中文摘要：<br/><textarea cols="60" rows="10" name="ch_summary" class="required"><?php echo dop($paper_data['ch_summary']); ?></textarea><br/>
				英文摘要：<br/><textarea cols="60" rows="10" name="en_summary" class="required"><?php echo dop($paper_data['en_summary']); ?></textarea><br/>
			</div>
			<div id="tab2" class="tab_content authors_block">
				<div><h2>[主要作者]</h2>
					<?php print_author_data( $pa_data, 1, '', 1 ); ?>
				</div>

				<br/>
				<div><h2>[其他作者]</h2>
					<?php print_author_data( $ea_data, 1 ); ?>
					<div id="author_add"></div>
					<input type="button" id="new_btn" value="新增一欄" style="margin-top:5px;" />
				</div>
			</div>
			<div id="tab3" class="tab_content">
				<?php print_pfcr( $paper_data['id'], 3, 1 ); ?>
			</div>
		</div>
			<input type="hidden" name="change_authors" id="change_authors" value="" />
			<input type="hidden" name="paper_id" value="<?php echo $paper_data['id']; ?>" />
			<input type="hidden" name="paper_status" value="<?php echo $paper_data['status']; ?>" />
			<input type="checkbox" name="modify_finish" value="1" />論文資料已修改完成 (修改完成請勾選此選項，未勾選此選項將暫存修改)<br/>
			<div style="text-align:center;">
				<input type=submit value="確認送出" />
				<input type=button value="放棄修改" onclick=" cancel_edit(); " />
			</div>
		</div>
	</form>
	<br/>
	<br/>
</body>
</html>