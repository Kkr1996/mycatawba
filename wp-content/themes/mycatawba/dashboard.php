<?php
/* Template Name: Dashboard */
    get_header();    
?>

<?php 
    global $wpdb;   
    if( !current_user_can('administrator') ) {  
        $role = 1;
        echo '<style> html{margin: 0 !important;} </style>';
    }
    $currentUser = wp_get_current_user();
    $currentID = $currentUser->ID;
    $users = $wpdb->get_results("SELECT user_id FROM user_badges WHERE user_id = '$currentID' ");    
    $badges = $wpdb->get_results("SELECT badge FROM user_badges WHERE user_id = '$currentID' ");        
    foreach($badges as $badge){
        $claimedBadges[] = $badge->badge;
    }
    if($claimedBadges == NULL){
        $claimedBadges[] = 0;
    }

    $rewards = $wpdb->get_results("SELECT reward_id FROM user_rewards WHERE userid = '$currentID' ");        
    foreach($rewards as $reward){
        $claimedRewards[] = $reward->reward_id;
    }
    if($claimedRewards == NULL){
        $claimedRewards[] = 0;
    }
?>

<!-- All Badges -->
<section class="all-badges">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">                
                <div class="all-badges-heading">
                    <h3 class="all-badge-small">
                        <?php echo get_field('first_title'); ?>
                    </h3>
                    <h1 class="main-title">
                        <?php echo get_field('main_title'); ?>
                    </h1>
                </div>
                <h2 class="main-title-2"><?php echo get_field('all_badges_heading'); ?></h2>
                <div class="badges-slider-outer">
                    <button class="slick-prev slide-prev"><img src="<?php echo get_template_directory_uri().'/assets/images/arrow-prev.png'?>"></button>
                    <button class="slick-next slide-next"><img src="<?php echo get_template_directory_uri().'/assets/images/arrow-next.png'?>"></button>
                    <div class="badges-slider">
                        <?php
                            $args = array('post_type' => 'badges', 'year' => date(Y), 'posts_per_page' => -1);
                            $badges = get_posts($args);
                            if($badges){
                                $active;
                                $Q1 = array(1, 2, 3, 4);
                                $Q2 = array(5, 6, 7, 8);
                                $Q3 = array(9, 10, 11, 12);
                                if(in_array(date(n), $Q1)){
                                    $active = 'Q1';
                                }   
                                if(in_array(date(n), $Q2)){
                                    $active = 'Q2';
                                }
                                if(in_array(date(n), $Q3)){
                                    $active = 'Q3';
                                }                                
                                foreach($badges as $badge){
                                    setup_postdata( $badge );                                  
                                    $month = get_field('availability', $badge->ID);
                                    $currentMonth = array();
                                    if( $month ): ?>
                                        <?php foreach( $month as $m ):
                                            $currentMonth[] = $m;                                            
                                        endforeach;                                    
                                    ?>
                                    <?php endif; ?>
                                    <div class="badge-col <?php echo( in_array($active, $currentMonth) ? 'active' : 'disabled'); ?> <?php echo (in_array($badge->ID, $claimedBadges) ? 'claimed': ''); ?>">
                                        <form class="claim-form" action="<?php echo site_url(); ?>/review" method="post">
                                            <input type="hidden" name="userid" value="<?php echo $currentID; ?>">
                                            <input type="hidden" name="badgeid" value="<?php echo $badge->ID; ?>">
                                        <div class="badge-inner-wrap">
                                            <div class="badge-icon">
                                                <?php $badgeIcon =  wp_get_attachment_image_src( get_post_thumbnail_id( $badge->ID ), 'single-post-thumbnail' )[0];?>
                                                <img src="<?php echo ($badgeIcon ? $badgeIcon : get_template_directory_uri().'/assets/images/default.png'); ?>">
                                            </div>
                                            <h2 class="badge-title"><?php echo $badge->post_title; ?></h2>
                                            <div class="badge-description"><?php the_content(); ?></div>
                                            <div class="login-btn badge-claim">
                                                <input type="submit" id="<?php echo $badge->ID; ?>" class="<?php echo (in_array($badge->ID, $claimedBadges) ? 'disabled': ''); ?>" value="<?php echo (in_array($badge->ID, $claimedBadges) ? 'Claimed': 'Claim'); ?>" >
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                            <?php } wp_reset_postdata();
                            }
                        ?>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- All Badges Ends -->

