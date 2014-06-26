<?php
    require_once('../../loader.php');
    if ( !check_login_status(false) ) {
        echo "登入時效逾時，請關閉視窗後重新登入。<br/>";
        echo "<button onclick=\" window.parent.$('div#dialog').dialog('close'); \">關閉視窗</button>";
        exit;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>編輯公告::帳號管理系統 - 樹德科技大學學報</title>
    <style type="text/css">
        body {
            padding: 0px;
            text-align: center;
        }

        div {
            margin-top: 50%;
        }
    </style>
</head>
<body>
    <div>
        <?php
            $action = update_notice( 
                $_POST['id'], 
                $_POST['title'], 
                $_POST['content']
            );
            
            if( $action )
                echo "修改完成!";
            else
                echo "發生預期外錯誤，請聯絡系統管理員！";
        ?>
    <br/><button onclick=" window.parent.location.reload(); window.parent.$('div#dialog').dialog('close'); ">關閉視窗</button>
    </div>
</body>
<?php func_queue_handler_start(); ?>
<?php mail_queue_handler_start(); ?>
</html>
