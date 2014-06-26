<?php
    require_once('../../loader.php');

    if ( !check_login_status(false) ) {
        echo "登入時效逾時，請關閉視窗後重新登入。<br/>";
        echo "<button onclick=\" window.parent.$('div#dialog').dialog('close'); \">關閉視窗</button>";
        exit;
    }

    $haveOperation = false;
    $operationResult = true;
    if ( isset($_POST['form_submit']) ) {
        $haveOperation = true;
        include('edit_userData_process.php');
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>編輯審稿委員資料::帳號管理系統 - 樹德科技大學學報</title>
    <!-- jQuery 1.7.1 -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write('<script src="../js/js_spare/jquery.min.js"><\/script>')</script>
    <!-- jQuery MultiFile upload plugin -->
    <script type="text/javascript" src="../js/multiple-file-upload/jquery.MultiFile.js"></script>
    <!-- jQuery validate plugin -->
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/cmxforms.js"></script>
    <script type="text/javascript">
        function cancel_edit(){
            window.parent.$('div#dialog').dialog('close');
        }

        function randomPassword(length) {
            chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            pwd = "";
            for(x=0;x<length;x++) {
                i = Math.floor(Math.random() * 62);
                pwd += chars.charAt(i);
            }
            
            return pwd;
        }
        
        $(document).ready(function(){
            $("form#commentForm").validate();

            $('#create_randPwd').click(function(){
                pwd = randomPassword(8);
                $('input[name="pwd"]').attr('value', pwd);
                $('input[name="confirmPwd"]').attr('value', pwd);
                $('#display_randPwd').attr('value', pwd);
                return false;
            });

            $('form').submit(function(){
                if ( ($('input[name="pwd"]').val()!='') || ($('input[name="confirmPwd"]').val()!='') ) {
                    if ( $('input[name="pwd"]').val() != $('input[name="confirmPwd"]').val() ) {
                        alert('兩次密碼輸入不一致，請重新輸入。');
                        return false;
                    } else {
                        var password = $('input[name="pwd"]').val().length;
                        if ( password<8 || password>32 ) {
                            alert('密碼長度限制為8~32字元。');
                            return false;
                        }
                    }
                }
            });
        });
    </script>
    <style type="text/css">
        html, body {
            margin: 0;
            padding: 10px 0;
        }

        body, td, th {
            padding: 2px 5px;
        }

        .operation_msg {
            z-index: 10;
            display: none;
            width: auto;
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

        label.error {
            color: #F00;
            font-size: 12px;
        }

        table#data {
            margin: 0 auto;
        }

        table#data th {
            text-align: center;
            width: 102px;
        }

        input[type="text"],
        input[type="password"] {
            width: 200px;
        }
    </style>
