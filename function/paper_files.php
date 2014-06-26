<?php

/*
get_paper_user_type
    取得論文使用者類型
Params:
    $paper_id
Returns:
    user_type:
        1. submit_user
        2. reviewer_1
        3. reviewer_2
        4. reviewer_3
        5. admin
*/
function get_paper_user_type( $paper_id ) {
    $auth_data = get_auth_data();

    # admin extension handle
    if ( strpos(get_full_url(), 'web/manage') && $auth_data['gid']{0} == 1 )
        return 'admin';
    /*
    $sys_name = array('manage', 'review', 'contribute');
    $current_sys = -1;
    foreach ($sys_name as $key => $value) {
        if ( strpos(get_web_path_url(), $value) > 0 ) {
            $current_sys = $key;
            break;
        }
    }

    # format gid
    $gid = '0000';
    $gid{0} = $auth_data['gid']{0};
    $gid{1} = $auth_data['gid']{1} || $auth_data['gid']{2};
    $gid{2} = $auth_data['gid']{3};

    if ( $gid{$current_sys} == 1 )
        return 'admin';
    */

    $paper_id = get_sys_pid($paper_id);

    $sql = "SELECT
            submit_user,
            reviewer_1,
            reviewer_2,
            reviewer_3
        FROM papers WHERE id=? ";
    $related_users = sql_q( $sql, array($paper_id) );

    # Same time have permissions of submit_user and reviewer, remove column submit_user at review system.
    if ( strpos(get_full_url(), 'web/review') )
        unset($related_users[0]['submit_user']);

    # 取得使用者類型
    $current_userID = $auth_data['id'];
    $target_user = '';
    foreach ($related_users[0] as $key => $value) {
        if ( $current_userID == $value ) {
            $target_user = $key;
            break;
        }
    }

    return $target_user;
}


