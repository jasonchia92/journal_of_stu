<?php
function get_publishs( $view_rows=10 ) {
    $sql = "SELECT * FROM publish ORDER BY id DESC";
    $result = sql_q_adv( $sql, array(), $view_rows );

    if ( count($result->data) > 0 )
        return $result;
    else
        return false;
}


function get_single_publish_data( $publish_id ) {
    $sql = "SELECT * FROM publish WHERE id=? ";
    $result = sql_q( $sql, array($publish_id) );

    if ( count($result) > 0 )
        return $result[0];
    else
        return false;
}


function get_newest_publish() {
    $result = sql_q("SELECT * FROM publish ORDER BY id DESC LIMIT 0,1", array());
    
    if ( count($result) > 0 )
        return $result[0];
    else
        return false;
}


function add_publish( $title, $publish_date ) {
    $file_pdf   = publish_file_upload( $_FILES['file_pdf'] );
    $file_cover = publish_file_upload( $_FILES['file_cover'] );
    
    if ( ($file_pdf == false) || ($file_cover == false) )
        return false;

    $sql = "INSERT INTO publish(
            title,
            file_pdf,
            file_cover,
            publish_date
        ) VALUES(?, ?, ?, ?) ";
    $array = array(
        $title,
        $file_pdf,
        $file_cover,
        $publish_date
    );

    return sql_e( $sql, $array );
}


function modify_publish( $publish_id, $title, $publish_date, $new_file_pdf=null, $new_file_cover=null ) {
    $sql_addon = array();

    if ( ($new_file_pdf !== null) || ($new_file_cover !== null) ) {
        // Get old file name.
        $sql = "SELECT file_pdf, file_cover FROM publish WHERE id=? ";
        $result = sql_q( $sql, array($publish_id) );

        // Change pdf file
        if ( $new_file_pdf !== null ) {
            publish_file_delete( $result[0]['file_pdf'] );
            $sql_addon['file_pdf'] = publish_file_upload( $new_file_pdf );
        }

        // Change cover file
        if ( $new_file_cover !== null ) {
            publish_file_delete( $result[0]['file_cover'] );
            $sql_addon['file_cover'] = publish_file_upload( $new_file_cover );
        }
    }
    
    $sql_addon_params = "";
    $sql_addon_col_params_array = array();

    foreach ($sql_addon as $key => $value) {
        $sql_addon_params .= ", ".$key."=? ";
        $sql_addon_col_params_array[] = $value;
    }

    $sql = "UPDATE publish SET
            title=?,
            publish_date=?
            ".$sql_addon_params."
        WHERE id=?";
    $array = array(
        $title,
        $publish_date
    );

    // Join addon column data
    foreach ($sql_addon_col_params_array as $value)
        $array[] = $value;

    // Join publish id
    $array[] = $publish_id;

    return sql_e( $sql, $array );
}


function publish_file_upload( $file ) {
    $publish_folder = ROOT_DIR."/web/publish/";
    $file_name = $file['name'];
    if ( OS_IS_WINDOWS == true ) {
        $publish_folder = iconv('utf-8', 'big5//ignore', $publish_folder);
        $file_name      = iconv('utf-8', 'big5//ignore', $file_name);
    }

    if ( !is_dir($publish_folder ) )
        @mkdir($publish_folder, 0755, true);

    if ( $file["error"] > 0 ) {
        return false;
    } else {
        if ( file_exists($publish_folder.$file_name) ) {
            if ( OS_IS_WINDOWS == true )
                $file_name = iconv('big5', 'utf-8//ignore', $file_name);
            
            $file_arr = explode(".", $file_name);
            $file_type = $file_arr[count($file_arr)-1];
            $file_name = $file_arr[0]."_".date('Ymdhis').".".$file_type;

            if ( OS_IS_WINDOWS == true )
                $file_name = iconv('utf-8', 'big5//ignore', $file_name);
        }
        
        if ( move_uploaded_file($file["tmp_name"], $publish_folder.$file_name) == true ) {
            if ( OS_IS_WINDOWS == true )
                $file_name = iconv('big5', 'utf-8//ignore', $file_name);
            return $file_name;
        } else {
            return false;
        }
    }
}


function publish_file_delete( $file_name ) {
    $array = array();
    if ( is_array($file_name) ) { // Support array.
        $array = $file_name;
    } else if ( func_num_args() > 1 ) { // Support multiple function parameters.
        for ($i=0; $i<func_num_args() ; $i++)
            $array[] = func_get_arg($i);
    } else if ( !is_array($file_name) ) { // Support single function parameter.
        $array[] = $file_name;
    } else {
        return false;
    }

    // Remove files
    foreach ($array as $value) {
        $path = PUBLISH_DIR.'/'.$value;
        if ( OS_IS_WINDOWS == true )
            $path = iconv('utf-8', 'big5//ignore', $path);

        if ( file_exists($path) )
            unlink($path);
    }

    return true;
}