<?php    
    /* Template Name: Update Badge */
    $data= $_POST['id'];
    echo $_POST['userid'];
    echo $data;
    $update = $wpdb->insert("user_badges", array('user_id' => $_POST['userid'], 'badge' => $_POST['id']), '%d');
    if($update){
        echo "true";
    }
    else{
        echo "false";
    }
?>