/*
multi_upload( $paper_id, $value_name, $file_category=0, $description='', $upload_dir=UPLOAD_DIR );
    多檔案上傳

Parameters:
    $paper_id
    $value_name: 檔案上傳表單變數名稱
    $file_category: 檔案類別
        0: 論文檔(壓縮檔, zip, rar)
        1: 著作權讓與同意書
    $file_access: 檔案存取權限 (依照使用的子系統來分)
        0: 管理系統
        1: 審稿系統
        2: 投稿系統
    $description
    $upload_dir (Default: UPLOAD_DIR, /path/to/web/uploads)

Returns:
    1. True
    2. False

*/
function multi_upload( $paper_id, $value_name, $file_category=0, $description='', $upload_dir=UPLOAD_DIR ){
    global $_FILES;

    # convert to 1 dimension array
    if ( ! is_array($_FILES[$value_name]['name'] ) ) {
        $_FILES[$value_name]['name'] = array( $_FILES[$value_name]['name'] );
        $_FILES[$value_name]['type'] = array( $_FILES[$value_name]['type'] );
        $_FILES[$value_name]['tmp_name'] = array( $_FILES[$value_name]['tmp_name'] );
        $_FILES[$value_name]['error'] = array( $_FILES[$value_name]['error'] );
        $_FILES[$value_name]['size'] = array( $_FILES[$value_name]['size'] );
    }

    $bln = true;
    for ( $i=0; $i<count($_FILES[$value_name]['name']) ; $i++) {
        if($_FILES[$value_name]['error'][$i] != 0){
            echo "Error:".$_FILES[$value_name]['error'][$i]."<br/>";
            $bln = false;
        }else{
            # 分離檔案名稱及副檔名
            // $file_arr = explode(".", $_FILES[$value_name]['name'][$i]);
            $file_origin_name = $_FILES[$value_name]['name'][$i];
            # 取得副檔名
            $file_type = pathinfo($file_origin_name, PATHINFO_EXTENSION);
            # mime type
            $mime_type = $_FILES[$value_name]['type'][$i];
            # real name
            $real_name = md5($file_origin_name . date('Ymdhis') . rand(0, 10000)). '.' . $file_type;

            # 取得時間
            $date = date("Y-m-d");
            $time = date("H:i:s");

            # 重組檔名
            if ( $file_category == 0 )
                $file_name = $paper_id.'_'.str_replace('-', '', $date).'_'.str_replace(':', '-', $time).'.'.$file_type;
            else
                $file_name = $file_origin_name;

            # 目錄不存在時建立目錄
            if ( !is_dir($upload_dir ) )
                @mkdir($upload_dir, 0755, true);
            
            # 移動檔案
            // move_uploaded_file($_FILES[$value_name]["tmp_name"][$i], iconv("utf-8//ignore", "big5", $upload_dir.'/'.$file_name) );
            move_uploaded_file($_FILES[$value_name]["tmp_name"][$i], iconv("utf-8//ignore", "big5", $upload_dir.'/'.$real_name) );

            # rewrite paper_id to format length-10
            $paper_id = get_sys_pid( $paper_id );
            $revision = sql_q( "SELECT MAX(revision) FROM pfc_record WHERE paper_id=? AND file_category=?", array( $paper_id, $file_category ) );
            
            if ( $revision == null )
                $revision = 0;

            $revision = $revision[0]['MAX(revision)']+1;

            # upload time point
            $upload_tp = sql_q(
                "SELECT status FROM papers WHERE id=?",
                array( get_sys_pid($paper_id) )
            );

            if ( count($upload_tp) )
                $upload_tp = $upload_tp[0]['status'];
            else
                $upload_tp = 0;

            /*
            # check user group
            $access_tpl = array(
                array(0, 0),
                array(1, 0),
                array(0, 1)
            );

            # set access permissions
            $file_access_pms = $access_tpl[ $file_access ];
            */
            $auth_data = get_auth_data();
            $target_user = get_paper_user_type( $paper_id );

            $array = array(
                $paper_id,
                $revision,
                $date.'  '.$time,
                $upload_tp,
                $file_name,
                $real_name,
                $file_category,
                $mime_type,
                $description
            );

            $not_admin = strcmp($target_user, 'admin') > 0 ? true : false;

            if ( $not_admin )
                $array[] = 1;

            $sql = "INSERT INTO pfc_record(
                    paper_id,
                    revision,
                    upload_time,
                    upload_tp,
                    file_name,
                    real_name,
                    file_category,
                    mime_type,
                    description,
                    ".( $not_admin ? 'fa_'.$target_user.',' : '' )."
                    upload_user
                ) VALUES(?,?,?,?,?,?,?,?,?,?".( $not_admin ? ',?': '' ).")
            ";

            $array[] = $auth_data['id'];

            sql_e( $sql, $array );

            # If uploader is contributer, set papers.paper_file_check = 0, papers.craa_upload = 0.
            if ( $not_admin )
                sql_e( "UPDATE papers SET paper_file_check=?, craa_upload=? WHERE id=? ", array( 0, 0, get_sys_pid($paper_id) ) );
            else if ( strcmp($target_user, 'submit_user') > 0 )
                sql_e( "UPDATE papers SET smu_edit_enable=? WHERE id=? ", array( 0, get_sys_pid($paper_id) ) );
        }
    }

    return $bln;
}

/*
file_delete( $record_id, $file_name );
    刪除檔案

Parameters:
    $record_id
    $file_name
    ※一維或二維陣列皆可

Returns:
    1. True
    2. False

Comments: 請先 check_file_pms() 再使用
*/
function file_delete( $record_id, $file_name ){
    if ( ! is_array( $record_id ) )
        $record_id = array( $record_id );
    if ( ! is_array( $file_name ) )
        $file_name = array( $file_name );

    foreach ($file_name as $key => $value)
        if ( file_exists(UPLOAD_DIR.'/'.$value) ) unlink( UPLOAD_DIR.'/'.$value );

    $record_id = acts( $record_id, 1 );
    $sql = "DELETE FROM pfc_record WHERE record_id IN (".$record_id.") ";

    return sql_e( $sql, array() );
}


