<?php
$gid = '0000';
$gid{$_GET['gid']} = '1';

$col_name_array = array(
    'name', 'sex', 'titles', 'serve_unit', 'email',
    'phone', 'fax', 'address', 'postcodes', 'country', 'assign_category'
);

$change_gid = '0000';
if ( isset($_POST['user_group']) )
    foreach ( $_POST['user_group'] as $key => $value )
        $change_gid{$value} = 1;

$array = array(
    'id' => $_POST['uid'],
    'gid' => $change_gid
);

// Check empty value.
foreach ($col_name_array as $key => $value)
    if ( isset($_POST[$value]) )
        $array[$value] = $_POST[$value];

$operationResult = update_userData( $array );

// Reset password
if ( isset($_POST['pwd']) && !empty($_POST['pwd']) )
	$operationResult = reset_pwd( $_POST['uid'], $_POST['pwd'] );
