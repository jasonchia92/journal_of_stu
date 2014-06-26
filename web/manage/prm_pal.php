<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>論文分派狀態::論文分派系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
      body {
        padding-top: 15px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }

      .navbar .nav{
        text-align: center;
        display: table-cell;
        float: none;
      }

		table .category_name {
			width: 120px;
			text-align: center;
		}

		table .category_name a {
			text-decoration: none;
		}

		#summary {
			margin: 0 auto;
			width: 850px;
		}


		#data {
			margin-top: 30px;
			text-align: center;
		}

		#data table th, #data table td,{
			padding: 0px;
		}

		#data .edit_data {
			cursor: pointer;
		}

		#data .edit_data:hover {
			background-color: #FF0;
		}

		#DataViewBlock_creater {
			text-align: left;
			margin-left: 70px;
			padding-bottom: 5px;
		}

		.hero-unit{
            padding-left: 30px;
        }

        .data_block {
        	border-top: 1px solid #828282;
        	padding: 2px 10px;
        	text-align: left;
        }

        .paper-title,
        .paper-title-content {
        	display: inline-block;
        }

        .paper-title {
        	vertical-align: top;
        }

        .paper-title-content {
        	width: 740px;
        	overflow : hidden;
			text-overflow : ellipsis;
			white-space : nowrap;
        }

        .reviewer_mblock {
        	display: inline-block;
        	width: 230px;
        	margin-right: 15px;
        }
    </style>
