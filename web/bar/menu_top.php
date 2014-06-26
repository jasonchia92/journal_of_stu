<ul class="nav btn-group">
    <li><a href="<?php echo ROOT_URL;?>/web/" class="button button-rounded button-flat-primary button-jumbo">學報系統首頁</a></li>
    <li><a href="<?php echo ROOT_URL;?>/web/person/index.php" class="button button-rounded button-flat-primary button-jumbo">個人資料</a></li>
        <?php
        $auth_data = get_auth_data();

        if( $auth_data ){
            $pms = $auth_data['gid'];
            echo $pms{3} ? '<li><a href="'.ROOT_URL.'/web/contribute/check_paper.php" class="button button-rounded button-flat-primary button-jumbo">線上投稿系統</a></li>' : '';
            echo ($pms{1} | $pms{2}) ? '<li><a href="'.ROOT_URL.'/web/review/all_paper.php" class="button button-rounded button-flat-primary button-jumbo">線上審稿系統</a></li>' : '';
            echo $pms{0} ? '<li><a href="'.ROOT_URL.'/web/manage/index.php">學報管理系統</a></li>': '';
        }
        echo '<li><a href="'.ROOT_URL.'web/logout.php" class="button button-rounded button-flat-primary button-jumbo">登出</a></li>';
    ?>
</ul>
