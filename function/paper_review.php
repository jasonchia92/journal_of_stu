<?php
/* 未使用
get_pa_list( $type, $page_num, $slice );
    取得論文分派資料(一頁)
傳入參數：
    $category：指定類別，int
        0:所有記錄.
        1:管理.
        2:資訊.
        3:設計.
        4:幼保.性學.外文. (附加review_recode欄位紀錄)
        5:通識教育.
    $page_num：指定頁數，int
    $slice：資料需求筆數，int
傳出參數：
    二維陣列
注意：需引入papers_operate.php，此function才可正常執行
*/
function get_pa_list( $category, $page_num, $slice ){
    $result = array();
    if( $type == 0 )
        $result = sql_q( "SELECT * FROM papers LIMIT ".(($page_num-1)*$slice).", ".$slice, array() );
    else
        $result = sql_q( "SELECT * FROM papers WHERE category=? LIMIT ".(($page_num-1)*$slice).", ".$slice, array( $category ) );

    return paper_data_id_convert( $result );
}


/*
record_add( $paper_id, $expired_date );
    新增評閱紀錄
傳入參數：
    $paper_id：論文id，string
    $expired_date：評閱到期日，date
傳出參數：
    無
*/
function record_add( $paper_id, $expired_date ){
    return sql_e("INSERT INTO review_record(paper_id, expired_date) VALUES(?, ?)"
        , array($paper_id, $expired_date));
}


/*
paper_assign( $paper_id, $reviewer_id );
    論文分派
傳入參數：
    $paper_id：論文id，string (papers.id)
    $rid：審稿委員，int (users.id)
傳出參數：
    無

note:
    status field: 0:未分配. 1:邀請中. 2:評閱中. 3:評閱完成. 4:拒絕評閱.
*/
function paper_assign( $paper_id, $rid, $review_number, $category ){
    # Delete old data
    $old_data = sql_q("SELECT id FROM review_record WHERE paper_id=? AND review_number=?"
        , array($paper_id, $review_number) );
    if ( count( $old_data ) > 0 )
        sql_e("DELETE FROM review_record WHERE paper_id=? AND review_number=?", array( $paper_id, $review_number ) );

    # Insert new record
    $sql = "INSERT INTO review_record(
            paper_id,
            rid,
            category,
            review_number,
            status
        ) VALUES(?,?,?,?,?)";
    $array = array(
        $paper_id,
        $rid,
        $category,
        $review_number,
        1
    );
    $action_1 = sql_e( $sql, $array );

    # Update papers record
    $paper_status = ( $review_number==3 ? 6 : 1 );
    $sql = "UPDATE papers SET 
            review_status_{$review_number}='1',
            reviewer_{$review_number}=?, 
            status=?,
            paper_file_check='1',
            smu_edit_enable='0'
        WHERE id=? ";
    $array = array(
        $rid,
        $paper_status,
        $paper_id
    );
    $action_2 = sql_e( $sql, $array );

    return ($action_1 && $action_2);
}


/*
paper_second_assign( $paper_id );
    論文複審
傳入參數：
    $paper_id
傳出參數：
    boolean
*/
function paper_second_assign( $paper_id ){
    $paper_id = get_sys_pid( $paper_id );
    # 取得分派目標的評閱記錄id
    $sql = "SELECT
            b.id,
            b.review_number
        FROM papers AS a LEFT JOIN review_record AS b ON
            a.id=b.paper_id
        WHERE
            a.id=?
            AND b.result='3'
            AND b.locked='1' ";
    $rev_rec = sql_q( $sql, array($paper_id) );
    
    if ( count($rev_rec) )
        $rev_rec = $rev_rec[0];
    else
        return false;
    
    # re assign
    $sql_1 = "UPDATE review_record SET
#           score_option='000000000000',
#           result='0',
            locked='0',
            status='6'
        WHERE id=? ";
    $action_1 = sql_e( $sql_1, array( $rev_rec['id'] ) );

    # sync change to papers
    $sql_2 = "UPDATE papers SET
            review_status_".$rev_rec['review_number']."='6',
            review_result_".$rev_rec['review_number']."='0',
            paper_file_check='1',
            smu_edit_enable='0',
            status='6'
        WHERE id=? ";
    $action_2 = sql_e( $sql_2, array($paper_id) );

    return ($action_1 && $action_2);
}


function cancel_assign( $paper_id, $review_number ){
    $action_1 = sql_e("DELETE FROM review_record WHERE paper_id=? AND review_number=?", array($paper_id, $review_number) );
    $action_2 = sql_e("UPDATE papers SET 
        review_status_{$review_number}='0', 
        reviewer_{$review_number}='0' 
        WHERE id = ?"
        , array($paper_id) );

    update_final_result( $paper_id );

    return ($action_1 && $action_2);
}



