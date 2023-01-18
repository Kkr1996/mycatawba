<?php
    /* Template Name: Claim Reward */    
    get_header();
    session_start();
    $currentUser = wp_get_current_user();
    $currentID = $currentUser->ID;
    $_SESSION['rewardID'] = $_POST['rewardID'];
    $siteUrl = get_site_url();
    if(!isset($_POST['rewardID'])){
        wp_redirect($siteUrl);
    }
?>

<section class="review-section reward-c-section">
    <div class="container text-center">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <?php
                    $currentBadge = get_post($_POST['rewardID']);
                    if($currentBadge){ ?>
                        <div class="reward-image">
                                <?php $badgeIcon =  wp_get_attachment_image_src( get_post_thumbnail_id($_SESSION['rewardID']), 'single-post-thumbnail' )[0];?>
                                <img src="<?php echo ($badgeIcon ? $badgeIcon : get_template_directory_uri().'/assets/images/default.png'); ?>">
                            </div>
                            <h2 class="claim-reward-title">Congratulations!</h2>
                            <div class="badge-description"><?php echo $currentBadge->post_content; ?></div>
                      <?php  } wp_reset_postdata();                    
                ?>
                <div class="badge-review">                    
                    <form method="post" action="submit-review">                        
                        <div class="login-btn badge-claim">
                            <input type="submit" value="Claim Reward" class="submit-review">
                            <input type="hidden" value="<?php echo $currentID; ?>" name="userid" id="userid">
                            <input type="hidden" value="<?php echo $_POST['rewardID']; ?>" name="rewardid" id="rewardid">
                        </div>                        
                        <?php wp_nonce_field( 'cpt_nonce_action', 'cpt_nonce_field' ); ?>
                    </form>                    
                </div>
                <div class="success-message text-center" style="display:none;">
                    <h3><i class="far fa-check-circle"></i>Success. You've claimed your reward.</h3>
                    <div class="login-btn badge-claim">
                       <a href="<?php echo site_url(); ?>">Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    jQuery(document).ready(function($){        
        $('.submit-review').click(function(e){            
            e.preventDefault();
            var rewardID = $('#rewardid').val();           
            var userID = $('#userid').val();
            console.log(rewardID, userID);
            $.ajax({
                    data: {userid: userID, rewardid: rewardID},
                    type: 'POST', 
                    url: '<?php echo site_url(); ?>/update-reward/',
                    beforeSend: function(){
                        $('.__submitLoader-wrap').show();
                    },
                    success: function(){                    
                        $('.__submitLoader-wrap').hide();
                        $('.badge-review').fadeOut(300);
                        $('.success-message').fadeIn(300);
                    }
                });
        });
        
        });    
</script>
<?php get_footer(); ?>