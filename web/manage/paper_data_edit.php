<?php
    require_once('../../loader.php');
    if ( !check_login_status(false) ) {
        echo "登入時效逾時，請關閉視窗後重新登入。<br/>";
        echo "<button onclick=\" window.parent.$('div#dialog').dialog('close'); \">關閉視窗</button>";
        exit;
    }

    $haveOperation = false;
    $operationResult = true;
    $delete_paper = false;

    if ( isset($_GET['delete_paper']) ) {
        $delete_paper =  paper_delete($_GET['pid']);
        ?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html>
            <head>
                <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
                <script type="text/javascript">
                    $('document').ready(function(){
                        <?php
                        if ( $delete_paper ) {
                            ?>
                                alert('操作成功.');
                                window.parent.location.assign('pm_pd.php');
                                cancel_edit();
                            <?php
                        } else {
                            ?>
                                alert('操作失敗，請稍後再嘗試；若持續出現此狀況，請聯絡系統管理者。');
                            <?php
                        }
                        ?>
                    });
                </script>
            </head>
        <?php
        if ( $delete_paper ) exit;
    } else if ( isset($_POST['form_submit']) ) {
        $haveOperation = true;
        include('paper_data_edit_process.php');
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>編輯論文資料::論文管理系統 - 樹德科技大學學報</title>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/cmxforms.js"></script>
    <script type="text/javascript" src="../js/tab_page.js"></script>
    <script type="text/javascript">
        function file_open( file_link ){
            window.open("../uploads/"+file_link, "論文檔案", 'width=800,height=600,location=no,toolbar=no,status=no,scrollbars=yes');
        }

        function cancel_edit(){
            window.parent.$('#dialog').html('').dialog('close');
        }

        function delete_paper(){
            if ( confirm('論文刪除後將無法復原，確定刪除?') )
                window.location.href = "?pid=<?=dop($_GET['pid']); ?>&delete_paper=1";
            else
                return false;
        }

        $(document).ready(function(){
            $('#commentForm').validate();

            $('#new_btn').click(function(){
                $('#author_add').append('                                                                                               \
                    <div class="else_authors">                                                                                          \
                        <div class="adt_col_left">                                                                                      \
                            <button class="remove_author_block">移除<\/button>                                                          \
                        <\/div>                                                                                                         \
                        <div class="adt_col_right">                                                                                     \
                            <div class="table_border_top"><\/div>                                                                       \
                            <span class="col_name">中文姓名<\/span>                                                                     \
                            <span class="col_data"><input type="text" name="new_ch_name[]" size="32" /><\/span>                         \
                            <span class="col_name">英文姓名</span>                                                                      \
                            <span class="col_data"><input type="text" name="new_en_name[]" size="32" /><\/span><br\/>                   \
                            <span class="col_name">職稱(中文)<\/span>                                                                   \
                            <span class="col_data"><input type="text" name="new_ch_titles[]" size="32" /><\/span>                       \
                            <span class="col_name">職稱(英文)<\/span>                                                                   \
                            <span class="col_data"><input type="text" name="new_en_titles[]" size="32" /><\/span><br\/>                 \
                            <span class="col_name">服務單位(中文)<\/span>                                                               \
                            <span class="col_data"><input type="text" name="new_ch_serve_unit[]" size="32" /><\/span>                   \
                            <span class="col_name">服務單位(英文)<\/span>                                                               \
                            <span class="col_data"><input type="text" name="new_en_serve_unit[]" size="32" /><\/span><br\/>             \
                            <span class="col_name">聯絡電話<\/span>                                                                     \
                            <span class="col_data"><input type="text" name="new_phone[]" class="required number" size="32" /><\/span>   \
                            <span class="col_name">E-mail<\/span>                                                                       \
                            <span class="col_data"><input type="text" name="new_email[]" class="required email" size="32" /><\/span>    \
                        <\/div>                                                                                                         \
                    <\/div>'
                );
                $('.remove_author_block').bind("click", function(){
                    $(this).parent().parent().remove();
                });
            });

            var col_tmp = "";
            $('div.authors_block input').focus(function(){
                col_tmp = $(this).val();
                $(this).blur(function(){
                    // 有修改時將其author_id加入#change_authors，送出變更時會update這筆資料 (primary_author與else_authors皆適用)
                    if( $(this).val() != col_tmp ) {
                        var author_id = $(this).parent().parent().siblings('.adt-author_id').val();
                        var change_authors = $("#change_authors").val();
                        if ( change_authors.search( author_id+',' ) == -1 )
                            $("#change_authors").val( change_authors+author_id+',' );
                    }
                });
            });
        });
    </script>
    <link rel="stylesheet" type="text/css" href="general.css" />
    <link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body {
            padding: 0px;
            background-color: #DEF;
            font: 100% 微軟正黑體,細明體,新細明體;
        }

        .container {
            margin-left: 10px;
        }

        .error {
            color: #F00;
            font-size: 12px;
        }

        .paper_block {
            border: 0px dashed #888;
            border-top-width: 2px;
            padding: 10px;
        }

        .paper_block table th, .paper_block table td {
            text-align: center;
            padding: 0px 5px;
        }

        .authors_block table .align_left {
            text-align: left;
        }

        .else_authors {
            margin-bottom: 10px;
        }


        #current_file_list , #current_file_list td {
            border: 1px solid #555;
        }

        .func_disabled {
            color: #888;
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

        span.col-name {
            display: inline-block;
            width: 100px;
            vertical-align: top;
            margin: 0;
        }

        .pd-input-col {
            width: 600px;
        }

        .pd-summary {
            width: 600px;
            height: 200px;
            padding: 4px 6px;
            resize : none;
        }

        textarea {
            resize : none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            if ( $haveOperation ) {
                ?>
                <script type="text/javascript">
                    $('document').ready(function(){
                        $('.operation_msg').fadeIn(300);
                        setTimeout(function(){
                            $('.operation_msg').fadeOut(300);
                        }, 3000);
                    });
                </script>
                <div class="operation_msg">

                <?=$operationResult ? "操作成功!" : "操作失敗，請稍後再嘗試；若持續出現此狀況，請聯絡系統管理者。"; ?>

                </div>
                <?php
            }

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

        <form id="commentForm" class="cmxform" method="post" action="" enctype="multipart/form-data">
            <div class="abgne_tab">
                <ul class="tabs">
                    <li><a href="#tab1">論文資料</a></li>
                    <li><a href="#tab2">作者資料</a></li>
                    <li><a href="#tab3">稿件檔案</a></li>
                </ul>
            <div class="tab_container">
                <div id="tab1" class="tab_content">
                    ◎ 論文ID：<?=$paper_data['id']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ◎ 語言：
                        <?php
                            $option_name = array("中文", "英文", "其他");
                            echo $option_name[$paper_data['id']{0}-1];
                        ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ◎ 類別：
                    <select name="category" id="category">
                        <?php
                            $option_name = array("1.管理", "2.資訊", "3.設計", "4.幼保.外文.性學", "5.通識教育");
                            for( $i=1 ; $i<=5 ; $i++ ){
                                if( strcmp($paper_data['category'], $i) == 0 )
                                    echo "<option value=\"{$i}\" selected>".$option_name[$i-1]."</option>";
                                else
                                    echo "<option value=\"{$i}\">".$option_name[$i-1]."</option>";
                            }
                        ?>
                    </select><br/><br/>
                    ◎ 中文標題：<input type="text" name="ch_title" class="pd-input-col required" value=<?='"'.dop($paper_data['ch_title']).'"'; ?> /><br/>
                    ◎ 英文標題：<input type="text" name="en_title" class="pd-input-col required" value=<?='"'.dop($paper_data['en_title']).'"'; ?> /><br/>
                    ◎ 關鍵字&nbsp;&nbsp;&nbsp;&nbsp;：<input type="text" name="keywords" class="pd-input-col required" value=<?='"'.dop($paper_data['keywords']).'"'; ?> /><br/>
                    <span class='col-name'>◎ 中文摘要：</span><span><textarea name="ch_summary" class="pd-summary required"><?=dop($paper_data['ch_summary']); ?></textarea><br /><br />
                    <span class='col-name'>◎ 英文摘要：</span><textarea name="en_summary" class="pd-summary required"><?=dop($paper_data['en_summary']); ?></textarea>
                </div>
                <div id="tab2" class="tab_content authors_block">
                    <div><h2>[主要作者]</h2>
                        <?php print_author_data( $pa_data, 1, '', 1 ); ?>
                    </div>

                    <br/>
                    <div><h2>[其他作者]</h2>
                        <?php print_author_data( $ea_data, 1 ); ?>
                        <div id="author_add"></div>
                        <input type="button" id="new_btn" value="新增一欄" style="margin-top:5px;" />
                    </div>
                </div>
                <div id="tab3" class="tab_content">

                    <?php print_pfcr( $paper_data['id'], 0, 1 ); ?>
                </div>
            </div>
                <input type="hidden" name="change_authors" id="change_authors" value="" />
                <input type="hidden" name="paper_id" value=<?="\"".$paper_data['id']."\""; ?> />
                <div style=" text-align:center; ">
                    <button type="submit" name="form_submit" class="btn btn-primary" />確認送出</button>
                    <button type="button" onclick=" cancel_edit(); " class="btn btn-primary" />關閉視窗</button>
                    <button type="button" onclick=" delete_paper(); " class="btn btn-primary" />刪除論文</button>
                </div>

            </div>
        </form>
        <br/>
        <br/>
    </div> <!-- end container -->
</body>
<?php func_queue_handler_start(); ?>
<?php mail_queue_handler_start(); ?>
</html>