/*
update_request( $id, $request);
    更新邀請狀態
傳入參數：
    $id：review_record.id，int
    $request：評閱請求結果，int 
        0:拒絕
        1:接受
    $deny_reason：拒絕理由，string (Default：空字串)
傳出參數：
    無
*/
function update_request( $id, $request, $deny_reason="" ){
    $papers_update = sql_q("SELECT paper_id, review_number FROM review_record WHERE id=? ", array($id) );
    $paper_inf = get_paper_inf( $papers_update[0]['paper_id'] );
    $paper_status = (int)$paper_inf['status'];
    if ( $paper_status == 0 )
        $paper_status = 1;
    else if ( $paper_status == 5 )
        $paper_status = 6;

    $action1 = sql_e(
        "UPDATE review_record SET
            status=?,
            deny_reason=?
        WHERE id=? ",
        array(
            ( $request ? 2 : 4 ),
            $deny_reason,
            $id
        )
    );
    $action2 = sql_e( "UPDATE papers SET 
            review_status_".$papers_update[0]['review_number']."=?,
            status=?
        WHERE id=? ",
        array(
            ( $request ? 2 : 4 ),
            $paper_status,
            $papers_update[0]['paper_id']
        )
    );

    return ($action1 && $action2);
}


# 未使用
function checked_deny_msg( $id ){
    $sql_array = array($id);
    $sql_1 = sql_q("SELECT paper_id ,review_number FROM review_record WHERE id=?");
    $papers_update = sql_q($sql_1, $sql_array);

    $sql_2 = "UPDATE papers SET 
            review_status_".$papers_update[0]['review_number']."=?,
            review_id_".$papers_update[0]['review_number']."=?
            WHERE paper_id=? ";
    $action1 = sql_e( $sql_2, array( $papers_update[0]['paper_id'] ) );

    $sql_3 = "UPDATE review_record SET review_number='0', locked='1' WHERE id=?";
    $action2 = sql_e($sql_3, $sql_array);

    return ($action1 && $action2);
}


/* 未使用
get_deny_reason( $id );
    取得拒絕理由資料
傳入參數：
    $id：review_record表內id欄位資訊，int
傳出參數：
    string
*/
function get_deny_reason( $id ){
    $result = sql_q("SELECT deny_reason FROM review_record WHERE id=?", array($id));
    return $result[0]['deny_reason'];
}


/*
execute_review( $id, $score_option, $opinion, $result );
    進行評閱
傳入參數：
    $id：review_record表內id欄位資訊，int
    $score_option：評閱選項，string (Length:12)
    $opinion：意見欄資料，string
    $result：評閱結果，int
        0:評閱中.
        1:推薦刊登.
        2:修正後刊登.
        3:修正後再審.
        4:不推薦.
傳出參數：
    無
*/
function execute_review( $id, $score_option, $opinion, $result ){
    $sql = "UPDATE review_record SET
            score_option=?,
            opinion=?,
            result=?
        WHERE id=? ";
    $array = array(
        $score_option,
        $opinion,
        $result,
        $id
    );
    
    return sql_e( $sql, $array );
}


/*
get_review_record( $id );
    取得評閱紀錄
傳入參數：
    $id：review_record表內id欄位資訊，int
    $query_col：查詢依據
        0:id (Default，回傳單筆資料)
        1:paper_id + rid
        2:paper_id (回傳所有資料)
傳出參數：
    評閱紀錄
        $query_col=0，一維陣列
        $query_col=1，二維陣列
*/
function get_review_record( $id, $query_col=0 ){
    $sql = "";
    $array = array( $id );
    switch ( $query_col ) {
        case 0:
            $sql = "SELECT * FROM review_record WHERE id=? ";
        break;

        case 1:
            $auth_data = get_auth_data();
            $sql = "SELECT * FROM review_record WHERE paper_id=? AND rid=? ";
            $array[] = $auth_data['id'];
        break;

        case 2:
            $sql = "SELECT * FROM review_record WHERE paper_id=? ";
        break;
    }

    $result = sql_q( $sql, $array );
    if ( $query_col != 2 )
        $result = $result[0];

    return $result;
}


/*
record_lock( $id, $lock );
    紀錄鎖定
傳入參數：
    $id：review_record表內id欄位資訊，int
    $lock：是否鎖定，int
        0:不鎖定.
        1:鎖定.
傳出參數：
    無
*/
function record_lock( $id, $lock=1 ){
    # 取得整列資料
    $result = sql_q("SELECT paper_id, review_number, result FROM review_record WHERE id=?", array($id) );
    $paper_id = $result[0]['paper_id'];
    $rev_num = $result[0]['review_number'];
    $rev_rs = $result[0]['result'];
    # 若審稿結果為修改後再審時，status = 5
    $rev_stat = ( $rev_rs==3 ? 7 : 3 );

    # 鎖定 review_record 記錄
    $sql = "UPDATE review_record SET
            locked=?,
            status=".$rev_stat."
        WHERE id=? ";
    $array = array(
        $lock,
        $id
    );
    $action_1 = sql_e( $sql, $array );

    # sync to papers
    $sql = "UPDATE papers SET
            review_status_".$rev_num."=".$rev_stat.",
            review_result_".$rev_num."=".$rev_rs."
        WHERE id=? ";
    $action_2 = sql_e( $sql, array($paper_id) );
    $action_3 = update_final_result( $paper_id );

    return ($action_1 && $action_2 && $action_3 );
}


