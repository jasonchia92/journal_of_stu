<?php
/* system
check_account( $account, $group );
    確認帳號資訊
傳入參數：
    $account：帳號(即email)，string
    $group：群組 (0:一般使用者，1:系統管理者)
傳出參數：
    1.一維陣列
    2.false (無此帳號時)
*/
function check_account( $account, $group ){
    $query_string = "SELECT id, name, pwd, gid FROM users WHERE email=?";
    if( $group == 1)
        $query_string = "SELECT id, name, pwd, pms FROM admins WHERE email=?";
    
    $result = sql_q($query_string, array($account));

    return ( count($result)>0 ? $result[0] : false );
}


/* system
check_pwd( $pwd, $sql_pwd );
    密碼驗證
傳入參數：
    $pwd：使用者輸入的密碼，string
    $sql_pwd：資料庫密碼紀錄：string
傳出參數：
    1.true：驗證成攻
    2.false：驗證失敗
*/
function check_pwd( $pwd, $sql_pwd ){
    return ( strcmp(sha1($pwd), $sql_pwd)==0 ? true : false );
}


/*
login( $account, $pwd );
    登入
傳入參數：
    $account：帳號(即email)，string
    $pwd：密碼，string
    $group：群組 (0:一般使用者，1:系統管理者)
傳出參數：
    1.true，登入成功
        (同時設定cookie值，確認登入狀態請用)
*/
function login( $account, $pwd, $group ){
    $account_data = check_account($account, $group);
    if( !$account_data ){
    //  echo "無此帳號.";
        return false;
    }

    if( !check_pwd($pwd, $account_data['pwd']) ){
    //  echo "密碼錯誤.";
        return false;
    }

    if( $group == 1 ){
        $account_data['gid'] = "1000";
    }

    unset( $account_data['pwd'] );
    $_SESSION[ session_id() ] = $account_data;
    $_SESSION[ session_id() ]['account'] = $account;

    return true;
}


function get_auth_data() {
    return isset( $_SESSION[ session_id() ] ) ? $_SESSION[ session_id() ] : false;
}


/*
logout();
    登出
傳入參數：
    無
傳出參數：
    無
*/
function logout(){
    unset( $_SESSION[ session_id() ] );
    setcookie( session_name(), null, time()-3600);
}


/*
check_pms( $sys_id );
    確認系統權限 Check Permissions
傳入參數：
    $sys_id：系統代碼
        0:學報管理系統
        1:線上審稿系統
        2:線上投稿系統
傳出參數：
    1.true
    2.false(直接自動導向到首頁)
*/
function check_pms( $sys_id ){
    $auth_data = get_auth_data();
    if ( $auth_data == false ) 
        return false;

    if ( $auth_data['gid']{ $sys_id } == 0 ){
        return true;
    } else {
        echo '<script> alert("您沒有權限訪問此頁面。"); window.location.href="'.ROOT_URL.'/web/home.php"; </script>';
        return false;
    }
}


function check_admin_pms( $sys_id ){
    $auth_data = get_auth_data();
    if ( $auth_data == false ) 
        return false;

    if ( $auth_data['pms']{ $sys_id } == 0 ){
        return true;
    } else {
        echo '<script> alert("您沒有權限訪問此頁面。"); window.location.href="'.ROOT_URL.'/web/home.php"; </script>';
        return false;
    }
}


function get_login_redirect_url() {
    $auth_data = get_auth_data();
    $link = array(
        'manage/index.php',
        'review/all_paper.php',
        'review/all_paper.php',
        'contribute/check_paper.php'
    );

    for ($i=0; $i<=3 ; $i++) { 
        if ( $auth_data['gid']{$i} == 1 ) {
            return ROOT_URL."/web/".$link[$i];
            break;
        }
    }
}


function redirect(){
    header("Location: ".get_login_redirect_url());
}