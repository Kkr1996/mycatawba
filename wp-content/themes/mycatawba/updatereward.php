<?php    
    /* Template Name: Update Reward */
    echo $_POST['userid'];    
    echo $_POST['rewardid'];    
    $update = $wpdb->insert("user_rewards", array('userid' => $_POST['userid'], 'reward_id' => $_POST['rewardid']), '%d');
    if($update){
        echo "true";
    }
    else{
        echo "false";
    }
?>