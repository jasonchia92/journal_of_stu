<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>所有論文::線上審稿系統 - 樹德科技大學學報</title>
  <!-- Le styles -->
  <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
  <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
  <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
  <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 
  <link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />
  <style type="text/css">
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

    #main{
      color:black;
    }
    .refuse{
      display:none;
      color:red;
    }
    .invite{
      color:red;
    }
    .category_file span{
      text-align: left;
      display: inline;
      margin-right: 30px;
      margin-left: 3%;
    }
    .font_right{
      text-align: left;
      margin-left: 3%;
    }

    .btn_right{
      margin-left: 42%;
    }
    .parallel{
      -moz-column-count:2;  /* Firefox */
      -webkit-column-count:2; /* Safari 和 Chrome */
      column-count:2;
      width:600px;
      margin-left: 5%;
    }
    #tab1{
      padding: 40px;
    }
    .title{
      color:green;
      display: inline-block;
    }
    span.content{
      display: inline-block;
      width: 580px;
      text-align: left;
      overflow: hidden;
      word-break: break-all;
    }
    span.col-name{
      color: black;
      display: inline-block;
      width: 90px;
      vertical-align: top;
      margin: 0;
      text-align: center;
    }
    span.paper_title{
      margin-right: 5%;
      display: inline-block;
      text-align: left;
      overflow: hidden;
      word-break: break-all;
    }
    .pd-summary {
      width: 640px;
      height: 200px;
      padding: 4px 6px;
      resize : none;
      margin-bottom: 15px;
    }
    .pdTable_summaryData{
      display: inline-block;
      text-align: left;
      overflow: hidden;
      word-break: break-all;
      margin-left: 3%;
      width: 600px;
      height: 200px;
    }

    input{
      display: inline;
    }
  </style>
  <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo JS_URL; ?>/jquery.validate.js"></script>
  <script type="text/javascript" src="<?php echo JS_URL; ?>/cmxforms.js"></script>
  <script type="text/javascript" src="<?php echo JS_URL; ?>/tab_page.js"></script>

  <script type="text/javascript" src="../bootstrap/theme/js/jQuery.appear.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/superfish.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/jquery.flexslider-min.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/jquery.easing.1.3.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/easypiechart.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/canvas.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/niceScroll.js"></script>

  <script src="../bootstrap/theme/js/jquery.lazyload.js"></script>
  <script src="../bootstrap/theme/js/least.min.js"></script>
  
  <script type="text/javascript">
    $(document).ready(function(){
      $("#cr").click(function(){
        $(".refuse").show();
      });
    });
  </script>
