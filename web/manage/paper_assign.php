<?php
    require_once('../../loader.php');
    if ( !check_login_status(false) ) {
        echo "登入時效逾時，請關閉視窗後重新登入。<br/>";
        echo "<button onclick=\" window.parent.$('div#dialog').dialog('close'); \">關閉視窗</button>";
        exit;
    }

    // cancel_assign($_GET['id'], $_GET['number']); exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>論文分派::論文分派系統 - 樹德科技大學學報</title>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?=JS_URL; ?>/js_spare/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#form .rev_display').click(function(){
                if ( $(this).attr('value') == 0 )
                    $(this).siblings('#rev_list').children(".nrmd_rev").hide().attr('disabled', true).siblings('option:first').attr('selected', true);
                    //$('#form #rev_list .nrmd_rev').show();
                else
                    $(this).siblings('#rev_list').children(".nrmd_rev").show().attr('disabled', false).siblings('option:first').attr('selected', true);
            });
            
            $('.close_window').click(function(){
                window.parent.location.reload();
                window.parent.$('div#dialog').dialog('close');
            });
        });
    </script>
    <link rel="stylesheet" type="text/css" href="general.css" />
    <style type="text/css">
        body {
            padding: 10px;
            background-color: #DEF;
        }

        .nrmd_rev {
            display: none;
        }
    </style>
