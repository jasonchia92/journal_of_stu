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
      <link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />

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
      <script type="text/javascript" src="../js/tab_page.js"></script>

      <script type="text/javascript">
        $(document).ready(function(){

          $("#tab_1").click(function(){
            $("#tab_a").fadeIn();
            $("#tab_b").fadeOut();
            $("#tab_c").fadeOut();
          });
          $("#tab_2").click(function(){
            $("#tab_b").fadeIn();
            $("#tab_a").fadeOut();
            $("#tab_c").fadeOut();
          });
          $("#tab_3").click(function(){
            $("#tab_c").fadeIn();
            $("#tab_b").fadeOut();
            $("#tab_a").fadeOut();
          });
        });
      </script>
      <style type="text/css">
        .sidebar-nav {
          padding: 9px 0;
        }

        .tab2_button{
          height: 50px;
        }

        .tab2_button input{
          float: left;
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

        tr{
          height:40px;
        }
        table{
          color:black;
        }
        .file_table{
          margin-left: auto;
        }
        .file_link{
          color:green;
        }
        .file_link:hover{
          color:blue;
        }
        .file_link:hover{
          color: blue;
          cursor: pointer;
        }
        #tab_b,#tab_c{
          display: none;
        }
        .font span{
         margin-right: 50px;
       }

       .tab_content{
        color: black;
      }
        /*
        .authors_block td {
          width: 280px;
          text-align: left;
          padding-left: 15%;
        }
        */
        #content{
          margin-left: 5%;
        }

        span.col-name {
          display: inline-block;
          width: 100px;
          vertical-align: top;
          margin: 0;
        }

        span.paper_title,
        span.paper-keywords {
          display: inline-block;
          width: 580px;
          text-align: left;
          overflow: hidden;
          word-break: break-all;
        }

        .pd-summary {
          width: 580px;
          height: 200px;
          padding: 4px 6px;
          resize : none;
          margin-bottom: 15px;
        }
        .fs{
          font-size: 28px;
          margin-top: 3%;
          margin-bottom: 2%;
        }
      </style>
    </head>
    <body class="home">
      <div id="container">
        <div id="wrapper">
         <?php include ("../bar/header_contribute.php");?> 
         <div class="span9">
          <div class="hero-unit">
            <div class="abgne_tab">
              <ul class="tabs">
                <li><a href="#tab1">論文狀態</a></li>
                <li><a href="#tab2">論文內容 </a></li>
              </ul>
              <div class="tab_container">
                <!--<table id="papers_status" class='table'>-->
                <div id="tab1" class="tab_content">
                  <?php
                  $category = array("","管理","資訊","設計","幼保、性學、外文","通識教育");
                  $language = array("","中文","英文","其他");
                          $status = array("預審中","審稿中","第一階段審稿完成","審稿完畢","預審退件 (不錄取)","等待第二階段審稿","第二階段審稿中","校稿中","出刊");   //設定狀態status
                          $id = $_GET['pid'];
                          $result = get_paper_inf( $id  );
                          $pa_data = get_paper_authors_data( $id, 1 );
                          $ea_data = get_paper_authors_data( $id, 0 );
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

                          # echo "<table width='560px' border='1'>";
                          $result['category'] = $category{$result['category']};
                          $result['status'] = $status{$result['status']};
                          $result['language'] = $language{$result['language']};
                          ?>
                          <div class='font'><span>◎ 論文 id：<?php echo dop($result['id']); ?></span>
                          <span>◎ 語言：<?php echo dop($result['language']); ?></span>
                          <span>◎ 類別：<?php echo dop($result['category']); ?></span></div>
                          ◎ 狀態：<?php echo dop($result['status']); ?><br/><br/>
                          <span class="col-name">中文標題：</span><span class="paper_title"><?php echo dop($result['ch_title']); ?></span><br/>
                          <span class="col-name">英文標題：</span><span class="paper_title"><?php echo dop($result['en_title']); ?></span><br/>
                        </div><!--tab1-->

                        <div id="tab2" class="tab_content">
                          <div class="tab2_button">
                          <input type="button" id="tab_1" class="btn btn-info" value="基本資料" />
                          <input type="button" id="tab_2" class="btn btn-info" value="論文作者" />
                          <input type="button" id="tab_3" class="btn btn-info" value="稿件檔案" />
                          </div>
                          <div id="tab_a">
                            <br /><div class='font'><span>◎ 論文 id：<?php echo dop($result['id']); ?></span>
                            <span>◎ 語言：<?php echo dop($result['language']); ?></span>
                            <span>◎ 類別：<?php echo dop($result['category']); ?></span></div><br/>
                            <span class="col-name">中文標題：</span><span class="paper_title"><?php echo dop($result['ch_title']); ?></span><br/>
                            <span class="col-name">英文標題：</span><span class="paper_title"><?php echo dop($result['en_title']); ?></span><br/>
                            <span class="col-name">中文摘要：</span>
                            <textarea class="pd-summary" name="opinion" disabled><?php echo dop($result['ch_summary']); ?></textarea><br />
                            <span class='col-name'>英文摘要：</span>
                            <textarea class="pd-summary" name="opinion" disabled><?php echo dop($result['en_summary']); ?></textarea><br />
                          </div><br/>

                          <div id='tab_b' class="authors_block">
                            <div>
                              <div class="fs">[主要作者]</div>
                              <?php print_author_data( $pa_data, 0, '', 1 ); ?>
                            </div>
                            <div>
                              <div class="fs">[其他作者]</div>
                              <?php
                              if ( count($ea_data) > 0 )
                                print_author_data( $ea_data );
                              else
                                echo "此篇論文無其他作者.";
                              ?>
                            </div>
                          </div><!--tab_b-->
                          <div id='tab_c'>
                            <br/>                         
                            <?php print_pfcr( $result['id'],3); ?>
                          </div><!--tab_c-->
                        </div><!--tab2-->
                        <!--<input type="button" onClick="javascript:history.back(1)" value="回上一頁" class="btn" style="margin-left:20%;" />-->
                        <!--</table>-->
                      </div><!--tab_container-->
                    </div><!--abgne_tab-->
                  </div>
                </div>
              </div>
            </div>
            <div align='center'>
              <?php include ("../bar/end.php");?>
            </div>
          <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
          <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
        </body>
    </html>
