<?php
    require_once('../loader.php');

    $account = $_POST['account'];
    $pwd = $_POST['password'];
    # $group = $_POST['identity'];
    # 資料表admin預定移除
    $group = 0;
    if( login( $account, $pwd, $group ) == true ){
        redirect();
    }
    else{
        header("Location: ".ROOT_URL."/web/login.php?error=1");
    }
?>
