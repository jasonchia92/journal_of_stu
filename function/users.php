<?php
/* system
check_account_repeat( $account );
    檢查帳號是否重複
傳入參數：
    $account：帳號，string
傳出參數：boolean
    true：有重複
    false：無重複
*/
function check_account_repeat( $account ){
    if( count( sql_q("SELECT id FROM users WHERE email=?", array($account)) ) > 0 )
        return true;
    else
        return false;
}


/*
user_signUp( $email, $pwd );
    建立帳號(一般使用者)
傳入參數：
    $name
    $sex
    $pwd
    $titles
    $serve_unit
    $email
    $phone
    $fax
    $address
    $postcodes
    $conutry
    ※有預設值為選填欄位(Optional)
傳出參數：boolean
    true：建立成功
    false：建立失敗(帳號重複)
*/
function user_signUp( $name, $pwd, $email, $phone, $sex='', $fax='', $titles='', $serve_unit='',  $address='', $postcodes='', $country='' ){
    if( check_account_repeat( $email ) ){
        return false;
    }else{
        $sql = "INSERT INTO users(
                name, pwd, email, phone, sex,
                fax, titles, serve_unit, address, postcodes,
                country, gid
            ) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
        $array = array(
            $name, sha1($pwd), $email, $phone, $sex,
            $fax, $titles, $serve_unit,  $address, $postcodes,
            $country, "0001"
        );

        return sql_e($sql, $array);
    }
}


/*
add_user_byAdmin
    建立帳號(系統管理者)
傳入參數：
    $name
    $sex
    $pwd
    $titles
    $serve_unit
    $email
    $phone
    $fax
    $address
    $postcodes
    $conutry
    $assign_category
    $gid：使用者群組
        1000：系統管理者
        0100：審稿委員
        0010：審稿召集人
        0001：投稿者
        ※多種權限時對應欄位設為1即可，如同時擁有投稿者及審稿委員權限則為0101
傳出參數：
    boolean
*/
function add_user_byAdmin( $name, $sex, $pwd, $titles, $serve_unit, $email, $phone, $fax, $address, $postcodes, $country, $assign_category, $gid ){
    if ( check_account_repeat( $email ) ) {
        return false;
    } else {
        $sql = "INSERT INTO users(
                name, sex, pwd, titles, serve_unit,
                email, phone, fax, address, postcodes,
                assign_category, country, gid
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
        $array = array(
            $name, $sex, sha1( $pwd ), $titles, $serve_unit,
            $email, $phone, $fax, $address, $postcodes,
            $assign_category, $country, $gid
        );

        return sql_e( $sql, $array );
    }
}


/*
function admin_create( $name, $email, $pms ){
    $repeat = sql_q("SELECT id FROM admins WHERE email=?", array($email) );

    if( $repeat > 0 ) {
        return false;
    } else {
        $pwd = "";
        for( $i=0 ; $i<8 ; $i++)
            $pwd .= chr( rand( 97, 122 ) );

        return sql_e("INSERT INTO admins(pwd, name, email, pms)
            VALUES(?, ?, ?, ?)",
            array( sha1($pwd), $name, $email, $pms )
        );
    }
}
*/

/*
get_users_list
    取得使用者清單
傳入參數：
    $gid
        0:系統管理者
        1:審稿委員
        2:審稿召集人
        3:投稿者
    $page_num：頁數，int
    $slice：資料需求筆數，int
傳出參數：
    二維陣列
*/
function get_users_list( $gid ){
    $sql = "SELECT * FROM users WHERE gid IN(".get_user_group_code($gid).")";

    return sql_q($sql, array());
}


/*
get_user_group_code
    取得群組代號
傳入參數：
    $gid
        0:系統管理者
        1:審稿委員
        2:審稿召集人
        3:投稿者
傳出參數：string
*/
function get_user_group_code( $gid ) {
    $group_codes = array(
        " '1000','1001','1010','1011','1100','1101','1110','1111' ", // Contributer
        " '0100','0101','0110','0111','1100','1101','1110','1111' ", // Reviewer
        " '0010','0011','0110','0111','1010','1011','1110','1111' ", // Reviewer Convener
        " '0001','0011','0101','0111','1001','1011','1101','1111' "  // System Manager
    );

    return $group_codes[$gid];
}


