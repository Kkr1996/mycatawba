<?php
/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2014, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */

// ------------------------------------------------------------------------
//	HybridAuth End Point
// ------------------------------------------------------------------------

// Boostrap WP - Pantheon hosting
if (defined("PANTHEON_BINDING")) {
    include_once("/srv/bindings/" . PANTHEON_BINDING . "/code/wp-blog-header.php");
}

// hack to fix persist-admin-notices-dismissal require WPINC/ABSPATH constant to be defined else it bails.
define( 'WPINC', true );
define( 'ABSPATH', true );

require __DIR__ . '/../vendor/autoload.php';

Hybrid_Endpoint::process();