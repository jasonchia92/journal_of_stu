<?php
    require_once('../../loader.php');

    if ( isset($_POST['id']) ) {
        $result = update_userData( $_POST );
        
        function script_alert( $message ) { ?>
            <script type="text/javascript">
                alert('<?php echo $message; ?>');
            </script>
        <?php }

        if( $result == true) {
            $_SESSION[session_id()]['name'] = $_POST['name'];
            func_queue_add('script_alert', '修改成功');

            // E-mail notification
            $auth_data = get_auth_data();
            mail_queue_add($auth_data['account'], 'profile_changed');
        } else {
            func_queue_add('script_alert', '操作失敗，請聯絡系統管理員。');
        }
    }
?>