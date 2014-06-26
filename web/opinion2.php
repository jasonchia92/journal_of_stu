<?php
require_once("../loader.php");

$name = $_POST['name'];
$email = $_POST['email'];
$keynote = $_POST['keynote'];
$identity = $_POST['identity'];
$content = $_POST['content'];

send_opinion($name, $email, $keynote, $identity, $content);

echo '<meta http-equiv=REFRESH CONTENT=1.5;url=index.php>';
echo '發送成功';
?>