<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>投稿流程說明::線上投稿系統 - 樹德科技大學學報</title>
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
        if ( $("a.flex-active").html() == 7 ) {
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
  .flexslider .btn{
    margin-top: -29px;
  }
  .flexslider .slides img{
    margin: 0 auto;
  }
  .hero-unit {
    font-size: 16px;
    -webkit-box-shadow: #666 0px 2px 3px;     /*陰影for Google Chrome、Safari*/
    -moz-box-shadow: #666 0px 2px 3px;     /*陰影for Firefox*/
    box-shadow: #666 0px 2px 3px;     /*陰影for IE*/ 
    color: black;
    text-align: center;
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
  <body class="home">
      <div id="container">
          <div id="wrapper">
              <?php include ("../bar/header_contribute.php");?> 
          
              <div class="span10">
                <div class="slider" >
                  <div class="flexslider">
                    <div style="border-bottom:1px solid #3F5A88; box-shadow:0 0 3px #888888; margin-bottom:12px; padding:4px;">
                      <h2>投稿流程說明</h2>
                      <button class="btn lbtn" style="float:left; display:none;">上一步</button>
                      <button class="btn rbtn" style="float:right;">下一步</button>
                      <button class="btn back-to-first-step" style="float:right; display:none;">回到第一步</button>
                    </div>
                    <ul class="slides">
                      <li>
                        <img src="../bar/step1.png" />
                        <span class="explain">把"我同意"的選項打勾以後才可做下一步的動作</span><br/>
                      </li>
                      <li>
                        <img src="../bar/step2.png" style="max-width:800px;"/>
                        <span class="explain">所有欄位都必須填寫才可做下一步的動作</span><br/>
                      </li>
                      <li>
                        <img src="../bar/step3.png" />
                      </li>
                      <li>
                        <img src="../bar/step4.png" />
                      </li>
                      <li>
                        <img src="../bar/step5.png" />
                      </li>
                      <li>
                        <img src="../bar/step6.png" />
                      </li>
                      <li>
                        <img src="../bar/step7.png" />
                        <span class="explain">提交完成後，可以在"查詢稿件進度"查看狀態</span><br/>
                        <span class="explain">點擊論文ID便可查看詳細資料</span><br/>
                      </li>
                    </ul>
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
