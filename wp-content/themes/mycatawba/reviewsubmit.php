<?php
/* Template Name: Submit */
$currentUser = wp_get_current_user();

echo $_POST['cptTitle'];
echo $_POST['cptContent'];

// create post object with the form values
$my_cptpost_args = array(

'post_title'   => $_POST['cptTitle'],

'post_content'  => $_POST['cptContent'],

'post_status'   => 'publish',

'post_type' => 'reviews',

'meta_input' => array(
    'user' => $currentUser->display_name ,
    'rating' =>  $_POST['rating'],
    'review' => $_POST['cptContent']
)

);
// insert the post into the database
$cpt_id = wp_insert_post( $my_cptpost_args, $wp_error);

$data= $_POST['badgeid'];
echo $_POST['userid'];
echo $data;
$update = $wpdb->insert("user_badges", array('user_id' => $_POST['userid'], 'badge' => $_POST['badgeid']), '%d');
if($update){
    echo "true";
}
else{
    echo "false";
}
                   
?>