<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>所有論文</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 

    <script type="text/javascript" src="../bootstrap/theme/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jQuery.appear.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/superfish.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/easypiechart.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/canvas.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/niceScroll.js"></script>

    <script src="../bootstrap/theme/js/jquery.lazyload.js"></script>
    <script src="../bootstrap/theme/js/least.min.js"></script>
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

    .table{
      width: 90%;
      margin: 0 auto;
    }
    td, th {
        padding: 15px;
        border: 1px solid #ccc;
        text-align: center;
        color: #000;
        }

    th {
      background: lightblue;
      border-color: white;
    }
    </style>
</head>
  <body class="home">
     <div id="container">
        <div id="wrapper">
            <?php include ("../bar/header_review.php");?> 
            <div class="span10">
                <div class="hero-unit" style="text-align:center;">
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
                      echo "<tbody><tr>
                          <td>
                            <a href='check_paper.php?pid=".$value['paper_id']."&status=".$value['status']."' >".$value['language']."-".$value['paper_id']."
                          </td>
                          <td><span class='content'>".dop($value['ch_title'])."<br/>".dop($value['en_title'])."</></td>
                          <td style='text-align:center;'>".$stat_arr[ $value['status'] ]."</td>
                        </tr>";
                    }
                    echo "</tbody></table>";
                  }
                  else
                    echo "目前沒有需要審稿的論文";
                  ?>
                </div>
            </div>
          </div>
      <div class="foot">
            <?php include ("../bar/end.php");?>
      </div>
    </div><!--/.fluid-container-->
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
  </body>
</html>
