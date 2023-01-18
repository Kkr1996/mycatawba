<?php
get_header();
/* Template Name: Sign up */
$siteUrl = get_site_url();
if(is_user_logged_in()){
    wp_redirect($siteUrl);
}
?>
<section class="user-registration">
    <div class="register-logo">
        <?php
            the_custom_logo();
        ?>
    </div>
    <?php
        if(have_rows('welcome')):
            while(have_rows('welcome')): the_row();
            $image = get_sub_field('image');
            $title = get_sub_field('main_title');
            $content = get_sub_field('content');
    ?>
    <div class="reg-welcome">
        <div class="video-wrap">
            <!--<iframe width="100%" height="100%" src="https://www.youtube.com/embed/_eLjkQ7vtOw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>-->
            <img src="<?php echo $image['url']; ?>" >
        </div>
        <div class="welcome-text">
            <h1 class="main-title-pass"><?php echo $title; ?></h1>
            <div class="pass-text"><?php echo $content; ?></div>
        </div>
    </div>
    <?php endwhile; endif; ?>
</section>
<?php
    if(have_rows('how_sec')):
        while(have_rows('how_sec')): the_row();
        $title = get_sub_field('title');
        $content = get_sub_field('content');
        $backImage = get_sub_field('background_image');
?>
<section class="how-it-works background-image" style="background-image:url(<?php echo $backImage['url']; ?>);">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">                
                <h2 class="main-title"><?php echo $title; ?></h2>
                <div>
                   <?php echo $content; ?>
                </div>                
            </div>
        </div>
    </div>
</section>
<?php endwhile; endif; ?>
<!-- How it works ends -->

<!-- User Registration Form -->
<section class="user-reg-form">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <h2 class="main-title text-center">Create Account</h2>
                <ul class="unstyled-list form-progress">
                    <li class="active"><span>1</span></li>
                    <li><span>2</span></li>
                    <li><span>3</span></li>
                </ul>
                <div class="registration-form">
                    <?php echo do_shortcode('[profilepress-registration id="1"]'); ?>
                    <div class="message-error">Please enter all the required fields.</div>
                </div>                
            </div>
        </div>
    </div>
</section>
<!-- User Registration Form Ends -->
<?php get_footer(); ?>