</head>
   <body class="home">
     <div id="container">
        <div id="wrapper">
            <?php include ("../bar/header_review.php");?> 
            <div class="span9">
              <div class="hero-unit">
                <?php
                  $paper_data = get_paper_inf( $_GET['pid'] );
                  $_GET['status'];
                  $id = $_GET['pid'];
                  $category = array("","管理","資訊","設計","幼保、性學、外文","通識教育");
                  $language = array("","中文","英文","其他");
                  $auth_data = get_auth_data();
                  $sql = "SELECT
                      a.*,
                      b.language,
                      b.category,
                      b.ch_title,
                      b.en_title,
                      b.keywords,
                      b.ch_summary,
                      b.en_summary,
                      b.status AS paper_status
                    FROM review_record AS a LEFT JOIN papers as b ON
                      a.paper_id=b.id
                    WHERE
                      a.paper_id=? AND
                      a.rid=? ";

                  $record_data = sql_q( $sql ,array( $id, $auth_data['id'] ) );
                  $record_data = $record_data[0];
                  echo '<div class="abgne_tab">';
                  echo '<ul class="tabs">
                      <li><a href="#tab1">論文資料</a></li>';
                    if( $_GET['status']==3 ){
                      echo '<li><a href="#tab2">審稿紀錄</a></li>
                      <li><a href="#tab3">上傳檔案</a></li>';
                    }
                    echo '</ul><div class="tab_container">';
                      echo '<div id="tab1" class="tab_content">';
                  echo '
                    <span class="col-name">論文編號：</span>
                    <span class="paper_title">'.$record_data['language'].'-'.$record_data['paper_id'].'</span>';
                    $record_data['category'] = $category{$record_data['category']};
                    $record_data['language'] = $language{$record_data['language']};
                  echo '
                    <span class="col-name">語言：</span><span class="paper_title">'.$record_data['language'].'</span>
                    <span class="col-name">論文類別：</span><span class="paper_title">'.$record_data['category'].'</span><br/><br/>
                    <span class="col-name">關鍵字 ：</span><span class="content">'.$record_data['keywords'].'</span><br/><br/>       
                    <span class="col-name">中文標題：</span><span class="content">'.$record_data['ch_title'].'</span><br/><br/>
                    <span class="col-name">英文標題：</span><span class="content">'.$record_data['en_title'].'</span><br/><br/>';
                    
                  switch ( $record_data['status'] ) {
                    case 1:
                      echo "<center><div class='invite'>新的審稿邀請</div><br/>";
                      echo "<button type='button' class='btn' onClick=self.location.href='review.php?pid=".$record_data['paper_id']."&id=".$record_data['id']."&rev_req=1' />接受審稿</button>";
                      echo "&nbsp<button type='button' class='btn' id=cr />拒絕審稿</button></center><br/>";
                      echo "<div class=refuse>拒絕理由：";
                      echo "<form action='refuse_reason.php?pid=".dop($_GET['pid'])."' method='post' >";
                      echo "<textarea class='pd-summary' cols='60' rows='10' name='reason' ></textarea>";
                      echo "<input type=\"hidden\" name=\"id\" value=\"".$record_data['id']."\"><br/>";
                      echo "<center><button type='submit' class='btn'  onclick=return(confirm('拒絕後便無法更改，請再次確認')) >送出</button>";
                      echo "</form>";
                      echo "</div>";
                      echo '</div>';//tab1
                    break;

                    case 2:
                    case 6:
                      echo '<button type="button" class="btn btn_right" onClick="self.location.href=\'review.php?pid='.$record_data['paper_id'].'&id='.$record_data['id'].'\'" />進入審稿</button>';
                      echo '</div>';//tab1
                    break;

                    case 3:
                      echo '</div>';//tab1
                      echo '<div id="tab2" class="tab_content">';
                      $reviewResult_arr = array(
                        '審查中',
                        '推薦刊登',
                        '修改後刊登',
                        '修改後再審',
                        '不推薦'
                      );
                      ?>
                      <div class="font_right" style="color:#006BDC;">審查結果：
                        <?php echo $reviewResult_arr[ $record_data['result'] ]; ?><br/>
                      </div><br/>
                      <div class="font_right">評閱選項：<br>
                        <div class="parallel">
                          <?php
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
                            # good option
                            for ( $i=0 ; $i<6 ; $i++ )
                              echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $record_data['score_option']{$i} ].' class="score_option" disabled />&nbsp;'.$chk_str[$i].'<br/>';
                          ?>
                           <br/>
                          <?php
                            # bad option
                            for ( $i=6 ; $i<12 ; $i++ )
                              echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $record_data['score_option']{$i} ].' class="score_option" disabled />&nbsp;'.$chk_str[$i].'<br/>';
                          //  echo '</div>';//tab2
                          ?>
                         </div><br/>
                      </div>
                      <div class="font_right" >評閱意見：</div>
                      <span><textarea class="pdTable_summaryData" disabled><?php echo dop( $record_data['opinion']); ?></textarea></span><br/>
                </div>
                  <?php
                    echo '<div id="tab3" class="tab_content">';
                    print_pfcr( $record_data['paper_id'], 1);
                    echo '</div>';//tab3
                    break;
                    case 4:
                    break;

                    default:
                    break;
                  }
                    # echo '</div>';//tab_container
                    # echo '</div>';//abgne_tab
                  ?>
                </div>
            </div>
         </div><!--/.fluid-container-->
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
  </body>
</html>