/*
update_final_result( $paper_id );
    更新final_result
傳入參數：
    $paper_id
傳出參數：
    無
備註：cancel_assign() 與 record_lock() 時連動
*/
function update_final_result( $paper_id ){
    $paper_id = get_sys_pid( $paper_id );
    
    $review_record = sql_q("SELECT
            reviewer_1,
            reviewer_2,
            reviewer_3,
            review_result_1,
            review_result_2,
            review_result_3,
            status
        FROM papers WHERE id=? ", array( $paper_id ) );

    $r1 = $review_record[0]['review_result_1'];
    $r2 = $review_record[0]['review_result_2'];
    $r3 = $review_record[0]['review_result_3'];
    $status = $review_record[0]['status'];

    $final_result = 0;
    $auto_set = array();
    if ( $r1 == 0 || $r2 == 0 ) {
        # reviewer_1與reviewer_2需皆審稿完成才可變更papers.status
        return true;
    } else if ( $r3 != 0 ) {
        # 若有reviewer_3，直接套用reviewer_3的結果
        $final_result = $r3 <= 2 ? 1 : 4;
        $auto_set[] = 3;
    } else { 
        if ( $status == 6 ) {
            # 論文在複審階段時，若複審委員再次點選修改後再審，則直接轉換結果為不推薦
            if ( $r1 == 3 ) {
                $r1 = 4;
                $auto_set[] = 1;
            }

            if ( $r2 == 3 ) {
                $r2 = 4;
                $auto_set[] = 2;
            }
        } 

        # 審稿結果表格
        $table = array(
            array( 1, 2, 3, 5 ),
            array( 2, 2, 3, 5 ),
            array( 3, 3, 4, 4 ),
            array( 5, 5, 4, 4 )
        );
        
        $final_result = $table[ $r1-1 ][ $r2-1 ];
    }

    # 審稿狀態7例外
    if ( $r1==3 && $r2==3 ) {
        $auto_set[] = 1;
        $auto_set[] = 2;
    }

    # 第二階段複審後，若複審結果為不推薦，則直接退稿
    if ( $status == 6 && $final_result == 5 )
        $final_result = 4;

    # 審稿結果出爐時，將所有審稿紀錄鎖定狀態
    switch ( count($auto_set) ) {
        case 1:
            $sql_1 = "UPDATE review_record SET status='3' WHERE paper_id=? AND review_number=? ";
            $sql_2 = "UPDATE papers SET review_status_".$auto_set[0]."='3' WHERE id=? ";
            sql_e( $sql_1, array($paper_id, $auto_set[0]) );
            sql_e( $sql_2, array($paper_id) );
        break;

        case 2:
            $sql_1 = "UPDATE review_record SET
                    status='3'
                WHERE paper_id=?
                    AND review_number IN (".$auto_set[0].", ".$auto_set[1].") ";
            $sql_2 = "UPDATE papers SET
                    review_status_".$auto_set[0]."='3',
                    review_status_".$auto_set[1]."='3'
                WHERE id=? ";
            sql_e( $sql_1, array($paper_id) );
            sql_e( $sql_2, array($paper_id) );
        break;

        case 3:
            $sql_1 = "UPDATE review_record SET
                    status='3'
                WHERE paper_id=?
                    AND review_number IN (".$auto_set[0].", ".$auto_set[1].", ".$auto_set[2].") ";
            $sql_2 = "UPDATE papers SET
                    review_status_".$auto_set[0]."='3',
                    review_status_".$auto_set[1]."='3',
                    review_status_".$auto_set[2]."='3'
                WHERE id=? ";
            sql_e( $sql_1, array($paper_id) );
            sql_e( $sql_2, array($paper_id) );
        break;

        default:
        break;
    }

    // Set E-mail notification params.
    $paper_data = get_paper_inf( $paper_id );
    $user_data  = get_user_data($paper_data['submit_user']);
    $mail_params['uid'] = $user_data['id'];
    $mail_params['pid'] = $paper_data['id'];

    switch ( $final_result ) {
        # 審稿完畢，錄取
        case 1:
        case 2:
            $mail_params['review_result'] = '錄取';
            mail_queue_add( $user_data['email'], 'review_result_notification', $mail_params);
            return sql_e( "UPDATE papers SET
                    status='3',
                    final_result='1'
                WHERE id=?", array($paper_id) );
        break;

        # 第二階段審稿，修正後再審
        case 3:
            return sql_e( "UPDATE papers SET
                    status='5',
                    smu_edit_enable='1'
                WHERE id=? ", array($paper_id) );
        break;

        # 審稿完畢，不錄取
        case 4:
            $mail_params['review_result'] = '不錄取';
            mail_queue_add( $user_data['email'], 'review_result_notification', $mail_params);
            return sql_e( "UPDATE papers SET
                    status='8',
                    final_result='2'
                WHERE id=?", array($paper_id) );
        break;

        # 第二階段審稿，並指派reviewer_3
        case 5:
            return sql_e( "UPDATE papers SET
                status='5',
                review_status_3='5'
            WHERE id=?", array($paper_id) );
        break;
    }

    return true;
}
