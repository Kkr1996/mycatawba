<?php
    /* Template Name: Review */    
    get_header();
    session_start();
    $_SESSION['badgeid'] = $_POST['badgeid'];  
    $siteUrl = get_site_url();
    if(!isset($_POST['badgeid'])){
        wp_redirect($siteUrl);
    }
?>
<?php $backgroundImage =  wp_get_attachment_image_src( get_post_thumbnail_id(), 'single-post-thumbnail' )[0];?>
<section class="review-banner background-image" style="background-image:url(<?php echo $backgroundImage; ?>);">    
</section>

<section class="review-section">
    <div class="container text-center">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <?php
                    $currentBadge = get_post($_POST['badgeid']);
                    if($currentBadge){ ?>
                        <div class="badge-icon">
                                <?php $badgeIcon =  wp_get_attachment_image_src( get_post_thumbnail_id($_SESSION['badgeid']), 'single-post-thumbnail' )[0];?>
                                <img src="<?php echo ($badgeIcon ? $badgeIcon : get_template_directory_uri().'/assets/images/default.png'); ?>">
                            </div>
                            <h2 class="badge-title"><?php echo $currentBadge->post_title; ?></h2>
                            <div class="badge-description"><?php echo $currentBadge->post_content; ?></div>
                      <?php  } wp_reset_postdata();                    
                ?>
                <div class="badge-review">
                    <div class="rating-error">
                        Please select your rating
                    </div>
                    <div class="__submitLoader-wrap">
                        <div class="__submitLoader"></div>
                    </div>
                    <ul class="rating-meter unstyled-list">
                        <li><i class=""></i></li>
                        <li><i class=""></i></li>
                        <li><i class=""></i></li>
                        <li><i class=""></i></li>
                        <li><i class=""></i></li>
                    </ul>                    
                    <form method="post" action="submit-review">
                        <input type="hidden" name="cptTitle" id="cptTitle" value="<?php echo $currentBadge->post_title; ?>"/>
                        <input type="hidden" name="rating" class="ratingValue" value=""> 
                        <input type="hidden" name="userid" value="<?php echo $_POST['userid']; ?>" id="userid">
                        <input type="hidden" name="badgeid" value="<?php echo $_POST['badgeid']; ?>" id="badgeid">
                        <input type="hidden" name="promocode" value="<?php echo get_field('promo_code', $_SESSION['badgeid']); ?>" id="promocode">
                        <textarea name="cptContent" id="cptContent" rows="5" cols="20" placeholder="Enter Message" required></textarea>
                        <input type="text" name="usercode" placeholder="Enter promo code" id="usercode">
                        <div class="login-btn badge-claim">
                            <input type="submit" value="Send Review" class="submit-review">
                        </div>                        
                        <?php wp_nonce_field( 'cpt_nonce_action', 'cpt_nonce_field' ); ?>
                    </form>
                    <div class="message-error">Please enter your review.</div>
                    <div class="message-error-code">Invalid Code</div>
                </div>
                <div class="success-message text-center" style="display:none;">
                    <h3><i class="far fa-check-circle"></i>Thank you for your feedback! Your review has been submitted.</h3>
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
        $('.rating-meter > li').on('mouseenter', function(){            
            var currentLi = $(this).index()+1;
            $('.rating-meter > li:lt('+currentLi+')').addClass('hover');
        });
        $('.rating-meter > li').on('mouseout',function(){
            $('.rating-meter > li').removeClass('hover');            
        });
        
        $('.rating-meter > li').on('click', function(){
            var activeItem = $(this).index()+1;
            $('.rating-meter > li').removeClass('active');
            $('.rating-meter > li:lt('+activeItem+')').addClass('active');
            $('.ratingValue').val(activeItem);
        });
        
        $('.submit-review').click(function(e){
            var ratingFlag;
            e.preventDefault();
            var promo = $('#promocode').val().toLowerCase();
            var userCode = $('#usercode').val().toLowerCase();
            
            if((promo != userCode) || (userCode == "")){
                $('.message-error-code').fadeIn(300);
                setTimeout(function(){
                   $('.message-error-code').fadeOut(300);
                }, 2000);
                return false;
            }            
            var postTitle = $('#cptTitle').val();
            var rating = $('.ratingValue').val();            
            if((rating == undefined) || (rating == "")) {
                ratingFlag = false;
                $('.rating-error').fadeIn(300);
                setTimeout(function(){
                   $('.rating-error').fadeOut(300);
                }, 2000);
            }
            else if(rating != undefined){
                ratingFlag = true;
            }
            var userID = $('#userid').val();
            var badgeID = $('#badgeid').val();
            var postContent = $('#cptContent').val();
            if(postContent == ""){
                ratingFlag = false;
                $('.message-error').fadeIn(300);
                setTimeout(function(){
                   $('.message-error').fadeOut(300);
                }, 2000);
            }             
            if(ratingFlag == true){
                $.ajax({
                    data: {cptTitle: postTitle, rating: rating, userid: userID, badgeid: badgeID, cptContent: postContent},
                    type: 'POST',
                    url: '<?php echo site_url(); ?>/submit-review/',
                    beforeSend: function(){
                        $('.__submitLoader-wrap').show();
                    },
                    success: function(){                    
                        $('.__submitLoader-wrap').hide();
                        $('.badge-review').fadeOut(300);
                        $('.success-message').fadeIn(300);
                    }
                });
            }
            else{
                
            }
        });
        
        });    
</script>
<?php get_footer(); ?>