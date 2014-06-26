<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="js.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.js"></script>
	<script type="text/javascript" src="../js/cmxforms.js"></script>
	<title>歷史紀錄</title>
	<link href="../bar/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
		$(document).ready(function(){
			$("#cr").click(function(){
				$(".refuse").show();
			});
		});
	</script>
	<style type="text/css">
		#main{
			color:black;
		}
		.refuse{
			display:none;
			color:red;
		}
		#dis{
			color:blue;
		}
	</style>
</head>
<body>
	<div id="container">
		<?php include ("../bar/header.php");?>
		<?php include ("../bar/menu_top.php");?>
		
		<div id="left">
			<?php require_once ("../bar/menu_review.php");?>
			
		</div><!--left-->
		<div id="main">		
			<?php			
				$id = $_GET['id'];
				$rid = "";
				$frid = sql_q("SELECT rid FROM users WHERE id = ?",array($auth_data['id']));
				foreach ($frid as $frid2) {
									$rid = $frid2['rid']; 
				}	//搜尋rid	
				$category = array("","管理","資訊","設計","幼保、性學、外文","通識教育");
				$language = array("","中文","英文","其他");
				$res = array("","推薦刊登","修改後刊登","修改後再審","不推薦");
				$result = sql_q("SELECT * FROM papers WHERE id = ?",array($id));						
				foreach($result as $pri){
					echo "id : ".$pri['language']."-".$pri['id']."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";					
					$pri['category'] = $category{$pri['category']};
					$pri['language'] = $language{$pri['language']};
					echo "語言 : ".$pri['language']."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
					echo "論文類別 : ".$pri['category']."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
					echo "關鍵字 : ".$pri['keywords']."<br />";
					echo "<p style='border-bottom-style:groove;'></p>";
					echo "中文標題 : ".$pri['ch_title']."<br />";
					echo "英文標題 : ".$pri['en_title']."<br />";
					echo "中文摘要 : <br />";
					echo '<textarea cols="60" rows="10" name="opinion" id="content" disabled>'.$pri['ch_summary'].'</textarea><br />';
					echo "英文摘要 : <br />";
					echo '<textarea cols="60" rows="10" name="opinion" id="content" disabled>'.$pri['en_summary'].'</textarea><br />';
					echo "<br />";
				}
				$result2 = sql_q("SELECT * FROM review_record WHERE paper_id = ? and rid = ?",array($id,$rid));
				echo "<div id='dis'>";
				foreach($result2 as $pri2){
					if($pri2['status'] == 4){
						echo "拒絕原因 : ".$pri2['deny_reason']."<br />";
					}
					else if( $pri2['status'] == 3 ){
						$pri2['result'] = $res{$pri2['result']};				
						echo "評論意見 : ".$pri2['opinion']."<br />";
						echo "評論結果 : ".$pri2['result']."<br />";
					}
				}
				echo "</div>";
				?>
				<input type="button" value="上一頁" onclick="javascript:history.back(1)" class="cssbtn" style='margin-left:50%;'>
		</div><!--main-->
		<div id="foot">
		</div><!--foot-->
	</div><!--container-->
	<?php include ("../bar/end.php");?>
	
</body>
</html>
