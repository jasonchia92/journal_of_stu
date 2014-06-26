<?php
    require_once("../loader.php");

    if ( !check_login_status(false) ) {
        echo "Session is timeout. Please login again.";
        exit;
    }

    switch( $_POST['action'] ){
        case 'delete_user':
            echo delete_user($_POST['id']) ? "success" : "fail" ;
        break;

        case 'notice_delete':
            echo delete_notice($_POST['id']) ? "success" : "fail" ;
        break;

        case 'send_mail':
            $content_obj = mail_sample_loader($_POST['sample_name'], $_POST);
            echo send_mail( $_POST['recipient'], $content_obj ) ? 'success' : 'fail';
        break;

        default:
        break;
    }