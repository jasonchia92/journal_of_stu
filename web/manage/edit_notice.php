<?php
    require_once('../../loader.php');
    check_login_status();

    $err_msg = "";
    if( isset( $_POST['submit'] ) ){
        $action = update_notice(
            $_GET['notice_id'],
            $_POST['title'], 
            $_POST['content']
        );
        
        if( $action ) {
            header('Location: ssm_notice.php');
            exit;
        } else {
            $err_msg = "發生預期外錯誤，請聯絡系統管理員！";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>公告::帳號管理系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
    <link href="../../plugin/page_number_creater/style/normal.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jQuery.appear.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/superfish.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/easypiechart.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/canvas.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/niceScroll.js"></script>

    <script src="../bootstrap/theme/js/jquery.lazyload.js"></script>
    <script src="../bootstrap/theme/js/least.min.js"></script>

    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="../js/ckfinder/ckfinder.js"></script>
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

         
    .button {
        font:bold 16px Century Gothic, sans-serif;
        font-style:normal;
        color:#eeeeee;
        background:#9ea86b;
        border:1px solid #d5dbb6;
        text-shadow:0px -1px 1px #084d0c;
        box-shadow:0px 0px 3px #252b01;
        -moz-box-shadow:0px 0px 7px #252b01;
        -webkit-box-shadow:0px 0px 7px #252b01;
        border-radius:10px;
        -moz-border-radius:5px;
        -webkit-border-radius:20px;
        width:autopx;
        padding:1px 5px;
        margin: 3px 0px;
        cursor:pointer;
    }

    .button:active {
        cursor:pointer;
        position:relative;
        top:2px;
    }

    .back:hover {
        text-decoration: none;
        color: #FFFFFF;
    }

    span.col-name {
        display: inline-block;
        padding: 5px;
        vertical-align: top;
        margin: 0;
    }

    input{
        display: inline;
    }

    .span9{
        width: 90%;
        margin: 0 auto;
        padding-top: 50px;
        font-size: 20px;
    }

    </style>
</head>
    <script type="text/javascript">
        $.ready(function(){
            $('form').validate();
        });
    </script>
  <body class="home">
    <div id="container">
        <div id="wrapper">
        <?php include ("header_manage.php");?> 
        <div class="span9">
            <div class="hero-unit">
                <div class="err_msg" style="color:#FF0000;"><?php echo $err_msg; ?></div>
                <?php $result = get_notice( $_GET['notice_id'] ); ?>
                <form method="post" action="">
                    <span class="col-name">公告標題：</span>
                    <span><input type="text" name="title" value="<?php echo dop($result['title']); ?>" style="width:388px;" required /></span>
                    <br/>
                    <span class="col-name">公告內容：</span>
                    <textarea id="editor1" name="content" class="ckeditor" style="width:400px; height:200px; resize:none;" required ><?php echo str_replace('\"', '"', $result['content']); ?></textarea>
                    <br/>
                    <div class="span1 offset3">
                        <div style="width:100px; padding-top:15px;">
                            <input type="submit" name="submit" value="修改" class="btn" />
                            <a href="ssm_notice.php" class="btn" style="text-align:center;">返回</a>
                        </div>
                    </div>
                </form>
            </div> <!-- end hero-unit -->
        </div> <!-- end span9 -->
        </div>
        <div align='center'>
        <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
    </body>
    <?php func_queue_handler_start(); ?>
    <?php mail_queue_handler_start(); ?>
    <script type="text/javascript">
        // This is a check for the CKEditor class. If not defined, the paths must be checked.
        if ( typeof CKEDITOR == 'undefined' ) {
            document.write(
                '<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
                'This sample assumes that CKEditor (not included with CKFinder) is installed in' +
                'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
                'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
                'value (line 32).' ) ;
        } else {
            var editor = CKEDITOR.replace( 'editor1' );
            CKFinder.setupCKEditor( editor, "<?php echo ROOT_URL; ?>web/js/ckfinder/" ) ;
        }
    </script>
</html>
 