</head>
	<script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
	<script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function(){
			$('#data .edit_data').click(function(e) {
				e.preventDefault();
				var $this = $(this);
				var link = "./paper_assign.php?" + $(this).children('.assign_value').attr('value');
				$('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=600 height=530 src="' + link + '" ><\/iframe>').dialog({
					title: "論文分派",
					autoOpen: true,
					width: 640,
					height: 584,
					modal: true,
					resizable: false,
					autoResize: false,
				});
			});
		});
	</script>

  <body>
    <div class="container">
        <div align='center'>
            <?php include ("../bar/header.php");?>
        </div>
        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
              <?php include ("../bar/menu_top.php");?>
            </div>
          </div>
        </div><!-- /.navbar -->
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <?php include ("menu_left.php");?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span10">
            <div class="hero-unit">
	            <?php
				$summary = array();
				for( $i=1 ; $i<=5 ; $i++){
					$query_1 = sql_q( "SELECT COUNT(category) FROM papers WHERE category=? AND status IN (0,1,2,5,6) ", array( $i ) );
					$query_2 = sql_q( "SELECT id FROM papers WHERE category=? AND status IN (1,2,5,6) AND (review_status_1='2' OR review_status_2='2' OR review_status_3='2') ", array( $i ) );
					$summary[$i]['count'] = $query_1[0]['COUNT(category)'];
					$summary[$i]['assigned'] = count($query_2);
				}
			?>

			<div id="summary">
				<div style="display:inline-block;">※點選類別名稱進行論文檢視與分派論文.</div>
				<div style="display:inline-block; margin-left:50px; margin-bottom:5px;">
					<form action="" method="get">
						以
						<select class='btn' name="search_condition">
							<option value="0">論文ID</option>
							<option value="1">審稿委員ID</option>
						</select>
						搜尋：
						<input type="text" name="search_str" />
						<button class='btn' type="submit" />送出</button>
					</form>
				</div>
				<div style="display:inline-block; margin-left:50px;"><a style="color:blue;" href="?category=0">顯示所有論文</a></div>
				<table>
					<tbody>
						<tr>
							<td>類別名稱</td>
							<td class="category_name"><a href="?category=1">管理</a></td>
							<td class="category_name"><a href="?category=2">資訊</a></td>
							<td class="category_name"><a href="?category=3">設計</a></td>
							<td class="category_name"><a href="?category=4">幼保.外文.性學</a></td>
							<td class="category_name"><a href="?category=5">通識教育</a></td>
						</tr>
						<tr>
							<td>已分派數/總篇數</td>
							<?php
								foreach( $summary as $key => $value )
									echo "<td class='category_name'>".$summary[$key]['assigned']." / ".$summary[$key]['count']."</td>";
							?>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="data">
			<?php
				if ( isset($_GET['category']) || isset($_GET['search_condition']) ){
					$data = array();
					if ( isset($_GET['category']) ) {
						$sql = "SELECT a.*, b.ch_name, b.ch_serve_unit
							FROM papers AS a LEFT JOIN authors_data AS b
								ON a.id=b.paper_id
							WHERE
								a.status NOT IN ('3', '4', '7', '8')
								AND b.author_type='1' ";
						$array = array();

						# all category
						if ( $_GET['category'] != 0 ) {
							$sql .= "AND a.category=? ";
							$array[] = $_GET['category'];
						}

						# sort
						$sql .= "ORDER BY id DESC ";

						$data = sql_q( $sql, $array );
					} else if ( isset($_GET['search_condition']) ) {
						switch( $_GET['search_condition'] ) {
							case 0:
								# 檢查id
								if ( preg_match('/^[1-2]-\S*$/', $_GET['search_str']) )
									$_GET['search_str'] = substr($_GET['search_str'], 2);
								$sql = "SELECT
										a.*,
										b.ch_name,
										b.ch_serve_unit
									FROM papers AS a LEFT JOIN authors_data AS b ON
										a.id=b.paper_id
									WHERE
										a.id LIKE ?
										AND a.status NOT IN ('3', '4', '7', '8')
										AND b.author_type='1' ";

								$data = sql_q( $sql, array( '%'.$_GET['search_str'].'%' ) );
							break;

							case 1:
								$sql = "SELECT
										a.*,
										b.ch_name,
										b.ch_serve_unit
									FROM papers AS a LEFT JOIN authors_data AS b ON
										a.id=b.paper_id
										AND b.author_type=?
									WHERE (a.reviewer_1=?
										OR a.reviewer_2=?
										OR a.reviewer_3=?
										) AND a.status NOT IN ('3', '4', '7', '8') ";

								$array = array( 1, $_GET['search_str'], $_GET['search_str'], $_GET['search_str'] );

								$data = sql_q( $sql , $array );
							break;
						}
					}

					//get reviewer id for query every author data
					$rid_array = array();
					foreach ($data as $key => $value) {
						//if field data not 0, add in $rid array
						if( strcmp( $value['reviewer_1'], "0") != 0 )
							$rid_array[] = $value['reviewer_1'];
						if( strcmp( $value['reviewer_2'], "0") != 0 )
							$rid_array[] = $value['reviewer_2'];
						if( strcmp( $value['reviewer_3'], "0") != 0 )
							$rid_array[] = $value['reviewer_3'];
					}

					//remove same value
					$rid_array = array_unique( $rid_array );
					global $reviewer_data;
					$reviewer_data = array();
					if ( count($rid_array) > 0 ) {
						$ri_qs = acts( $rid_array, 1 ); //reviewer id query string
						$get_rname = sql_q("SELECT rid, name FROM users WHERE rid IN(".$ri_qs.") ", array() );
						//format $reviewer_data
						foreach ($get_rname as $key => $value)
							$reviewer_data[ $value['rid'] ] = $value['name'];
					}


					function get_reviewer_name( $rid ){
						$name = "無";
						global $reviewer_data;
						if( isset( $reviewer_data[$rid] ) )
							$name = $reviewer_data[$rid];
						return $name;
					}

					/*
					status field:
						0:未分配.
						1:邀請中.
						2:審稿中中.
						3:評閱完成.
						4:拒絕評閱.
						5:必須分配(reviewer_3)
						6:審稿中(二階段)
					*/
					# Set high light color.
					function span_header( $status ) {
						$color = array("#999999", "#3F8670", "#9911AA", "#00AA00", "#FF0000", "#FF00FF", "#9911AA", "#FF00FF");
						return "<span class=\"edit_data\" style=\"color:".$color[$status]."\">";
					}

					$bln = count($data)>0 ? true : false;
					if ($bln) {
						foreach ($data as $key => $value) {
							?>
							<div class="data_block">
								<span>&nbsp;&nbsp;ID&nbsp;&nbsp;：</span><span><?php echo $value['language'].'-'.$value['id']; ?></span><br/>
								<span>
									<span class="paper-title">標題：</span>
									<span class="paper-title-content">
										<span><?php echo dop( $value['ch_title'] ); ?></span><br/>
										<span><?php echo dop( $value['en_title'] ); ?></span>
									</span>
								</span><br/>
								<span class="reviewer_mblock">審稿委員1：<?php echo span_header( $value['review_status_1'] ).dop( get_reviewer_name( $value['reviewer_1'] ) ); ?><input type="hidden" class="assign_value" value="number=1&id=<?php echo $value['id']; ?>" /></span></span>
								<span class="reviewer_mblock">審稿委員2：<?php echo span_header( $value['review_status_2'] ).dop( get_reviewer_name( $value['reviewer_2'] ) ); ?><input type="hidden" class="assign_value" value="number=2&id=<?php echo $value['id']; ?>" /></span></span>
								<span class="reviewer_mblock">審稿委員3：<?php echo span_header( $value['review_status_3'] ).dop( get_reviewer_name( $value['reviewer_3'] ) ); ?><input type="hidden" class="assign_value" value="number=3&id=<?php echo $value['id']; ?>" /></span></span>
							</div>
							<?php
						}
						?>
						<div style="padding-top:20px; border-top: 1px solid #828282;">
							<span style="color:#999999; margin-right:15px;">※未分派</span>
							<span style="color:#3F8670; margin-right:15px;">※邀稿中</span>
							<span style="color:#9911AA; margin-right:15px;">※審稿中</span>
							<span style="color:#00AA00; margin-right:15px;">※審稿完成</span>
							<span style="color:#FF0000; margin-right:15px;">※拒絕評閱</span>
							<span style="color:#FF00FF; margin-right:15px;">※必須分派</span>
						</div>
						<?php
					} else {
						echo "查無資料.";
					}
				}
			?>

			</div>
			<div id="dialog"></div>
		    </div>
		  </div>
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
  </body>
</html>
