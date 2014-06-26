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
        # pfrc template parameter - paper_id
        $_POST['paper_id'] = $_GET['pid'];
        # pfcr template proccess
        $sql = "UPDATE papers SET status='8' WHERE id=?";
        $operationResult = (
            paper_file_check($_GET['pid']) &&
            pfcr_file_uploader(0) &&
            pfcr_file_deleter() &&
            pfcr_fa_updater() &&
            ( isset($_POST['finish_pfd']) ? sql_e($sql, array(get_sys_pid($_GET['pid']))) : true )
        );

        $haveOperation = true;

        if ( isset($_POST['finish_pfd']) ) :
            function alert_msg() { ?>
                <script type="text/javascript">
                    $('document').ready(function(){
                        alert('操作成功，此論文已校稿完成，欲檢視此論文內容請至「可刊登論文」頁面。');
                        window.parent.location.reload();
                        close_window();
                    });
                </script>
            <?php }

            func_queue_add('alert_msg');

            // E-mail notification
            $paper_data     = get_paper_inf( $_GET['pid'] );
            $user_data      = get_user_data( $paper_data['submit_user'] );
            $mail_params['pid'] = $paper_data['id'];
            $mail_params['uid'] = $user_data['id'];
            mail_queue_add( $user_data['email'], 'proofreading_finished', $mail_params);
        endif;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>編輯論文資料::論文管理系統 - 樹德科技大學學報</title>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="general.css" />
    <link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body{
            font: 100% 微軟正黑體,細明體,新細明體;
            margin: 0;
            padding: 0;
            background-color: #DEF;
        }
        .container {
            margin-left: 10px;
        }

        label.error {
            color: #F00;
            font-size: 12px;
        }
        #packup1,#packup2,#packup3{
            cursor: pointer;
            color: blue;
            display: none;
        }

        #option1,#option2,#option3{
            display: none;
            background: white;
            height: 168px;
            margin-bottom: 400px;
        }


        #view1,#view2,#view3{
            color: white;
            margin-bottom: 3%;
        }

        .authors_block td {
            width: 280px;
            text-align: left;
        }

        table#current_file_list th, table#current_file_list td {
            border: 1px solid #555;
        }
        .parallel{
            -moz-column-count:2;    /* Firefox */
            -webkit-column-count:2; /* Safari 和 Chrome */
            column-count:2;
            width:600px;
            margin-left: 10%;
        }
        #author{
            margin-left: 10%;
        }
        #tab2 h2{
            margin-left: 5%;
        }
        .txtarea{
            display: table-cell;
            background: white;
            margin-left:10%;
            margin-top:2%;
        }

        .title{
            margin-left:75px;
            text-indent:-75px;
            color:green;
        }
        button#view2 ,button#view3 {
            margin-top: -44px;
        }

        div.tab_container {
            margin-bottom: 5px;
        }

        .operation_msg {
            z-index: 10;
            display: none;
            width: 150px;
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

        .information > span {
            display: inline-block;
            padding: 2px;
            margin-right: 20px;
            width: 300px;
        }

        span.paper-data-summary {
            width: 600px;
            margin-bottom: 10px;
        }

        .paper-data-summary > span {
            display: inline-block;
        }

        .paper-data-summary .col-name {
            vertical-align: top;
        }

        .paper-data-summary textarea {
            width: 400px;
            height: 200px;
            resize: none;
        }

        span.col-name {
            display: inline-block;
            width: 80px;
            vertical-align: top;
            margin: 0;
        }

        span.paper_title {
            display: inline-block;
            width: 520px;
            text-align: left;
            overflow: hidden;
            word-break: break-all;
        }
    </style>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/cmxforms.js"></script>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/tab_page.js"></script>
    <script type="text/javascript">
        function close_window(){
            window.parent.$('div#dialog').dialog('close');
        }

        $(document).ready(function(){
            $("form#commentForm").validate();

            $("div.authors_block input").click(function(){
                $("#ae_flag").attr('value', 1);
            });

            $('#view1').click(function(){
                $('#option1').slideToggle();
            });
            $('#view2').click(function(){
                $('#option2').slideToggle();
            });
            $('#view3').click(function(){
                $('#option3').slideToggle();
            });

            $('.select_fu').click(function(){
                $('.enable_file_upload').attr('checked', 1);
                $('.file_upload_block').show();
            });

            $('.select_fp').click(function(){
                $('.enable_file_upload').removeAttr('checked');
                $('.file_upload_block').hide();
            });

            $('form').submit(function(){
                if ( ($('input[name="finish_pfd"]').attr('checked')=='checked') && !confirm('完成校稿後將無法對此論文做任何修改，確定提交變更?') )
                    return false;
            });
        });
    </script>
</head>
<body>
    <div class="container">
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
                    <?php echo $operationResult ? "操作成功!" : "操作失敗，請稍後再嘗試；若持續出現此狀況，請聯絡系統管理者。"; ?>
                </div>
            <?php endif;

            $paper_data = get_paper_inf( $_GET['pid'] );
            $pa_data = get_paper_authors_data( $_GET['pid'], 1 );
            $ea_data = get_paper_authors_data( $_GET['pid'], 0 );
            //過濾多餘維度
            if( count($pa_data) == 0 ){
                $pa_data = array(
                    'ch_name' => "",
                    'en_name' => "",
                    'ch_serve_unit' => "",
                    'en_serve_unit' => "",
                    'ch_titles' => "",
                    'en_titles' => "",
                    'email' => "",
                    'phone' => ""
                );
            }else{
                $pa_data = $pa_data[0];
            }
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="abgne_tab">
                <ul class="tabs">
                    <li><a href="#tab1">論文校稿</a></li>
                    <li><a href="#tab2">論文資料</a></li>
                    <li><a href="#tab3">作者資料</a></li>
                </ul>
                <div class="tab_container">
                    <div id="tab1" class="tab_content">
                        現有檔案：<br/>
                        <?php
                            print_pfcr( $paper_data['id'], 0, 1 );
                        ?>
                    </div>
                    <div id="tab2" class="tab_content">
                        <?php
                            $lang = array("中文", "英文", "其他");
                            $option_name = array("管理", "資訊", "設計", "幼保.外文.性學", "通識教育");
                        ?>
                        <div class='information'>
                            <span>論文ID：<?php echo dop($paper_data['id']); ?></span>
                            <span>語言：<?php echo $lang[$paper_data['id']{0}-1]; ?></span><br/>
                            <span>類別：<?php echo $option_name[ $paper_data['category'] ]; ?></span>
                            <span>提交日期：<?php echo dop($paper_data['submit_date']); ?></span><br/>
                            <span>關鍵字：<?php echo dop($paper_data['keywords']); ?></span><br/>
                            <span class="col-name">中文標題：</span><span class="paper_title"><?php echo dop($paper_data['ch_title']); ?></span><br/>
                            <span class="col-name">英文標題：</span><span class="paper_title"><?php echo dop($paper_data['en_title']); ?></span><br/>
                            <span class="paper-data-summary">
                                <div>
                                <span class="col-name">中文摘要：</span>
                                <span><textarea disabled><?php echo dop($paper_data['ch_summary']); ?></textarea></span>
                                </div>
                            </span><br/>
                            <span class="paper-data-summary">
                                <span class="col-name">英文摘要：</span>
                                <span><textarea disabled><?php echo dop($paper_data['en_summary']); ?></textarea></span><br/>
                            </span><br/>
                        </div>
                    </div>
                    <div id="tab3" class="tab_content authors_block">
                        <div><h3>[主要作者]</h3>
                            <?php print_author_data( $pa_data, 0, '', 1 ); ?>
                        </div>
                        <div><h3>[其他作者]</h3>
                            <?php
                                if ( count($ea_data) > 0 ) {
                                    print_author_data( $ea_data );
                                } else {
                                    echo "此篇論文無其他作者.";
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div style=" text-align:center; ">
                    <input type="checkbox" name="finish_pfd" value="1" style="margin-bottom:5px;" />&nbsp;完成校稿<br/>
                    <input type="submit" name="form_submit" value="提交變更" class="btn btn-primary" />
                    <input type="button" value="關閉視窗" class="btn btn-primary" onclick=" close_window(); " />
                </div>
            </div>
        </form>
    </div>
</body>
<?php func_queue_handler_start(); ?>
<?php mail_queue_handler_start(); ?>
</html>