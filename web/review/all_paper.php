﻿<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>所有論文</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/slide.css" rel="stylesheet">
    <link href="../bootstrap/css/half-slide.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../bar/style.css" rel="stylesheet" type="text/css"/>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="../bootstrap/css/buttons.css">
    <link rel="stylesheet" href="../bootstrap/css/review.css">
    <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery.min.js"></script>
    <script src="../bootstrap/js/slide.js"></script>
    <style type="text/css">
      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
      .content{
      display: inline-block;
      width: 500px;
      text-align: left;
      overflow : hidden;
      text-overflow : ellipsis;
      white-space : nowrap;
    }
    .table th{
      text-align: center;
    }
    </style>
</head>
  <body>

  <?php include ("../bar/header.php");?>

      <div class="container-fluid">
        <div class="row-fluid">
          <div class="span2">
            <div class="well sidebar-nav">
              <ul class="nav nav-list">
                  <?php require_once ("../bar/menu_review.php");?>
              </ul>
            </div><!--/.well -->
          </div><!--/span-->
          <div class="span10">
              <div class="hero-unit">
                <?php
                  $auth_data = get_auth_data();
                  $id = $auth_data['id'];
                  $rid = "";
                  # $rid = sql_q("SELECT rid FROM users WHERE id = ?",array($auth_data['id']));
                  # $rid = $rid[0]['rid'];
                  $rid = $auth_data['id'];

                  $sql = "SELECT
                      a.status,
                      a.result,
                      a.id,
                      a.paper_id,
                      b.category,
                      b.language,
                      b.ch_title,
                      b.en_title
                    FROM review_record AS a INNER JOIN papers AS b ON
                      a.paper_id=b.id
                    WHERE
                      a.rid=?
                      AND a.locked='0'
                      AND a.status IN ('1', '2', '6') ";
                  $result = sql_q( $sql, array($rid) );
                  if(count($result) >0 ){
                  echo "<table class='table' >";
                  echo '<thead><tr>
                    <th style="width:135px;">論文id</th>
                    <th>論文標題</th>
                    <th style="width:135px;">狀態</th></tr></thead>';

                  foreach($result as $value){
                    # $stat_arr = array("", "邀稿中", "審稿中", "評閱完成", "拒絕評閱");
                    $stat_arr = array("", "邀稿中", "審稿中", "審稿完成", "拒絕審稿", "等待複審", "審稿中(複審)", "等待複審");
                    echo "<thody><tr>
                        <td>
                          <a href='check_paper.php?pid=".$value['paper_id']."&status=".$value['status']."' >".$value['language']."-".$value['paper_id']."
                        </td>
                        <td><span class='content'>".dop($value['ch_title'])."<br/>".dop($value['en_title'])."</></td>
                        <td style='text-align:center;'>".$stat_arr[ $value['status'] ]."</td>
                      </tr>";
                  }
                  echo "</thody></table>";
                }
                else
                  echo "目前沒有需要審稿的論文";
                ?>
              </div>
          </div>
        </div>
      </div>
      <div class="foot">
            <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
  </body>
</html>