/*
check_file_pms( $paper_id, $id, $id_type );
    確認檔案讀取權限

Parameters:
    $paper_id
    $id : user_id 或 reviewer_id
    $id_type : 傳入的id類型
        0:user_id
        1:reviewer_id

Returns:
    true : 接受存取
    false : 拒絕存取

Comments:避免URL直接存取
*/
function check_file_pms( $paper_id ){
    $auth_data = get_auth_data();
    if ( $auth_data['gid']{0} == 1 ){
        return true;
    }

    $uid = $auth_data['id'];
    $sql = "SELECT id FROM papers 
                WHERE id=? 
                AND (
                    submit_user=? 
                    OR reviewer_1=? 
                    OR reviewer_2=? 
                    OR reviewer_3=?
                )";
            $array = array(
                get_sys_pid( $paper_id ),
                $uid,
                $uid,
                $uid,
                $uid
            );

    return ( count(sql_q($sql, $array))>0 ? true : false );
}


/*
get_file( $file_name );
    取得檔案 (下載檔案)

Parameters:
    $file_name

Returns:
    success: start download
    error: file missing

Comments: 請先 check_file_pms() 再使用
*/
function get_file( $file_info ){
    $file = UPLOAD_DIR.'/'.$file_info['real_name'];
    if ( file_exists($file) ) {
        header('Content-Description: File Transfer');
        // header('Content-Type: application/octet-stream');
        header('Content-Type: '.$file_info['mime_type']);
        header('Content-Disposition: attachment; filename='.$file_info['file_name']);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: '.filesize($file) );
        ob_clean();
        flush();
        readfile($file);
        exit;
    } else {
        return false;
    }
}


function get_file_info( $pfc_id ) {
    $sql = 'SELECT * FROM pfc_record WHERE record_id=?';
    $result = sql_q( $sql, array($pfc_id) );
    return ( count($result) > 0 ? $result[0] : array() );
}


/*
get_paper_files( $paper_id );
    取得論文檔案

Parameters:
    $paper_id
    $type
        0: 管理員
        1: 審稿委員
        2: 審稿召集人
        3: 投稿者
    $file_category: 檔案類別
        0: 論文檔(壓縮檔, zip, rar)
        1: 著作權讓與同意書

Returns:
    success: start download
    error: file missing
*/
function get_paper_files( $paper_id, $type, $file_category=0 ){
    $paper_id = get_sys_pid( $paper_id );
    $sql = "";
    $array = array();

    if ( $type != 0 ) { # global
        $target_user = get_paper_user_type($paper_id);
        $auth_data = get_auth_data();

        # 取得檔案紀錄
        $sql = "SELECT
                a.*,
                b.name AS file_uploader
            FROM pfc_record AS a
            LEFT JOIN users AS b ON a.upload_user=b.id
            WHERE
                a.paper_id=?
                AND a.file_category=?
                AND a.fa_".$target_user."='1'
                AND b.id=? ";
        $array = array( $paper_id, $file_category, $auth_data['id'] );
    } else { # admin
        $sql = "SELECT
                a.*,
                b.name AS file_uploader
            FROM pfc_record AS a
            LEFT JOIN users AS b ON a.upload_user=b.id
            WHERE
                a.paper_id=? ";
        $array = array( $paper_id );
    }
    
    $sql .= "ORDER BY record_id DESC ";

    return sql_q( $sql, $array );
}