/*
function get_admins_list() {
    return sql_q("SELECT id, pms, name, email FROM admins", array() );
}
*/

/*
get_user_data( $id );
    取得使用者個人資料
傳入參數：
    $uid：使用者id，int
傳出參數：
    一維陣列
備註：
    id、email、sex、gid、rid為不可修改之欄位，若要修改必須經由系統管理者，前端個資修改頁面不可將此五個欄位
    顯示為可修改欄位(即不顯示為<input>)，gid預設不顯示，rid在擁有評閱者權限時才顯示。
*/
function get_user_data( $uid ){
    $result = sql_q("SELECT * FROM users WHERE id=?", array($uid));
    if( count($result) > 0 )
        $result = $result[0];

    return $result;
}


/*
update_userData
    修改使用者個人資料
傳入參數：
    $id：使用者id，int
    $name：姓名，string
    $titles：職稱，string
    $serve_unit：服務單位，string
    $phone：連絡電話，string
    $fax：傳真，string
    $address：地址，string
    $postcodes：郵遞區號，int
    $conutry：國家，string
傳出參數：
    boolean
備註：
    id、email、sex、gid、rid為不可修改之欄位，若要修改必須經由系統管理者，前端個資修改頁面不可將此五個欄位
    顯示為可修改欄位(即不顯示為<input>)，gid預設不顯示，rid在擁有評閱者權限時才顯示。
*/
    /*
function update_user_data( $id, $name, $titles, $serve_unit, $phone, $fax, $address, $postcodes, $country){
    return sql_e("UPDATE users SET name=?, titles=?, serve_unit=?, phone=?, fax=?, address=?, postcodes=?, country=? WHERE id=?"
                , array($name, $titles, $serve_unit, $phone, $fax, $address, $postcodes, $country, $id));
}
*/

function update_userData( $array ){
    $sql = "UPDATE users SET ";
    $params = array();

    if ( isset($array['id']) ) {
        $id = $array['id'];
        unset($array['id']);
    } else if ( isset($array['uid']) ) {
        $id = $array['uid'];
        unset($array['uid']);
    }

    foreach ($array as $key => $value) {
        $sql .= $key."=?, ";
        $params[] = $value;
    }

    $sql = substr($sql, 0, strlen($sql)-2)." WHERE id=? ";
    $params[] = $id;

    return sql_e( $sql, $params );
}


/*
function update_admin_pms( $id, $pms ){
    return sql_e("UPDATE admins SET pms=? WHERE id=?", array($pms, $id));
}
*/

function delete_user( $id ){
    return sql_e("DELETE FROM users WHERE id=?", array($id));
}

/*
function admin_delete( $id ){
    return sql_e("DELETE FROM admins WHERE id=?", array($id));
}
*/

/*
change_pwd( $id, $old_pwd, $new_pwd );
    修改密碼
傳入參數：
    $id：使用者id，int
    $old_pwd：舊密碼驗證，string
    $new_pwd：新密碼，string
傳出參數：
    boolean
*/
function change_pwd( $id, $old_pwd, $new_pwd ){
    $sql_pwd = sql_q("SELECT pwd FROM users WHERE id=?", array($id));

    if( strcmp($sql_pwd[0]['pwd'], sha1($old_pwd)) != 0 )
        return false;

    return sql_e("UPDATE users SET pwd=? WHERE id=?", array(sha1($new_pwd), $id));
}


/*
reset_pwd( $uid, $new_pwd );
    重設密碼
傳入參數：
    $uid：使用者id，int
    $old_pwd：舊密碼驗證，string
    $new_pwd：新密碼，string
傳出參數：
    boolean
*/
function reset_pwd( $uid, $new_pwd ){
    $sql = "UPDATE users SET pwd=? WHERE id=?";
    $exec_result = sql_e($sql, array(sha1($new_pwd), $uid));
    
    if ( $exec_result == true ) {
        // E-mail notification
        $user_data = get_user_data($uid);
        $mail_params = array(
            'uid'           => $uid,
            'user_password' => $new_pwd,
        );
        mail_queue_add( $user_data['email'], 'password_reset', $mail_params);
    }

    return $exec_result;
}


function check_highest_pms(){
    $auth_data = get_auth_data();

    for ( $i=0 ; $i<=3 ; $i++){
        if ( $auth_data['gid']{$i} == 1 )
            return $i;
    }

    return -1;
}