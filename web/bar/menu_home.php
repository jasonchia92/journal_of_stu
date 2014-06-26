<?php # require_once('../base_function.php'); ?>
<ul class="nav home-top-menu" >
          <div class="btn-group" >
                   <a href="index.php" class="button button-rounded button-flat-primary button-jumbo"  style="text-decoration:none;">首頁</a>
                   <a href="#" class="button button-rounded button-flat-primary button-jumbo">編審委員</a>
                   <a href="callforpaper.php" class="button button-rounded button-flat-primary button-jumbo">徵稿辦法</a>
                   <a href="publishs_history.php" class="button button-rounded button-flat-primary button-jumbo">卷期查詢</a>
                   <a href="login.php" class="button button-rounded button-flat-primary button-jumbo">線上投稿</a>
                   <a href="opinion.php" class="button button-rounded button-flat-primary button-jumbo">意見回應</a>
    <?php if ( !check_login_status(false) ): ?>
    <a href="signup.php" class="button button-rounded button-flat-primary button-jumbo">申請帳號</a>
    <?php endif ?>
 </div>
</ul>
