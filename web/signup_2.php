<?php
require_once('../loader.php');
$email = $_POST['email'];
$pwd = $_POST['pwd'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$c_p = $_POST['check_pwd'];

if( isset($_POST['fax']) )
    $fax = $_POST['fax'];
else
    $fax = '';
if( isset($_POST['sex']) )
    $sex = $_POST['sex'];
else
    $sex = '';
if( isset($_POST['titles']) )
    $titles = $_POST['titles'];
else
    $titles = '';
if( isset($_POST['serve_unit']) )
    $serve_unit = $_POST['serve_unit'];
else
    $serve_unit = '';
if( isset($_POST['country']) )
    $country = $_POST['country'];
else
    $country = '';
if( isset($_POST['postcodes']) )
    $postcodes = $_POST['postcodes'];
else
    $postcodes = '';
if( isset($_POST['address']) )
    $address = $_POST['address'];
else
    $address = '';

    if ( user_signUp( $name, $pwd, $email, $phone, $sex, $fax, $titles, $serve_unit,  $address, $postcodes, $country ) == true ) : ?>
        <script type="text/javascript">
            alert('申請完成。');
            window.location.href = '<?php echo ROOT_URL; ?>/web/';
        </script>
    <?php else : ?>
        <script type="text/javascript">
            alert('E-mail重複，請使用其他E-mail。');
            window.location.href = '<?php echo ROOT_URL; ?>/web/signup.php';
        </script>
    <?php endif;

?>