<!-- Claimed Badges -->
<section class="claimed-badges">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <h2 class="main-title-2"><?php echo get_field('claimed_badges_heading'); ?></h2>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="claimed-badge-listing">
                <?php  
                        $args = array('post_type' => 'badges', 'include' => $claimedBadges, 'year' => date(Y), 'meta_query' => array(
                                array(
                                    'key' => 'availability',
                                    'value' => $active,
                                    'compare' => 'LIKE'
                                ),
                            'posts_per_page' => -1
                            ));
                        $badges = get_posts($args);
                        $claimedCount = count($badges);                        
                        if($badges){
                            foreach($badges as $badge){
                                setup_postdata( $badge ); 
                                ?>
                                <div class="badge-col">
                                    <div class="badge-inner-wrap">
                                        <div class="badge-icon">
                                            <?php $badgeIcon =  wp_get_attachment_image_src( get_post_thumbnail_id( $badge->ID ), 'single-post-thumbnail' )[0];?>
                                            <img src="<?php echo ($badgeIcon ? $badgeIcon : get_template_directory_uri().'/assets/images/default.png'); ?>">
                                        </div>
                                        <h2 class="badge-title"><?php echo $badge->post_title; ?></h2>
                                        <div class="badge-description"><?php the_content(); ?></div>
                                    </div>
                                </div>
                        <?php } wp_reset_postdata();
                        }
                        else{
                            echo '<h4>You have not claimed any badge.</h4>';
                        }
                    ?>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-m-12 col-sm-12 col-12">
                <?php                
                    $tbadge = array('post_type' => 'badges', 'year' => date(Y), 'meta_query' => array(array(
                                'key' => 'availability',
                                'value' => $active,
                                'compare' => 'LIKE'
                                ),
                            ),'posts_per_page' => -1,
                           );
                    
                    $totalBadges = count(get_posts($tbadge));
                ?>
                <div class="badge-col reward-col">
                    <div class="badge-inner-wrap">
                        <?php
                            $rewardsQuery = array('post_type' => 'rewards', 'posts_per_page' => 1, 'year' => date(Y), 'meta_query' => array(array(
                            'key' => 'availability',
                            'value' => $active,
                            'compare' => 'LIKE' ),),
                            'posts_per_page' => -1,
                            );
                            $rewards = get_posts($rewardsQuery);
                            if($rewards){ 
                                foreach($rewards as $reward){ ?>
                                <?php $rewardIcon =  wp_get_attachment_image_src( get_post_thumbnail_id( $reward->ID ), 'single-post-thumbnail' )[0]; 
                                    if($rewardIcon){
                                ?>
                                    <div class="reward-image">
                                        <img src="<?php echo $rewardIcon; ?>">
                                    </div>
                                <?php } ?>
                                    <div class="reward-title">
                                        <h2 class=""><?php echo $reward->post_title; ?></h2>
                                    </div>
                            <?php } ?>             
                        <?php }
                            else{
                                echo '<div class="reward-title">
                                        <h2 class="">No rewards available right now.</h2>
                                    </div>';
                            }                        
                        ?>
                        <div class="reward-progress">
                            <div class="completed-badge">
                                <span class="title">Completed Badges</span><span class="count"><?php echo $claimedCount; ?></span>
                            </div>
                            <div class="total-badge">
                                <span class="title">Available Badges</span><span class="count"><?php echo $totalBadges; ?></span>
                            </div>
                        </div>                        
                        <div class="reward-progress-bar">
                            <div class="reward-bar">
                                <div class="reward-bar-cal"></div>
                            </div>
                            <div class="reward-t">
                                Completed <span></span>
                            </div>
                        </div>                        
                        <div class="">
                            <form action="<?php echo site_url(); ?>/claim-reward" method="post" class="login-btn badge-claim reward-claim">
                                <input type="submit" value="<?php echo (in_array($reward->ID, $claimedRewards) ? 'Claimed': 'Claim Reward'); ?>" class="<?php echo (in_array($reward->ID, $claimedRewards) ? 'claimed-rew': ''); ?>">
                                <input type="hidden" value="<?php echo $reward->ID; ?>" name="rewardID">
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Claimed Badges Ends -->

<script>
    jQuery(document).ready(function(){
        
        var role = '<?php echo $role; ?>';        
        if(role == 1){
            jQuery("#wpadminbar").remove();
            jQuery('html').css('margin', '0 !important');
        }
        var totalBadges = jQuery('.total-badge > .count').html();
        var claimedBadges = jQuery('.completed-badge > .count').html();
        var badgePercent = (claimedBadges / totalBadges) * 100;
        jQuery('.reward-t > span').html(Math.round(badgePercent * 100) / 100+'%');
        jQuery('.reward-bar-cal').css('width', Math.round(badgePercent * 100) / 100+'%');
        
        if(totalBadges == claimedBadges){
           jQuery('.reward-claim > input').addClass('active');
        }
        else{
           jQuery('.reward-claim > input').addClass('disabled');
       }
        
        
        /*jQuery('.badge-claim > a').click(function(e){
            e.preventDefault();
            if(jQuery(this).hasClass('disabled')){
                e.preventDefault();
            }
            else{
                var target = jQuery(this);
                var claimBadge = jQuery(this).attr('id');
                var userID = <@?php echo $currentID; ?>;
                jQuery.ajax({
                    data: {id:claimBadge, userid: userID},
                    type: 'POST',
                    url: '<@?php echo site_url(); ?>/update-badge/',
                    success: function(){                    
                        target.addClass('disabled');
                    }

                });
            }            
        });    */   
    });
</script>
<?php get_footer();
?>