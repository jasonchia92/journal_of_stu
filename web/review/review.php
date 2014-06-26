<?php
  require_once('../../loader.php');
  check_login_status();
  
  // Accept review.
  if ( isset($_GET['rev_req']) && ($_GET['rev_req'] == 1) && update_request($_GET['id'], 1) ) {
    // E-mail notification
    $auth_data = get_auth_data();
    $mail_params['pid'] = $_GET['pid'];
    mail_queue_add( $auth_data['account'], 'review_invite_accept', $mail_params);
  }
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>瀏覽維護論文資料::論文管理系統 - 樹德科技大學學報</title>
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

      .tab_container{
        color: black;
        resize:none;
      }
      .detail{
        display: table-cell;
        background: white;
      }
      #tab1,#tab2{
        margin: 0;
        padding: 5%;
      }

      span.col-name {
        display: inline-block;
        vertical-align: top;
        margin: 0;
      }

      span.paper_title textarea{
        display: inline-block;
        text-align: left;
        overflow: hidden;
        word-break: break-all;
        width: 500px;
      }

      #main{
        color:black;
      }
      .refuse,.up_lock,.write_content{
        display:none;
        color:red;
      }
      table#current_file_list th, table#current_file_list td {
        border: 1px solid #555;
      }

      table#current_file_list td.file_link {
        cursor: pointer;
        color: #31E;
      }

      table#current_file_list td.file_link:hover {
        color: #39D;
      }
      .warm{
        color:red;
        display: none;
      }
      .file_link:hover{
        color: blue;
        cursor: pointer;
      }
      span.paper_con{
      display: inline-block;
      width: 520px;
      text-align: left;
      word-break: break-all;
      }
      .label-info{ 
        font-size: 15px;
      }
      .down_ltiitle{
        margin-top: 20px;
      }
      .detail{
        display: inline-block;
        width: 580px;
        text-align: left;
        overflow: hidden;
        word-break: break-all;
      }
      span.col-name{
        color: black;
        display: inline-block;
        vertical-align: top;
        margin: 0;
      }
      span.paper_title{
        margin-right: 5%;
        display: inline-block;
        text-align: left;
        overflow: hidden;
        word-break: break-all;
      }
      .remind{
        color:green;
        font-size: 15px;
        display: inline-block;
        margin-left: 10%;
      }

      input{
        display:inline;
      }

    </style>
   
    <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/cmxforms.js"></script>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/tab_page.js"></script>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/multiple-file-upload/jquery.MultiFile.js"></script>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery-ui.min.js"></script>
    
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
        $(".cr").click(function(){
          $(".refuse").show();
        });

        //設定checkbox 和 disabled
        $('#review_form input:checkbox:[name="bln_uf"]').click(function(){
          if($(".choose_file").is(":checked") )
            $("#up_lock").attr("disabled",false);
          else
            $("#up_lock").attr("disabled",true);
        });

        //檢查是否有輸入資料
        $('#next').click( function(){
          var check_uploadedFiles = ( $('.upload_font').html().indexOf('此篇論文未上傳任何檔案') ) != -1 ? true : false ;
          var formCheck_1 = ( $('#content').val()=="" ) && ( check_uploadedFiles && $('#up_lock').val()=="" );
          var formCheck_2 = false;
          $('.review_result').each(function(){
            if ( $(this).attr('checked') )
              formCheck_2 = true;
          });


          // Check form
          if ( formCheck_1 )
            $('.up_lock').show();
          if ( !formCheck_2 )
            $('.warm').show();
          if ( formCheck_1 || !formCheck_2 )
            return false;

          if ( confirm('確認送出後便無法更改!送出請按確定') ) {
            $('#update_type').val(1);
            $('#review_form').submit();
          } else {
            return false;
          }

          return false;
        });

        $('#save').click(function(){
          $('#review_form').submit();
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
          <form action="review2.php?pid=<?=dop($_GET['pid']);?>" method="post" id="review_form" enctype="multipart/form-data">
           <div class="abgne_tab">
            <ul class="tabs">
              <li><a href="#tab1">論文資料</a></li>
              <li><a href="#tab2">論文審查</a></li>
            </ul>
            <div class="tab_container">
              <div id="tab1" class="tab_content">
                <?php
                  $category = array("","管理","資訊","設計","幼保、性學、外文","通識教育");
                  $language = array("","中文","英文","其他");
                  $status = array("預審中","審稿中","第一階段審稿完成","審稿完畢","預審退件 (不錄取)","等待第二階段審稿","第二階段審稿中","校稿中");   //設定狀態status
                  $id = $_GET['pid'];
                  $result = get_paper_inf( $id );
                  # echo "<table width='560px' border='1'>";
                  $result['category'] = $category{$result['category']};
                  $result['status'] = $status{$result['status']};
                  $result['language'] = $language{$result['language']};

                  ?>
                  <span class="col-name">論文ID：</span><span class="paper_title"><?php echo dop($result['id']); ?></span>
                  <span class="col-name">語言：</span><span class="paper_title"><?php echo dop($result['language']); ?></span>
                  <span class="col-name">類別：</span><span class="paper_title"><?php echo dop($result['category']); ?></span><br/>
                  <div class="title">中文標題：<span class="paper_con"><?php echo dop($result['ch_title']); ?></span></div>
                  <div class="title">英文標題：<span class="paper_con"><?php echo dop($result['en_title']); ?></span></div>
                  <br/>
                  <span class="col-name">中文摘要：</span>
                  <span class="paper_title"><textarea cols="60" rows="10" name="opinion" id="content" class="detail" disabled><?php echo dop($result['ch_summary']); ?></textarea></span><br/><br/>
                  <span class="col-name">英文摘要：</span>
                  <span class="paper_title"><textarea cols="60" rows="10" name="opinion" id="content" class="detail" disabled><?php echo dop($result['en_summary']); ?></textarea></span><br />
                  <?php

                  # Custom template
                  $target_user = get_paper_user_type( get_sys_pid($result['id']) );
                  $auth_data = get_auth_data();

                  # 取得檔案紀錄
                  $sql = "SELECT * FROM pfc_record WHERE
                      paper_id=?
                      AND fa_".$target_user."='1'
                      AND upload_user!=?
                    ORDER BY revision DESC ";
                  $array = array(
                    get_sys_pid($result['id']),
                    $auth_data['id']
                  );

                  $pfc_record = sql_q( $sql, $array );
                  if ( ! count($pfc_record) ) {
                    echo '無論文檔案可顯示.';
                  } else {
                    $link = ROOT_URL.'/web/get_file.php?file=';
                    ?>
                    <div>
                      檔案名稱：<?php echo $pfc_record[0]['file_name']; ?><br/>
                      上傳時間：<?php echo $pfc_record[0]['upload_time']; ?><br/>
                      檔案下載：<span class="file_link" onClick="window.open('<?php echo $link.$pfc_record[0]['file_name']; ?>', '論文檔案', 'width=250,height=250,location=no,toolbar=no');">點此開始下載</span>
                      <br/>
                      <span style="vertical-align:top;">備&emsp;&emsp;註：</span>
                      <span><textarea style="resize:none; margin-top:5px; width:400px; height:100px;" disabled><?php echo $pfc_record[0]['description']; ?></textarea></span>
                    </div>

                    <?php
                  }
                  # End custom template

                  echo "<br/>";
                ?>
              </div>
              <div id="tab2" class="tab_content">
                <span class="label label-info">評閱區</span><br/>
                針對給作者及編譯的資訊中，您如何評量這篇文章?請勾選<br />
                <div>
                  <div class="comment_area" style="display:inline-block; width:250px;">
                  <?php
                    $auth_data = get_auth_data();
                    $record_data = get_review_record( $_GET['id'] );
                    $paper_data = get_paper_inf( $_GET['id'] );
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
                      echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $record_data['score_option']{$i} ].' />&nbsp;'.$chk_str[$i].'<br/>';
                    # change column
                    echo '</div><div class="comment_area2" style="display:inline-block; width:250px;">';
                    # bad option
                    for ( $i=6 ; $i<12 ; $i++ )
                      echo '<input type="checkbox" name="score_option[]" value="'.$i.'" '.$chk_stat[ $record_data['score_option']{$i} ].' />&nbsp;'.$chk_str[$i].'<br/>';
                  ?>
                  </div>
                </div>
                <br /><br />
                <span class="label label-info">評閱意見</span><div class="remind">※檔案上傳和評閱意見至少選擇一項</div><br/>
                <textarea cols="60" rows="10" name="opinion" id="content" class="detail" required><?php echo dop( $record_data['opinion']); ?></textarea><br />
                <div class="down_ltiitle"><input type="checkbox" name="bln_uf" class="choose_file" style="display:inline;" />上傳評閱檔案<br /></div>
                <input name="new_upload_file" type="file" style="display:inline;" id="up_lock" disabled /><br />
                <div class="up_lock">*檔案上傳和評閱意見至少選擇一項</div><br/>
                <div class="upload_font"><span class="label label-info">已上傳的檔案</span><br />
                  <?php
                    # Custom template
                    $target_user = get_paper_user_type( get_sys_pid($result['id']) );
                    $auth_data = get_auth_data();

                    # 取得檔案紀錄
                    $sql = "SELECT
                        a.*
                      FROM pfc_record AS a
                      LEFT JOIN users AS b ON a.upload_user=b.id
                      WHERE
                        a.paper_id=?
                        AND a.fa_".$target_user."='1'
                        AND a.upload_user=?
                      ORDER BY revision DESC ";
                    $array = array(
                      get_sys_pid($result['id']),
                      $auth_data['id']
                    );

                    $pfc_record = sql_q( $sql, $array );
                    if ( ! count($pfc_record) ) {
                      echo '無論文檔案可顯示.';
                    } else {
                      $link = ROOT_URL.'/web/get_file.php?file=';
                      ?>
                      <div>
                        檔案名稱：<?php echo $pfc_record[0]['file_name']; ?><br/>
                        上傳時間：<?php echo $pfc_record[0]['upload_time']; ?><br/>
                        檔案下載：<span class="file_link" onClick="window.open('<?php echo $link.$pfc_record[0]['file_name']; ?>', '論文檔案', 'width=250,height=250,location=no,toolbar=no');">點此開始下載</span>
                        <br/>
                        <span style="vertical-align:top;">備&emsp;&emsp;註：</span>
                        <span><textarea style="resize:none; margin-top:5px; width:400px; height:100px;" disabled><?php echo $pfc_record[0]['description']; ?></textarea></span>
                      </div>
                      <?php
                    }
                  ?>
                </div>
                <br />
                <p><span class="label label-info">※評閱結果 :</span><br/>
                  <?php
                    $res_str = array(
                      1 => "推薦刊登",
                      2 => "修改後刊登",
                      3 => "修改後再審",
                      4 => "不推薦"
                    );

                    for ( $i=1 ; $i<=4 ; $i++ ) {
                      echo '<div class="rev">';
                      echo '<input type="radio" name="result" value="'.$i.'" class="review_result" '.( $record_data['result']==$i ? 'checked' : '' ).' />'.$res_str[$i];
                      echo '</div>';
                    }
                  ?>
                  <div class="warm">*請選擇結果</div>
              <!--    </div>   -->
                </p>
                <br />
                <center>
                  <input type="hidden" name="id" value="<?php echo $record_data['id']; ?>" />
                  <input type="hidden" name="paper_id" value="<?php echo $record_data['paper_id']; ?>" />
                  <input type="hidden" name="file_description" value="" />
                  <input type="hidden" name="update_type" value="0" id="update_type" />
                  <input type="hidden" name="review_status" value="<?php echo $record_data['status']; ?>" />
                  <button type="button" id="save" class="btn" />暫存意見</button>
                  <button type="button" id="next" class="btn" />送出評閱</button>
                 </center>
              </div><!--tab2-->
            </div><!--tab_container-->
          </div><!--abgne_tab-->
          </form>
        </div>
      </div>
  </div>
  </div>
      <div align='center'>
          <?php include ("../bar/end.php");?>
      </div>
  </div><!--/.fluid-container-->
  <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
</body>
</html>