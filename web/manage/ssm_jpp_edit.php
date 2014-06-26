<?php
    require_once('../../loader.php');
    check_login_status();

    $system_option_name = 'journal_publish_provition';

    if( isset( $_POST['submit'] ) ){
        $save_result = 0;
        if( save_option($system_option_name, $_POST['editor_content']) )
            $save_result = 1;

        header('Location: ?save_result=' . $save_result);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>編輯學報發行辦法::系統設定管理 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 
    <link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />

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

    <script type="text/javascript" src="<?=JS_URL;?>/jquery.validate.js"></script>
    <script type="text/javascript" src="<?=JS_URL;?>/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?=JS_URL;?>/ckfinder/ckfinder.js"></script>    <style type="text/css">
      
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

    span.col-name {
        display: inline-block;
        width: 90px;
        vertical-align: top;
        margin: 0;
    }

    textarea.ckeditor p {
        line-height: 15px;
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
            <div class="hero-unit" style="padding-top:70px;text-align:center;">
                <div class="err_msg"><?php
                    if ( isset($_GET['save_result']) ) {
                        switch ($_GET['save_result']) {
                            case 0:
                                echo '<span style="color:#FF0000;">發生預期外錯誤，請聯絡系統管理員！</span>';
                            break;
                            
                            case 1:
                                echo '<span style="color:#0000FF;">變更已儲存。</span>';
                            break;
                        }
                    }
                ?></div>
                <h2>編輯學報發行報法</h2>
                <br/>
                <?php
                    $result = get_option($system_option_name);
                    $editor_content = '';
                    if ( count($result) == 0 ) {
                        save_option($system_option_name);
                    } else {
                        $editor_content = $result['option_value'];
                    }
                ?>
                <form method="post" action="" style="width:90%;margin:0 auto;">
                    <textarea id="editor1" name="editor_content" class="ckeditor" style="width:400px; height:200px; resize:none;" required >
                        <?php echo str_replace('\"', '"', $editor_content); ?>
                    </textarea>
                    <br/>
                    <div class="span4 offset4">
                        <input type="submit" name="submit" value="儲存變更" class="btn" />
                        <a href="view_notice.php" class="btn back">放棄修改</a>
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
 