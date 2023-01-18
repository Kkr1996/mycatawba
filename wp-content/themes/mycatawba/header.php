<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MyCatawba
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
    <?php if(is_user_logged_in()) {?>
	<header id="masthead" class="site-header">
        <div class="container">
            <div class="header-main">
                <div class="site-branding">
                    <?php
                        the_custom_logo();
                    ?>
                </div><!-- .site-branding -->
                <div class="user-toggle">
                    <button class="user-btn" class="toggle-btn" data-toggle="dropdown" aria-expanded="true">
                        <i class="fas fa-user"></i>
                    </button>
                   <nav id="site-navigation" class="main-navigation dropdown-menu menu">			
                    <?php
                        wp_nav_menu( array(
                            'menu' => 'Main Menu',
                            'menu_class' => 'unstyled-list main-menu',
                            'container' => '<ul>'
                        ) );
                    ?>
                  </nav> 
                </div>
            </div>
        </div>
	</header><!-- #masthead -->
    <?php } ?>
	<div id="content" class="site-content">
