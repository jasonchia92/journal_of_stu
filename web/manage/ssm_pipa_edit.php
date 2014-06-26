<?php
    require_once('../../loader.php');
    check_login_status();

    $system_option_name = 'personal_information_protection_act';

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
    <title>編輯個人資料保護法::系統設定管理 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
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
    <script type="text/javascript" src="<?=JS_URL;?>/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="<?=JS_URL;?>/js_spare/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?=JS_URL;?>/jquery.validate.js"></script>
    <script type="text/javascript" src="<?=JS_URL;?>/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?=JS_URL;?>/ckfinder/ckfinder.js"></script>
    <script type="text/javascript">
        $.ready(function(){
            $('form').validate();
        });
    </script>
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
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <?php include ("menu_left.php");?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
            <div class="hero-unit">
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
                <h2>編輯個人資料保護法</h2>
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
                <form method="post" action="">
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
        <div align='center'>
        <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
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
 