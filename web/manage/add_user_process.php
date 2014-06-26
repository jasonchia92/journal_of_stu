<?php
    $gid = '0000';
    $gid{$_GET['gid']} = '1';

    $col_name_array = array(
        'name', 'sex', 'pwd', 'titles', 'serve_unit', 'email',
        'phone', 'fax', 'address', 'postcodes', 'country'
    );

    // Check empty value.
    foreach ($col_name_array as $key => $value)
        if ( !isset($_POST[$value]) )
            $_POST[$value] = '';

    $operationResult = add_user_byAdmin( 
        $_POST['name'],
        $_POST['sex'],
        $_POST['pwd'],
        $_POST['titles'],
        $_POST['serve_unit'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['fax'],
        $_POST['address'],
        $_POST['postcodes'],
        $_POST['country'],
        ( isset($_POST['assign_category']) ? $_POST['assign_category'] : 0 ),
        $gid
    );
    
    if ( $operationResult == true ) {
        // E-mail notification
        $mail_params = array(
            'user_name'     => $_POST['name'],
            'user_account'  => $_POST['email'],
            'user_password' => $_POST['pwd']
        );
        mail_queue_add( $_POST['email'], 'account_created', $mail_params);
    }
