<?php
// Global namespace
namespace {

    require 'passwordless.php';
}

namespace Extras {

    class PP_Extras
    {
        public static function instance()
        {
            \PP_Passwordless::instance();
        }
    }
}