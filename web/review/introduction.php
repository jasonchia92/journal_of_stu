<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>審稿流程說明::線上審稿系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <!--slide-->
    <!-- CSS -->
    <link rel="stylesheet" href="../bar/flexslider.css" type="text/css" media="screen" />
    <!-- jQuery -->
    <script type="text/javascript" src="<?=JS_URL;?>/js_spare/jquery.min.js"></script>
    <!-- FlexSlider -->
    <script type="text/javascript" src="<?=JS_URL;?>/jquery.flexslider.js"></script>
    <script type="text/javascript">
        $('document').ready(function(){
            $('.flexslider').flexslider({
                animation: "slide",
                start: function(slider){
                    $('body').removeClass('loading');
                }
            });

            $('.flex-direction-nav').hide();

            function button_display_control() {
                if ( $("a.flex-active").html() == 6 ) {
                    $('.rbtn').hide();
                    $('.back-to-first-step').show();
                } else {
                    $('.rbtn').show();
                    $('.back-to-first-step').hide();
                }

                if ( $("a.flex-active").html() == 1 ) {
                    $('.lbtn').hide();
                } else {
                    $('.lbtn').show();
                }
            }

            $('.lbtn').click(function(){
                $('a.flex-prev').click();
                button_display_control();
            });

            $('.rbtn').click(function(){
                $('a.flex-next').click();
                button_display_control();
            });

            $('.flex-control-paging').click(function(){
                button_display_control();
            });

            $('.back-to-first-step').click(function(){
                $('.flex-control-paging li:nth-child(1) a').click();
                button_display_control();
            });
        });
    </script>
    <!--slide-->
    <style type="text/css">
      .slide {
        position: relative;
      }
      .slide .inner {
        position: absolute;
        left: 0;
        bottom: 0;
      }
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
      .hero-unit {
        -webkit-box-shadow: #666 0px 2px 3px;     /*陰影for Google Chrome、Safari*/
        -moz-box-shadow: #666 1px 2px 3px;     /*陰影for Firefox*/
        box-shadow: #666 0px 2px 3px;     /*陰影for IE*/ 
        text-align: center;
        color: black;
      }
      .hero-unit img.step{
        -webkit-box-shadow: #666 0px 2px 3px;     /*陰影for Google Chrome、Safari*/
        -moz-box-shadow: #666 1px 2px 3px;     /*陰影for Firefox*/
        box-shadow: #666 0px 2px 3px;     /*陰影for IE*/ 
        margin: 3% 0 3% 0;
       }
      span.explain{
        color: blue;
        font-size: 18px;
      }
      .slider{
          text-align: center;
      }
    </style>
</head>
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
                  <?php require_once ("../bar/menu_review.php");?>
              </ul>
            </div><!--/.well -->
          </div><!--/span-->
          <div class="span10">
            <div class="slider">
              <div class="flexslider">
                <div style="border-bottom:1px solid #3F5A88; box-shadow:0 0 3px #888888; margin-bottom:12px; padding:4px;">
                  <h2>審稿流程說明</h2>
                  <button class="btn lbtn" style="float:left; display:none; margin-top:-35px;">上一步</button>
                  <button class="btn rbtn" style="float:right; margin-top:-35px;">下一步</button>
                  <button class="btn back-to-first-step" style="float:right; display:none; margin-top:-35px;">回到第一步</button>
                </div>
                <ul class="slides">
                  <li>
                    <img src="../bar/review1.png" />
                  </li>
                  <li>
                    <img src="../bar/review2.png" />
                    <span class="explain">接受審稿後可以進行評閱稿件</span><br/>
                  </li>
                  <li>
                    <img src="../bar/review3.png" />
                    <span class="explain">接受稿件後，稿件的進度會顯示在"所有論文"頁面</span><br/>
                    <span class="explain">點擊論文ID可以進入察看稿件詳情</span><br/>
                  </li>
                  <li>
                    <img src="../bar/review4.png" style="max-width:800px;"/>
                  </li>
                  <li>
                    <img src="../bar/review5.png" style="max-width:800px;"/>
                  </li>
                  <li>
                    <img src="../bar/review6.png" />
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div align='center'>
          <?php include ("../bar/end.php");?>
      </div>
    </div><!--/.fluid-container-->
  </body>
</html>
