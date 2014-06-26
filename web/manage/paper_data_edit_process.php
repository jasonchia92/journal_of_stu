<?php
    # format to sys_id
    $pid = get_sys_pid($_GET['pid']);

    # 刪除作者資料
    if ( isset($_POST['delete_authors']) && count($_POST['delete_authors']) ) {
        # 尋找 change_authors 的重複值，避免與change_authors 衝突
        foreach ($_POST['delete_authors'] as $key => $value)
            $_POST['change_authors'] = str_replace($value.',', '', $_POST['change_authors']);
        
        sql_e( "DELETE FROM authors_data WHERE id IN (".acts($_POST['delete_authors'], 1).") ", array() );
    }

    # 作者資料變更過時 update
    if ( strlen($_POST['change_authors']) ) {
        $queue = explode(',', $_POST['change_authors']);
        unset( $queue[ count($queue)-1 ] );
        foreach ($queue as $key => $value) {
            $_POST['authors_data'][$value]['id'] = $value;
            $sql = "UPDATE authors_data SET 
                ch_name=?,
                en_name=?,
                ch_titles=?,
                en_titles=?,
                ch_serve_unit=?,
                en_serve_unit=?,
                phone=?,
                email=?
                WHERE id=?
            ";

            # array key rebind
            $array = array();
            foreach ($_POST['authors_data'][$value] as $key => $value)
                $array[] = $value;

            sql_e( $sql, $array );
        }
    }

    #無資料時不進行動作
    if( isset($_POST['new_ch_name']) ){
        # 跑迴圈寫入新加入的作者資料
        for( $i=0 ; $i<count($_POST['new_ch_name']) ; $i++ ){
            $bln = add_author_data(
                $pid,
                0, # 其他作者
                $_POST['new_ch_name'][$i],
                $_POST['new_en_name'][$i],
                $_POST['new_ch_serve_unit'][$i],
                $_POST['new_en_serve_unit'][$i],
                $_POST['new_ch_titles'][$i],
                $_POST['new_en_titles'][$i],
                $_POST['new_email'][$i],
                $_POST['new_phone'][$i]
            );

            if ( false === $bln ) throw new Exception('Create authors data has been error at web/manage/paper_data_edit_process.php line 42.');
            sleep(0.5);
        }
    }


    $sql = "UPDATE papers SET
        ch_title=?,
        en_title=?,
        ch_summary=?,
        en_summary=?,
        keywords=?,
        category=?
        WHERE id=?";
    $array = array(
        $_POST['ch_title'], 
        $_POST['en_title'], 
        $_POST['ch_summary'], 
        $_POST['en_summary'], 
        $_POST['keywords'], 
        $_POST['category'],
        $pid
    );

    # pfcr template proccess
    paper_file_check( $pid );
    pfcr_file_uploader(0);
    pfcr_file_deleter();
    pfcr_fa_controller();

    # 結果儲存於 $operationResult
    $operationResult = sql_e($sql, $array);
?>
