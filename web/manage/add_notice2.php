<?php
    require_once('../../loader.php');

    session_start();

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
    <title>新增公告::帳號管理系統 - 樹德科技大學學報</title>
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
            $user_data = get_auth_data();
            
            $action = add_notice( 
                $_POST['title'],
                $_POST['content'],
                $user_data['id'],
                date("Y-m-d")
            );
            
            if( $action )
                echo "新增完成!";
            else
                echo "發生預期外錯誤，請聯絡系統管理員！";
        ?>
    <br/><button onclick=" window.parent.location.reload(); window.parent.$('div#dialog').dialog('close'); ">關閉視窗</button>
    </div>
</body>
</html>