</head>
<body>
    <?php
        if ( $haveOperation ) : ?>
            <script type="text/javascript">
                $('document').ready(function(){
                    $('.operation_msg').fadeIn(300);
                    setTimeout(function(){
                        $('.operation_msg').fadeOut(300);
                    }, 3000);
                });
            </script>
            <div class="operation_msg">
                <?php echo $operationResult ? "操作成功" : "操作失敗，請稍後再嘗試；若持續出現此狀況，請聯絡系統管理者。"; ?>
            </div>
        <?php endif;

        $user_data = get_user_data( $_GET['id'] );
    ?>
    <form id="commentForm" class="cmxform" method="post" action="">
        <table id="data" summary=""><tbody>
            <tr><th>ID</th><td><?php echo $user_data['id']; ?></td></tr>
            <tr><th>姓名</th><td><input type="text" name="name" class="required" value=<?php echo "'".dop( $user_data['name'] )."'"; ?> /></td></tr>
            <tr><th>性別</th><td><?php
                $option_name = array("男", "女");
                for( $i=0 ; $i<=1 ; $i++ ){
                    if( strcmp($user_data['sex'], $i) == 0 )
                        echo "<input type=\"radio\" name=\"sex\" value=\"{$i}\" checked />".$option_name[$i];
                    else
                        echo "<input type=\"radio\" name=\"sex\" value=\"{$i}\" />".$option_name[$i];
                }
            ?></td></tr>
            <tr><th>E-mail</th><td><input type="text" name="email" class="required" value=<?php echo "'".dop( $user_data['email'] )."'"; ?> /></td></tr>
            <tr><th>連絡電話</th><td><input type="text" name="phone" class="required number" value=<?php echo "'".dop( $user_data['phone'] )."'"; ?> /></td></tr>
            <?php
                if ( $_GET['gid'] != 0 ) : ?>
                    <tr><th>傳真</th><td><input type="text" name="fax" class="number" value=<?php echo "'".dop( $user_data['fax'] )."'"; ?> /></td></tr>
                    <tr><th>地址</th><td><input type="text" name="address" value=<?php echo "'".dop( $user_data['address'] )."'"; ?> /></td></tr>
                    <tr><th>郵遞區號</th><td><input type="text" name="postcodes" value=<?php echo "'".$user_data['postcodes']."'"; ?> /></td></tr>
                    <tr><th>服務單位</th><td><input type="text" name="serve_unit" value=<?php echo "'".dop( $user_data['serve_unit'] )."'"; ?> /></td></tr>
                    <tr><th>職稱</th><td><input type="text" name="titles" value=<?php echo "'".dop( $user_data['titles'] )."'"; ?> /></td></tr>
                    <tr><th>國家</th><td><input type="text" name="country" value=<?php echo "'".dop( $user_data['country'] )."'"; ?> /></td></tr>
                <?php endif;

                if ( $_GET['gid'] == 1 ) : ?>
                    <tr><th>分派領域</th><td>
                        <select name="assign_category"><?php
                            $option_name = array("0.未定", "1.管理", "2.資訊", "3.設計", "4.幼保.外文.性學", "5.通識教育");
                            for ( $i=0 ; $i<=5 ; $i++ ) {
                                if( strcmp($user_data['assign_category'], $i) == 0 )
                                    echo "<option value=\"{$i}\" selected>".$option_name[$i]."</option>";
                                else
                                    echo "<option value=\"{$i}\">".$option_name[$i]."</option>";
                            }
                        ?></select>
                    </td></tr>
                <?php endif;
            ?>
            <tr><th>密碼</th><td><input type="password" name="pwd" /></td></tr>
            <tr><th>確認密碼</th><td><input type="password" name="confirmPwd" /></td></tr>
            <tr>
                <th><button id="create_randPwd">產生隨機密碼</button></th>
                <td><input type="text" id="display_randPwd" /></td>
            </tr>
            <tr><td colspan="2" style="text-align:center; font-size:13px; color:#0000FF;">※不變更密碼時請保持欄位空白。</td></tr>
            <tr>
                <th>使用者群組</th>
                <td><?php
                    $group_name_arr = array(
                        '管理員',
                        '審稿委員',
                        '審稿召集人',
                        '投稿者'
                    );
                    for ($i=0; $i<=3; $i++): ?>
                        <input type="checkbox" name="user_group[]" value="<?php echo $i; ?>" <?php if ( $user_data['gid']{$i} == 1 ) echo 'checked'; ?> /><?php echo $group_name_arr[$i]; ?><br/>
                    <?php endfor;
                ?></td>
            </tr>
            <tr><th colspan="2">
                <input type="hidden" name="uid" value=<?php echo "'".$user_data['id']."'"; ?> />
                <input type="submit" name="form_submit" value="修改" />
                <input type="button" value="關閉" onclick=" cancel_edit(); " />
            </th></tr>
        </tbody></table>
    </form>         
</body>
</html>
<?php func_queue_handler_start(); ?>
<?php mail_queue_handler_start(); ?>