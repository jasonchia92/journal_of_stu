<?php
function mail_service_init() {
    $mail= new PHPMailer();
    $mail->IsSMTP();                        // Set mail protocal is SMTP.
    $mail->SMTPAuth     = true;             // Set SMTP need authenticat.
    $mail->SMTPSecure   = "ssl";            // Set SMTP secure connection protocal is ssl.
    $mail->Host         = MAIL_SERVER_HOST; // Mail server host.
    $mail->Port         = MAIL_SERVER_PORT; // Mail server port.
    $mail->CharSet      = "utf-8";          // Set charset.
    $mail->Username     = MAIL_UID;         // Set authentication account.
    $mail->Password     = MAIL_PWD;         // Set authentication password.
    $mail->From         = MAIL_FROM;        // Set sender address.
    $mail->FromName     = MAIL_FROM_NAME;   // Set sender name.

    return $mail;
}


function send_mail( $recipients, $content_obj ) {
    $mail = mail_service_init();

    if ( !is_array($recipients) )
        $recipients = array( $recipients );

    foreach ($recipients as $value)
        $mail->addAddress( $value );

    $mail->Subject = $content_obj->subject;
    $mail->Body = $content_obj->content;
    $mail->IsHTML(true);  // Set content is html.

    // 使用者連線中斷仍繼續執行腳本直到完成，並下session_commit防止session被綁住.
    ignore_user_abort(true);
    set_time_limit(0);
    session_commit();
    if ( $mail->Send() ) {
        return true;
    } else {
        // make_log('Send mail is failed. Error massage: '.$mail->ErrorInfo);
        throw new Exception('Send mail is failed. Error massage: '.$mail->ErrorInfo);
        return false;
    }
}


function mail_sample_loader( $sample_name, $options ) {
    class MailSample {
        var $subject;
        var $content;

        function __construct( $subject=null, $content=null ) {
            $this->subject = $subject;
            $this->content = $content;
        }
    }

    // Get option include values
    $options = get_mail_sample_options_values( $sample_name, $options );

    $sample_path    = MAIL_SAMPLE_DIR . '/' . $sample_name . '.html';
    $signature_path = MAIL_SAMPLE_DIR . '/_signature.html';
    if ( file_exists( $sample_path ) && file_exists($signature_path) ) {
        // Mail subject and content
        $sample_content = file_get_contents($sample_path);
        foreach ($options as $key => $value) {
            $key = '(' . $key . ')';
            $sample_content = str_replace($key, $value, $sample_content);
        }
        $subject = substr($sample_content, 4, strpos($sample_content, '-->')-4 );

        // Signature
        $signature_content = file_get_contents( $signature_path );
        $signature_content = str_replace('(home_page_url)', ROOT_URL . '/web/', $signature_content);

        // Append signature to mail content
        $sample_content .= $signature_content;

        return new MailSample( $subject, $sample_content );
    } else {
        return false;
    }
}


function get_mail_sample_options_values( $sample_name, $options=array() ) {
    $auth_data = get_auth_data();
    $paper_category_name = array("未分類", "管理", "資訊", "設計", "幼保.性學.外文", "通識教育");
    // Default params
    $params = array(
        'date_time'             => date('Y/m/d h:m'),
        'login_page_url'        => ROOT_URL . '/web/login.php',
        'or_page_url'           => ROOT_URL . '/web/opinion.php'
    );

    // Mail contents target user
    if ( isset($options['uid']) ) {
        $user_data = get_user_data($options['uid']);
        $params['user_name']    = $user_data['name'];
        $params['user_account'] = $user_data['email'];
    } else if ( count($auth_data) > 0 ) {
        $params['user_name']    = $auth_data['name'];
        $params['user_account'] = $auth_data['account'];
    }

    // Get paper relation param values.
    if ( isset($options['pid']) ) {
        $paper_data                 = get_paper_inf($options['pid']);
        $params['paper_id']         = $paper_data['id'];
        $params['paper_title']      = dop( $paper_data['ch_title'] . ' / ' . $paper_data['en_title'] );
        $params['paper_category']   = $paper_category_name[ $paper_data['category'] ];
    }

    unset($options['pid']);
    unset($options['uid']);
    $params = array_merge($params, $options);

    return $params;
}


function mail_queue_add( $recipient, $sample_name, $other_options=array()) {
    global $mail_queue;

    if ( !is_array($other_options) ) {
        $other_options = array($other_options);
    }
    $mail_params = array(
        'action'        => 'send_mail',
        'recipient'     => $recipient,
        'sample_name'   => $sample_name
    );
    $mail_params = array_merge($mail_params, $other_options);

    $script_content = '';
    foreach ($mail_params as $key => $value)
        $script_content .= '\'' . $key . '\' : \'' . $value . '\', ';

    $script_content = '{' . substr($script_content, 0, -2) . '}';
    $mail_queue[] = $script_content;
}


function mail_queue_handler_start() {
    global $mail_queue;

    $url_target = ROOT_URL . '/web/ajax_request_handler.php';
    if ( count($mail_queue) > 0 ) : ?>
        <script>window.jQuery || document.write('<script src="<?=JS_URL;?>/js_spare/jquery.min.js"><\/script>')</script>
        <script type="text/javascript">
            $(document).ready(function(){
                <?php
                    foreach ($mail_queue as $value) : ?>
                        $.post(
                            '<?php echo $url_target; ?>',
                            <?php echo $value; ?>
                        );
                    <?php endforeach;
                ?>
            });
        </script>
    <?php endif;

    return true;
}


