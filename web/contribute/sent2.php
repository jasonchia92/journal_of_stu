<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>查詢進度</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet">   

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
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

    .content{
      display: inline-block;
      width: 800px;
      text-align: left;
      overflow : hidden;
      text-overflow : ellipsis;
      white-space : nowrap;
    }
    .hightlight{
      color: red;
    }
    span.col-name{
      display: inline-block;
      width: 100px;
      vertical-align: top;
      margin: 0;
      text-align: center;
      font-size: 15px;
    }
    span.final_view{
      color:black;
      display: inline-block;
      text-align: left;
      overflow: hidden;
      word-break: break-all;
      width:650px;
      font-size: 15px;
    }
    .hero-unit{
      text-align: center;
    }
    h2{
      font-size: 45px;
    }
    </style>
</head>
  <body class="home">
          <div id="container">
                <div id="wrapper">
                 <?php include ("../bar/header_contribute.php");?> 
        <div class="span9">
            <div class="hero-unit">
                <h2>提交稿件</h2>
                  <?php
                    // echo "<div class='hightlight'>提交成功論文ID為 ".dop($_POST['language'])."-".dop($paper_id)."</div><br/>";
                    $paper_id = get_max_paper_id();
                    // echo "論文ID為 ".dop($_POST['language'])."-".dop($paper_id)."</div><br/>";
                    echo "<div class='hightlight'>提交成功，論文ID為 ".dop($_POST['language'])."-".$paper_id."</div><br/>";
                    
                    $auth_data = get_auth_data();
                    $upload_id = $paper_id;
                    $submit_user = $auth_data['id'];
                    $language = "".$_POST['language'];
                    $ch_title = "".$_POST['ch_title'];
                    $en_title = "".$_POST['en_title'];
                    $pa_id = add_author_data(
                      $paper_id,1,
                      $_POST['pa_ch_name'],
                      $_POST['pa_en_name'],
                      $_POST['pa_ch_serve_unit'],
                      $_POST['pa_en_serve_unit'],
                      $_POST['pa_ch_titles'],
                      $_POST['pa_en_titles'],
                      $_POST['pa_email'],
                      $_POST['pa_phone']
                    );

                    //跑迴圈新增其他作者資料，並收集其他作者的id
                    $ea_id = array();
                    #無資料時不進行動作
                    if( isset($_POST['new_ch_name']) ){
                      # 跑迴圈寫入新加入的作者資料
                      for( $i=0 ; $i<count($_POST['new_ch_name']) ; $i++ ){
                        $bln = add_author_data(
                          $paper_id,
                          0, # 其他作者
                          $_POST['new_ch_name'][$i],
                          $_POST['new_en_name'][$i],
                          $_POST['new_ch_serve_unit'][$i],
                          $_POST['new_en_serve_unit'][$i],
                          $_POST['new_ch_titles'][$i],
                          $_POST['new_en_titles'][$i],
                          $_POST['new_email'][$i],
                          $_POST['new_phone'][$i]
                        );

                        if ( false === $bln ) throw new Exception('Create authors data has been error at web/contribute/sent2.php line 118.');
                        sleep(0.5);
                      }
                    }
                      
                    #把id陣列轉成SQL查詢用字串
                    $ea_id = acts( $ea_id, 0 );

                    $ch_summary = "".$_POST['ch_summary'];
                    $en_summary = "".$_POST['en_summary'];
                    $keywords = "".$_POST['keywords'];
                    $category = "".$_POST['category'];
                    $paper_file_link="";

                    #寫入論文資料
                    $submission_result = paper_add( $language ,$ch_title, $en_title, $ch_summary, $en_summary, $keywords, $category, $submit_user );
                    

                    #無新增檔案則不呼叫multi_upload()   檔案上傳
                    // if( strcmp($_FILES['paper_files']['name'][0], "") != 0 )
                    // multi_upload( $paper_id, "paper_files", "" );
                    $upload_file = $_POST['language']."-".$paper_id;
                    if( strlen($_FILES['new_upload_file']['name']) > 0 ) {
                      multi_upload(
                        $upload_file,
                        'new_upload_file',
                        0,
                        "提交新稿"
                      );
                    } else {
                      echo 'Not have file uploaded.';
                      return false;
                    }

                    sleep(2);
                    #取得該論文的資料
                    $get_paper_data = get_paper_inf($paper_id);
                    
                    echo "<span class='col-name'>語言 :</span><span class='final_view'>";

                    if($language == 1){
                      echo "中文";
                    }
                    else if($language == 2){
                      echo "英文";
                    }
                    else{
                      echo "其他(須附上中文譯本)";
                    }echo "</span><br />";
                    echo "<span class='col-name'>類別 :</span><span class='final_view'>";
                    if($category == 1){
                      echo "管理";
                    }
                    else if($category == 2){
                      echo "資訊";
                    }
                    else if($category == 3){
                      echo "設計";
                    }
                    else if($category == 4){
                      echo "幼保、外文、性學";
                    }
                    else if($category == 4){
                      echo "通識教育";
                    }echo "</span><br />";

                    echo "<span class='col-name'>中文標題 : </span><span class='final_view'>".dop($ch_title)."</span><br />";
                    echo "<span class='col-name'>英文標題 : </span><span class='final_view'>".dop($en_title)."</span><br />";
                    echo "<span class='col-name'>關鍵字 : </span><span class='final_view'>".dop($keywords)."</span><br />";
                    echo "<span class='col-name'>主作者 : </span><span class='final_view'>".dop($_POST['pa_ch_name'])." ".dop($_POST['pa_en_name'])."</span><br />";
                    echo "<span class='col-name'>中文摘要 :  </span><span class='final_view'>".dop($ch_summary)."</span><br />";
                    echo "<span class='col-name'>英文摘要 :  </span><span class='final_view'>".dop($en_summary)."</span><br />";                    
                    echo "<center style='margin-top:5%;'><input type=button value=離開此頁 class=btn onclick=self.location.href='check_paper.php'></center>";

                    if ( $submission_result == true ) {
                      mail_queue_add($auth_data['account'], 'submission_success', array('pid'=>$paper_id));
                    }
                  ?>
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
