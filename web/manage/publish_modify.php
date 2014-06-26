<?php
    require_once('../../loader.php');
    if ( !check_login_status(false) ) {
        echo "登入時效逾時，請關閉視窗後重新登入。<br/>";
        echo "<button onclick=\" window.parent.$('div#dialog').dialog('close'); \">關閉視窗</button>";
        exit;
    }

    $have_operation = false;
    $operation_result = true;
    if ( isset($_POST['submit']) ) {
        $have_operation = true;
        $new_file_pdf   = ( isset($_FILES['file_pdf'])  ? $_FILES['file_pdf']   : null );
        $new_file_cover = ( isset($_FILES['file_cover'])? $_FILES['file_cover'] : null );
        
        $operation_result = modify_publish( $_POST['id'], $_POST['title'], $_POST['publish_date'], $new_file_pdf, $new_file_cover );
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>修改資料</title>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
    <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
    <!-- jQuery MultiFile upload plugin -->
    <script type="text/javascript" src="../js/multiple-file-upload/jquery.MultiFile.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select_date').datepicker({
                dateFormat: 'yy-mm-dd',
                showOn: 'both'
            });

            $(".enable-select-pdf-file").click(function(){
                if( $(".enable-select-pdf-file").attr('checked') )
                    $(".file_pdf").attr("disabled",false); 
                else
                    $(".file_pdf").attr("disabled",true); 
            });

            $(".enable-select-cover-file").click(function(){
                if( $(".enable-select-cover-file").attr('checked') )
                    $(".file_cover").attr("disabled",false); 
                else
                    $(".file_cover").attr("disabled",true); 
            });

            $(".modify_from").submit(function(){
                var cancel_submit = false;
                if ( $(".enable-select-pdf-file").attr('checked') ) {
                    if ( ! $(".file_pdf").val().match(".pdf") ) {
                        alert("文件檔案格式為pdf");
                        cancel_submit = true;
                    }
                }

                if( $(".enable-select-cover-file").attr('checked') ){
                    if( ! ( $(".file_cover").val().match(".png") || $(".file_cover").val().match(".jpg") ) ){
                        alert("圖片檔格式為png或jpg");
                        cancel_submit = true;
                    }
                }
                
                if( cancel_submit == true )
                    return false;
            });
        });
    </script>
    <style type="text/css">
        body {
            padding: 0px;
            background-color: #DEF;
            font: 100% 微軟正黑體,細明體,新細明體;
            color: black;
            margin: 5%;
        }

        .container {
            margin-left: 10px;
        }

        .block {
            margin-top: 15px;
        }

        .form-main {
            margin-top: 0;
        }

        .operation_msg {
            z-index: 10;
            display: none;
            width: 150px;
            padding: 5px 15px;
            margin-left: -10px;
            position: absolute;
            float: right;
            top: 5px;
            right: 5px;
            background-image: linear-gradient(to bottom, #67AAE6, #3994E6);
            border-radius: 6px;
            text-align: center;
            color: #FFFFFF;
        }

        span.col-name{
          color: black;
          display: inline-block;
          width: 80px;
          vertical-align: top;
          margin: 0;
          text-align: center;
        }

        span.col-data{       
          display: inline-block;
          text-align: left;
          width: 300px;
        }

        input[type="file"]{
            background-color: #DDEEFF;
        }

        .ui-datepicker-title, .ui-widget {
            font-size: 14px;
        }

        .prompt {
            font-size: 12px;
            color: #0000FF;
        }
    </style>
</head>
<body>
    <?php
    if ( $have_operation ) { ?>
        <script type="text/javascript">
            $('document').ready(function(){
                $('.operation_msg').fadeIn(300);
                setTimeout(function(){
                    $('.operation_msg').fadeOut(300);
                }, 3000);
            });
        </script>
        <div class="operation_msg">

        <?=$operation_result ? "操作成功!" : "操作失敗，請稍後再嘗試；若持續出現此狀況，請聯絡系統管理者。"; ?>

        </div>
    <?php }
        $publish_data = get_single_publish_data( $_GET['id'] );
    ?>
    <form action="" class="modify_from" method="post" enctype="multipart/form-data">
        <div class="block form-main">
            <span class="col-name">期刊標題：</span>
            <span class="col-data">
                <input type="text" name="title" value="<?=dop($publish_data['title']); ?>" />
            </span><br/>
            <span class="col-name">出刊日期：</span>
            <span class="col-data">
                <input type="text" name="publish_date" class="select_date" value="<?=dop($publish_data['publish_date']); ?>" />
            </span><br/>
            <span class="col-name">期刊檔案：</span>
            <span class="col-data">
                <a target="_blank" href="<?="../publish/".$publish_data['file_pdf']; ?>"><?=dop($publish_data['file_pdf']); ?></a>
            </span><br/>
            <span class="col-name">期刊封面：</span>
            <span class="col-data">
                <a target="_blank" href="../publish/<?=dop($publish_data['file_cover']); ?>"><?=dop($publish_data['file_cover']); ?></a>
            </span><br/>
        </div> <!-- form-main -->

        <div class="block select-file">
            <input type="checkbox" class="enable-select-pdf-file">替換期刊檔案：
            <input type="file" name="file_pdf" class="file_pdf" disabled/><br/>
            
            <input type="checkbox" class="enable-select-cover-file">替換期刊封面：
            <input type="file" name="file_cover" class="file_cover" disabled/><br/>
        </div> <!-- select-file -->

        <div class="block prompt">
            ※期刊檔案格式必須為PDF檔，期刊封面必須為JPG或PNG檔。<br/>
            ※如檔名重複，則系統會自動在檔案名稱末端加上上傳時間，避免覆蓋到其他檔案。
        </div> <!-- prompt -->
        
        <div class="block control">
            <input type="hidden" name="id" value="<?=dop($_GET['id']); ?>">
            <input type="submit" class="btn btn-primary" name="submit" value="送出"/ >
            <input type="button" class="btn btn-primary" value="關閉視窗" onclick=" window.parent.location.reload(); window.parent.$('div#dialog').dialog('close'); " />
        </div> <!-- control -->
    </form>
</body>
<?php func_queue_handler_start(); ?>
<?php mail_queue_handler_start(); ?>
</html>