/* [ Template ]
print_pfcr( $paper_id, $del_func=0 );
    輸出論文檔案變更紀錄
Parameters:
    $paper_id
    $user_type : 使用者類型
        0: 管理員
        1: 審稿委員
        2: 審稿召集人
        3: 投稿者
        4: 管理者 (檢視模式)
    $upload_func : 啟用上傳檔案，視需求開或關 (Default:off)
        0:關閉 (Default)
        1:開啟
    $des_uploader : 指定上傳者
        0:不指定 (Default)
        1:指定

Returns:
    html code

Comments: 後端請配合 pfcr_file_uploader() 與 pfcr_file_deleter() 使用(不使用刪除功能無需使用pfcr_file_deleter();)
*/
function print_pfcr( $paper_id, $user_type, $upload_func=0 ){
    $paper_id = get_sys_pid( $paper_id );
    $pfc_record = get_paper_files(
        $paper_id,
        ( $user_type==4 ? 0 : $user_type )
    );

    # start if
    if ( ! count($pfc_record) ) {
        echo '此篇論文未上傳任何檔案';
    } else {
        $link = ROOT_URL.'/web/get_file.php?pfc_id=';
        $upload_tp = array(
            '預審中',
            '審稿中',
            '第一階段審稿完成',
            '審稿完畢',
            '預審退件',
            '等待第二階段審稿',
            '第二階段審稿中',
            '校稿中'
        );
        switch ( $user_type ) {
            case 0:
                $paper_data = get_paper_inf( $paper_id );
                $paper_file_check = $paper_data['paper_file_check'] ? 'checked' : '';
                $smu_edit_enable  = $paper_data['smu_edit_enable']  ? 'checked' : '';
                $craa_upload      = $paper_data['craa_upload']      ? 'checked' : '';

                $sql = "SELECT
                        a.reviewer_1,
                        a.reviewer_2,
                        a.reviewer_3,
                        a.submit_user,
                        b.id AS uid,
                        b.name
                    FROM papers AS a LEFT JOIN users AS b ON
                        ( a.reviewer_1=b.id
                        OR a.reviewer_2=b.id
                        OR a.reviewer_3=b.id
                        OR a.submit_user=b.id )
                    WHERE
                        a.id=? ";
                $related_users = sql_q($sql, array($paper_id));

                $fa_col = $related_users[0];

                # user data key bind
                $user_data = array();
                foreach ($related_users as $key => $value)
                    $user_data[$value['uid']] = $value['name'];
                
                ?>
                <input type="checkbox" name="paper_file_check" value="<?php echo $paper_data['paper_file_check']; ?>" <?php echo $paper_file_check; ?> />&nbsp;論文資料與檔案均已確認<br/>
                <input type="checkbox" name="smu_edit_enable"  value="<?php echo $paper_data['smu_edit_enable']; ?>"  <?php echo $smu_edit_enable; ?>  />&nbsp;開放投稿者上傳論文檔案<br/>
                <input type="checkbox" name="craa_upload"      value="<?php echo $paper_data['craa_upload']; ?>"      <?php echo $craa_upload; ?>      />&nbsp;開放投稿者上傳著作權讓與同意書
                <table class="pfcr" border="1" style="text-align:center;">
                    <thead>
                        <th style="width:40px;">刪除</th>
                        <th style="width:300px;">檔案資訊</th>
                        <th style="width:250px;">備註</th>
                        <th style="width:125px;">存取權限</th>
                    </thead>
                    <tbody>
                    <?php
                        $pfcr_id_list = '';
                        foreach ($pfc_record as $key => $value) {
                            $pfcr_id_list .= $value['record_id'].',';
                            ?>
                            <tr>
                                <td><input type="checkbox" name="file_delete[]" value="<?php echo $value['record_id']; ?>" /></td>
                                <td class="summary" style="text-align:left; padding:2px 10px;">
                                    <span>版次：<?php echo $value['revision']; ?></span>
                                    <span style="margin-left:50px;">上傳者：</span><span><?php echo $value['file_uploader']; ?></span><br/>
                                    <span>時間點：<?php echo $upload_tp[ $value['upload_tp'] ]; ?></span><br/>
                                    <span>上傳時間：<?php echo $value['upload_time']; ?></span><br/>
                                    <span>檔案下載連結：</span><span class="file_link" onClick="window.open('<?php echo $link.$value['record_id']; ?>', '論文檔案', 'width=250,height=250,location=no,toolbar=no');">Download</span>
                                </td>
                                <td>
                                    <textarea style="width:280px; height:90px; resize:none; border:none; background:#FFFFFF; color:#000000;" disabled><?php echo dop( $value['description'] ); ?></textarea>
                                </td>
                                <td style="text-align:left; padding: 0px 5px;">
                                    <?php
                                        $user_type_arr = array("submit_user", "reviewer_1", "reviewer_2", "reviewer_3");
                                        # for ( $i=0; $i<count($related_users) ; $i++) { 
                                        foreach( $user_type_arr as $key_2 => $value_2 ) {
                                            if ( $fa_col[$value_2] == 0 )
                                                continue;

                                            $user_name = $user_data[ $fa_col[$value_2] ];
                                            $checked = ( $value['fa_'.$value_2] ? ' checked' : '' );
                                            $color = !$key_2 ? '#00AA00' : '#9911AA';
                                            ?>
                                                <input type="checkbox" name="fa_stat[<?php echo $value['record_id']; ?>][]" value="<?php echo $value_2; ?>"<?php echo $checked; ?> />&nbsp;<span style="color:<?php echo $color; ?>;"><?php echo $user_name; ?></span><br/>
                                            <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody></table>
                    <input type="hidden" name="pfcr_id_list" value="<?php echo $pfcr_id_list; ?>" />
                <?php
            break;

            case 4:
                $paper_data = get_paper_inf( $paper_id );
                ?>
                <table class="pfcr" border="1" style="text-align:center;">
                    <thead>
                        <th style="width:300px;">檔案資訊</th>
                        <th style="width:280px;">備註</th>
                    </thead>
                    <tbody>
                    <?php
                        $pfcr_id_list = '';
                        foreach ($pfc_record as $key => $value) {
                            $pfcr_id_list .= $value['record_id'].',';
                            ?>
                            <tr>
                                <td class="summary" style="text-align:left; padding:2px 10px;">
                                    <span>版次：<?php echo $value['revision']; ?></span>
                                    <span style="margin-left:50px;">上傳者：</span><span><?php echo $value['file_uploader']; ?></span><br/>
                                    <span>時間點：<?php echo $upload_tp[ $value['upload_tp'] ]; ?></span><br/>
                                    <span>上傳時間：<?php echo $value['upload_time']; ?></span><br/>
                                    <span>檔案下載連結：</span><span class="file_link" onClick="window.open('<?php echo $link.$value['record_id']; ?>', '論文檔案', 'width=250,height=250,location=no,toolbar=no');">Download</span>
                                </td>
                                <td>
                                    <textarea style="width:280px; height:90px; resize:none; border:none; background:#FFFFFF; color:#000000;" disabled><?php echo dop( $value['description'] ); ?></textarea>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody></table>
                <?php
            break;

            case 1:
            case 2:
            case 3:
                ?>
                    <style type="text/css">
                        .file_link {
                            cursor: pointer;
                            color: #31E;
                        }

                        .file_link:hover {
                            color: #39D;
                        }
                    </style>
                    <div>
                        檔案名稱：<?php echo $pfc_record[0]['file_name']; ?><br/>
                        上傳時間：<?php echo $pfc_record[0]['upload_time']; ?><br/>
                        檔案下載：<span class="file_link" onClick="window.open('<?php echo $link.$pfc_record[0]['record_id']; ?>', '論文檔案', 'width=250,height=250,location=no,toolbar=no');">點此開始下載</span>
                        <br/>
                        <span style="vertical-align:top;">備&emsp;&emsp;註：</span>
                        <span><textarea disabled style="resize:none; margin-top:5px; width:400px; height:100px; background-color:#F5F5F5;"><?php echo $pfc_record[0]['description']; ?></textarea></span>
                    </div>
                <?php
                    if ( count($jppa = get_paper_files($paper_id, $user_type, 1)) > 0 ):?>
                        <br/>
                        <div>
                            檔案名稱：<?php echo $jppa[0]['file_name']; ?><br/>
                            上傳時間：<?php echo $jppa[0]['upload_time']; ?><br/>
                            檔案下載：<span class="file_link" onClick="window.open('<?php echo $link.$jppa[0]['record_id']; ?>', '論文檔案', 'width=250,height=250,location=no,toolbar=no');">點此開始下載</span>
                            <br/>
                            <span style="vertical-align:top;">備&emsp;&emsp;註：</span>
                            <span><textarea disabled style="resize:none; margin-top:5px; width:400px; height:100px; background-color:#F5F5F5;"><?php echo $jppa[0]['description']; ?></textarea></span>
                        </div>
                    <?php endif;
            break;
        }
    }
    # end if

    // file upload block
    if ( $upload_func ) {
        ?>
        <br/><br/>
            <div class="file_upload_block">
                <div class="block_title">
                    <input type="checkbox" name="bln_uf" class="enable_file_upload" />
                    上傳新檔案
                </div>
                <div class="block_main func_disabled">
                    選擇檔案：
                    <input name="new_upload_file" type="file" class="select_upload_file" disabled />
                    <br/>
                    <span style="font-size:12px; color:#888;">(檔案格式限制：*.rar、*.zip)</span>
                    <br/>
                    <textarea class="file_description" name="file_description" style="width:600px; height:100px; text-align:left; resize:none;" disabled>(在此寫下檔案備註)</textarea>
                    <br/>
                    <span class="tip" style="font-size:12px;">※上傳檔案後，系統會將檔案重新命名(命名格式：論文ID_年月日_時分秒.副檔名).</span>
                    <br/>
                </div>
            </div>
            <script type="text/javascript" src="<?php echo ROOT_URL.'/web/js/file_upload_check.js'; ?>"></script>
        <?php
    }
}


/* [ Template ]
pfcr_file_uploader();
    論文檔案變更紀錄 - 檔案上傳處理
Parameters:
    $file_access: 檔案存取權限 (依照使用的子系統來分)
        0: 管理系統
        1: 審稿系統
        2: 投稿系統

Returns:
    None.

Comments: 請配合 print_pfcr() 使用
*/
function pfcr_file_uploader( $file_access ){
    global $_FILES;
    global $_POST;

    if ( isset($_POST['bln_uf']) ){
        if( strlen($_FILES['new_upload_file']['name']) > 0 ) {
            # Format Length-12 paper_id
            if ( strlen($_POST['paper_id']) < 12 ) {
                $full_id = sql_q(
                    "SELECT language FROM papers WHERE id=? ",
                    array($_POST['paper_id'])
                );
                $_POST['paper_id'] = $full_id[0]['language'].'-'.$_POST['paper_id'];
            }

            if ( false === multi_upload(
                    $_POST['paper_id'], 
                    'new_upload_file',
                    0,
                    ( !strcmp($_POST['file_description'], '(在此寫下檔案備註)') ? '' : $_POST['file_description'] )
                )) {
                    throw new Exception('Function \'pfcr_file_uploader\' has been error at function/paper_files.php line 637.');
            }

            // 投稿者上傳文件後，不開放投稿者上傳其他檔案
            if ( $file_access == 2 ) {
                $sql = "UPDATE papers SET smu_edit_enable='0' WHERE id=? ";
                $array = array( get_sys_pid($_POST['paper_id']) );
                sql_e($sql, $array);
            }

            return true;
        } else {
            echo 'Not have file uploaded.';
            return false;
        }
    } else {
        return true;
    }

}


/* [ Template ]
pfcr_file_deleter();
    論文檔案變更紀錄 - 檔案刪除處理
Parameters:
    None.

Returns:
    None.

Comments: 請配合 print_pfcr() 使用
*/
function pfcr_file_deleter(){
    global $_POST;

    if ( isset($_POST['file_delete']) && (count($_POST['file_delete']) > 0) ) {
        /*
        if ( ! check_file_pms( $_POST['paper_id'] ) )
            return false;
        */

        $str = acts( $_POST['file_delete'], 1 );
        $sql = "SELECT file_name FROM pfc_record WHERE record_id IN(".$str.")";
        $delete_files = array();
        $delete_files_tmp = sql_q( $sql, array() );
        # convert
        foreach ($delete_files_tmp as $key => $value)
            $delete_files[] = $value['file_name'];

        return file_delete( $_POST['file_delete'], $delete_files );
    } else {
        return true;
    }
}


function fa_controller( $record_id, $pms_stat ) {
    $sql = "UPDATE pfc_record SET ";
    $array = array();
    foreach ($pms_stat as $key => $value) {
        $sql .= "fa_".$key."=?,";
        $array[] = $value;
    }
    $sql = substr($sql, 0, strlen($sql)-1)." WHERE record_id=? ";
    $array[] = $record_id;

    return sql_e($sql, $array);
}

function pfcr_fa_controller() {
    global $_POST;
    $sql = "SELECT record_id FROM pfc_record WHERE paper_id=? ";
    $record_id_arr = sql_q($sql, array( get_sys_pid($_POST['paper_id']) ));

    # foreach ($_POST['fa_stat'] as $key => $value) {
    foreach ($record_id_arr as $key => $value) {
        $array = array(
            'submit_user' => 0,
            'reviewer_1' => 0,
            'reviewer_2' => 0,
            'reviewer_3' => 0
        );
        
        if ( isset($_POST['fa_stat'][$value['record_id']]) ) {
            foreach ($_POST['fa_stat'][$value['record_id']] as $value_2)
                $array[$value_2] = 1;
        }

        fa_controller( $value['record_id'], $array );
    }
}

/* [ Template ]
fa_updater( $type );
    file access update
Parameters:
    $type : 類型
        0:file_access_smu，投稿者
        1:file_access_rev，審稿委員

Returns:
    None.
*/
function fa_updater( $type ) {
    global $_POST;

    if ( !isset($_POST['pfcr_id_list']) )
        return true;

    switch ( $type ) {
        case 0:
            $col_name = 'file_access_smu';
            $arr_key_name = 'fa_smu_stat';
        break;
        
        case 1:
            $col_name = 'file_access_rev';
            $arr_key_name = 'fa_rev_stat';
        break;
    }


    # Access deny.
    $pfcr_arr = explode(',', $_POST['pfcr_id_list']);
    array_pop($pfcr_arr);

    # 檔案權限全關時不進行 set 1
    if ( isset($_POST[$arr_key_name]) ) {
        foreach ($_POST[$arr_key_name] as $key => $value)
            unset( $pfcr_arr[ array_search($value, $pfcr_arr) ] );
    } else {
        $_POST[$arr_key_name] = array();
    }

    $str_set_0 = acts($pfcr_arr, 1);
    $str_set_1 = acts($_POST[$arr_key_name], 1);

    # 全開
    return (
        ( strlen($str_set_0) ? sql_e( 'UPDATE pfc_record SET '.$col_name.'=? WHERE record_id IN('.$str_set_0.') ', array(0) ) : true ) &&
        ( strlen($str_set_1) ? sql_e( 'UPDATE pfc_record SET '.$col_name.'=? WHERE record_id IN('.$str_set_1.') ', array(1) ) : true )
    );
}


/* [ Template ]
pfcr_fa_updater();
    file access update

Parameters:
    None.

Returns:
    None.

Comments : print_pfcr()，mode 2 專用
*/
function pfcr_fa_updater() {
    return ( fa_updater(0) && fa_updater(1) );
}


/* 論文檔案確認
Params:
    $pid: papers.id
Returns:
    Boolean
*/
function paper_file_check( $pid ){
    $pid = get_sys_pid( $pid );
    $stat_1 = !( isset($_POST['paper_file_check']) && $_POST['paper_file_check'] );
    $stat_2 = !( isset($_POST['smu_edit_enable'])  && $_POST['smu_edit_enable'] );
    $stat_3 = !( isset($_POST['craa_upload'])      && $_POST['craa_upload'] );

    // 無須修改時停止後續動作
    if ( !($stat_1 || $stat_2 || $stat_3) )
        return true;

    $paper_file_check = isset($_POST['paper_file_check']) ? 1 : 0;
    $smu_edit_enable  = isset($_POST['smu_edit_enable'])  ? 1 : 0;
    $craa_upload      = isset($_POST['craa_upload'])      ? 1 : 0;

    $set_status = "";
    if ( $smu_edit_enable ) {
        $get_status = sql_q("SELECT status FROM papers WHERE id=?", array($pid));
        $set_status = ( $get_status[0]['status']=='3' ? ",status='7'" : "" );

        // E-mail notification
        $paper_data  = get_paper_inf( $_POST['paper_id'] );
        $user_data   = get_user_data($paper_data['submit_user']);
        $mail_params = array(
            'uid' => $user_data['id'],
            'pid' => $paper_data['id']
        );
        mail_queue_add( $user_data['email'], 'proofreading_remind', $mail_params);
    }

    $sql = "UPDATE papers SET "
            .( $stat_1 ? "paper_file_check='".$paper_file_check."'" : '' )
            .( (($stat_1 && $stat_2) || ($stat_1 && $stat_3)) ? ', ' : '' )
            .( $stat_2 ? "smu_edit_enable='".$smu_edit_enable."'" : '' )
            .( ($stat_2 && $stat_3) ? ', ' : '' )
            .( $stat_3 ? "craa_upload='".$craa_upload."'" : '' )
            .$set_status
        ." WHERE id=? ";

    return sql_e( $sql, array($pid) );
}



