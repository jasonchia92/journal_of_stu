<?php
    require_once('../../loader.php');
    check_login_status();
    $err_msg = "";
    if( isset( $_POST['submit'] ) ){
        $action = reply_opinion(
            $_GET['opinion_id'],
            $_POST['reply_content']
        );
        
        if( $action ) {
            // E-mail notification
            $mail_params = array(
                'user_name'        => $_POST['user_name'],
                'opinion_date'     => $_POST['opinion_date'],
                'reply_content'    => str_replace("\n", '<br/>', $_POST['reply_content']),
                'opinion_page_url' => ROOT_URL . '/web/opinion.php'
            );
            $content_obj = mail_sample_loader('opinion_replied', $mail_params);
            if ( send_mail( $_POST['target_email'], $content_obj ) )
                header('Location: ssm_opinion.php');
            exit;
        } else {
            $err_msg = "發生預期外錯誤，請聯絡系統管理員！";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>意見回應管理::系統設定管理 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body {
            padding-top: 15px;
            padding-bottom: 40px;
        }
        
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        .navbar .nav{
            text-align: center;
            display: table-cell;
            float: none;
        }

        .col-name {
            display: inline-block;
            width: 100px;
            vertical-align: top;
            text-align: right;
            margin: 0;
        }

        .col {
            display: inline-block;
        }

        .opinion-content {
            background-color: #FFFFFF;
            border: 1px solid #C0C0C0;
            border-radius: 3px;
            line-height: 22px;
            max-height: 200px;
            min-height: 100px;
            overflow: auto;
            padding: 0 3px;
            resize: none;
            width: 500px;
        }

        .label {
            font-size: 16px;
            line-height: 20px;
        }

    </style>
    <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
</head>
<body>
    <div class="container">
        <div align='center'>
            <?php include ("../bar/header.php");?>
        </div>
        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
              <?php include ("../bar/menu_top.php");?>  
            </div>
          </div>
        </div><!-- /.navbar -->
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <?php include ("menu_left.php");?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
            <div class="hero-unit">
                <div class="err_msg" style="color:#FF0000;"><?php echo $err_msg; ?></div>
                <div>
                    <span class="label label-important">意見內容</span>
                    <?php
                        $result = get_single_opinion_data($_GET['opinion_id']);
                        $identity_array = array('作者', '評閱者', '編輯委員', '其他');
                    ?>
                    <div>
                        <div class="col-name">編號：</div>
                        <div class="col"><?=$result['opinion_id'];?></div>
                    </div>
                    <div>
                        <div class="col-name">主旨：</div>
                        <div class="col"><?=dop($result['keynote']);?></div>
                    </div>
                    <div>
                        <div class="col-name">姓名：</div>
                        <div class="col"><?=dop($result['name']);?></div>
                    </div>
                    <div>
                        <div class="col-name">身分別：</div>
                        <div class="col"><?=$identity_array[ $result['identity'] ];?></div>
                    </div>
                    <div>
                        <div class="col-name">E-mail：</div>
                        <div class="col"><?=$result['email'];?></div>
                    </div>
                    <div>
                        <div class="col-name">回覆狀態：</div>
                        <div class="col"><?=( $result['replied'] ? '已回覆' : '尚未回覆' );?></div>
                    </div>
                    <div>
                        <div class="col-name">訊息內容：</div>
                        <div class="opinion-content"><?=dop($result['content']);?></div>
                    </div>
                    <?php if ( $result['replied'] == 1 ): ?>
                        <div>
                            <div class="col-name">回覆內容：</div>
                            <div class="opinion-content"><?=dop($result['reply_content']);?></div>
                        </div>
                    <?php endif; ?>
                </div>
                <div style="margin-top:30px;">
                    <span class="label label-important"><?=( $result['replied'] ? '再次回覆' : '線上回覆' );?></span><br/>
                    <form method="post" action="" style="display:inline-block; text-align:center;">
                        <input type="hidden" name="user_name"    value="<?=$result['name'];?>" />
                        <input type="hidden" name="target_email" value="<?=$result['email'];?>" />
                        <input type="hidden" name="opinion_date" value="<?=$result['date'];?>" />
                        <textarea class="opinion-content" name="reply_content"></textarea><br/>
                        <input type="submit" name="submit" class="btn" value="送出" />
                    </form>
                </div>
            </div> <!-- end hero-unit -->
        </div> <!-- end span9 -->
        <div align='center'>
        <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
    </body>
</html>
 