</head>
<body>
    <?php
        //check form submit action
        if( isset($_GET['submit']) ) {
            $msg = array(
                "<span style=\"color:red;\">操作成功!</span>",
                "<span style=\"color:red;\">出現未知錯誤，請聯絡系統管理員!</span>"
            );
            $action = 1;
            if ( $_GET['rid'] == -1 ) { // Cancel assign.
                if( cancel_assign($_GET['id'], $_GET['number']) ) 
                    $action = 0;
            } else if ( $_GET['rid'] != 0 ) { // First assign.
                if ( paper_assign( $_GET['id'], $_GET['rid'], $_GET['number'], $_GET['category'] ) ) {
                    $action = 0;

                    // E-mail notification
                    $user_data      = get_user_data( $_GET['rid'] );
                    $mail_params = array(
                        'uid' => $user_data['id'],
                        'pid' => $_GET['id']
                    );
                    mail_queue_add( $user_data['email'], 'review_invite', $mail_params);
                } else {
                    // make_log('The paper ' . $_GET['id'] . ' assign again is fail.');
                    throw new Exception('The paper ' . $_GET['id'] . ' assign again is fail.');
                }
            }

            echo $msg[ $action ];
        } else if ( isset($_GET['assign_again']) ) { // Assign again.
            if ( paper_second_assign( $_GET['id'] ) ) {
                // E-mail notification
                $paper_data = get_paper_inf($_GET['id']);
                $user_data  = get_user_data($paper_data['submit_user']);
                $mail_params['uid'] = $user_data['id'];
                $mail_params['pid'] = $paper_data['id'];
                mail_queue_add( $user_data['email'], 'review_assign_again', $mail_params);
            } else {
                // make_log('The paper ' . $_GET['id'] . ' assign again is fail.');
                throw new Exception('The paper ' . $_GET['id'] . ' assign again is fail.');
            }
        }

        $review_data = sql_q("SELECT * FROM papers WHERE id=?", array( $_GET['id'] ) );
        $review_data = $review_data[0];

        $review_data = paper_data_id_convert( $review_data );
        // $all_reviewer = sql_q("SELECT rid, name, assign_category FROM users WHERE rid>0", array() );
        $all_reviewer = sql_q("SELECT id as rid, name, assign_category FROM users WHERE gid IN (".get_user_group_code(1).")", array() );

        //format reviewer data and compute every reviewer assigned quantity.
        $reviewer = array();
        foreach ($all_reviewer as $key => $value) {
            $rid = $value['rid'];
            //assigned quantity
            $asg_qnt = sql_q("SELECT COUNT('quantity') FROM review_record WHERE rid=? AND status IN (2,6) ", array($rid) );
            $reviewer[$rid] = $value;
            $reviewer[$rid]['asg_qnt'] = $asg_qnt[0]["COUNT('quantity')"];
        }

        //current reviewer
        $cur_rev_id = $review_data[ 'reviewer_'.$_GET['number'] ];
        $cur_rev_data = array();
        $rev_rco = array();
        if ( $cur_rev_id != 0 ){
            $cur_rev_data = $reviewer[ $cur_rev_id ];
            $get_rev_rco = sql_q("SELECT * FROM review_record WHERE paper_id=? AND rid=?", array( substr($review_data['id'], 2), $cur_rev_data['rid']) );
            $rev_rco = $get_rev_rco[0];
        } else {
            $cur_rev_data = array(
                'rid' => "無",
                'name' => "無"
            );
        }

        //依照被分派論文數由小到大排序
        function compare( $ar_1, $ar_2 ) {
            if( $ar_1['asg_qnt'] < $ar_2['asg_qnt'] )
                return 1;
            else if( $ar_1['asg_qnt'] > $ar_2['asg_qnt'] )
                return -1;
        }
        uasort($reviewer, 'compare');
    ?>
    <form id="form" action="">
        <div>
            論文ID：<?=$review_data['id']; ?> <br/>
            審稿委員編號：<?=dop($_GET['number']); ?> <br/>
            審稿狀態：
            <?php
                $status_type = array("未分派", "邀稿中", "審稿中", "審稿完成", "<span style=\"color:red;\">拒絕審稿</span>", "<span style=\"color:red;\">必須分派</span>", "審稿中(第二階段)", "等待第二階段審稿");
                $status_code = $review_data['review_status_'.$_GET['number'] ];
                echo $status_type[$status_code]."<br/>";

                if ( $status_code != 0 ) {
                    echo "審稿委員ID：".$cur_rev_data['rid']."<br/>";
                    echo "審稿委員姓名：".$cur_rev_data['name']."<br/>";
                }

                if ( $status_code == 4 )
                    echo "婉拒理由：<br/><textarea disabled=\"disabled\" style=\"width:250px; height:100px;\">".dop( $rev_rco['deny_reason'] )."</textarea><br/>";
            ?>
            
            <?php
                if ( $_GET['number']==3 && $status_code!=5  ) {
                    echo '此論文目前無需指派第三位審稿委員。<br/><br/>';
                } else if ( $status_code == 7 && $review_data['status']!=5 ) {
                    echo '等待其他審稿委員完成審稿中。<br/><br/>';
                } else if ( !$review_data['paper_file_check'] && ($status_code != 3) ) {
                    echo '論文檔案尚未確認，請至論文資料管理檢視該論文檔案並勾選「論文資料與檔案均已確認」後，才可進行論文分派。<br/><br/>';
                } else {
                    $result_arr = array('無效的審查結果', '推薦刊登', '修改後刊登', '修改後再審', '不推薦');
                    switch ( $status_code ) {
                        case 1:
                        case 2:
                        case 6:
                            echo '論文已分派。<br/><br/>';
                        break;
                        
                        case 7: ?>
                                <br />
                                ※確認投稿者修改後的論文內容無誤後，請按下此按鈕進行複審。<br/>
                                <input type="hidden" name="id" value="<?=substr($review_data['id'], 2); ?>" />
                                <input type="hidden" name="number" value="<?=dop($_GET['number']); ?>" />
                                <input type="hidden" name="category" value="<?=$review_data['category']; ?>" />
                                <input type="submit" name="assign_again" value="開始複審" />&emsp;&emsp;
                        <?php break;

                        case 3:
                        case 5:
                            echo '審稿結果：' . $result_arr[ $review_data['review_result_'.$_GET['number']] ] . '<br /><br />';
                        break;

                        default:
            ?>
            <br/>
            指派審稿委員：<br/>
            <input type="radio" name="rev_display" class="rev_display" value="0" checked />&nbsp;顯示推薦的審稿委員<br/>
            <input type="radio" name="rev_display" class="rev_display" value="1" />&nbsp;顯示所有的審稿委員(不含目前的審稿委員)<br/>
            <select id="rev_list" name="rid">
                <option value="0">請選擇</option>
                <?php
                    if ( $cur_rev_id != 0 )
                        ?> <option value="-1">清除分派記錄</option> <?php

                    # 移除已被分派審查此論文的審稿委員，避免重覆分派
                    unset( $reviewer[ $review_data['reviewer_1'] ] );
                    unset( $reviewer[ $review_data['reviewer_2'] ] );
                    unset( $reviewer[ $review_data['reviewer_3'] ] );
                    foreach ($reviewer as $key => $value) {
                        echo "<option value=\"".$value['rid']."\" ";
                        if ( $value['assign_category'] != $review_data['category'] )
                            echo "class=\"nrmd_rev\" ";
                        echo ">".$value['name']." (".$value['asg_qnt'].")</option>";
                    }
                ?>
            </select>
            <br/>
            <span style="font-size:13px; color:#555;">
                <br/>※論文分派後，系統將自動關閉投稿者論文修改功能，並將論文資料與檔案<br/>&nbsp;&nbsp;&nbsp;確認狀態變更為「已確認」。
                <br/>※欲清空此記錄，請選擇「清除分派記錄」(無分派時無此選項)。
                <br/>※若審稿委員拒審，亦選擇此項目清空記錄。
            </span>
        </div><br/>
        <input type="hidden" name="id" value="<?=substr($review_data['id'], 2); ?>" />
        <input type="hidden" name="number" value="<?=dop($_GET['number']); ?>" />
        <input type="hidden" name="category" value="<?=$review_data['category']; ?>" />
        <input type="hidden" name="submit" />
        <button type="submit" class="btn"/>送出</button>&nbsp;&nbsp;
        <?php
                    break;
                } # end switch
            } # end if
        ?>

        <button type="button" class="btn close_window" />關閉</button>
    </form>
</body>
</html>
<?php func_queue_handler_start(); ?>
<?php mail_queue_handler_start(); ?>