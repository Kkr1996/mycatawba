<?php
/**
 * Theme name: Social Login / Signup Login Theme
 * Builder type: Login form
 */
namespace logins\social_login_signup;

/**
 * Class Social_Login_Signup
 * @package logins\soc_login_signup
 */
class Social_Login_Signup
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            LOGIN_TABLE,
            array(
                'title'     => 'Social Login / Signup Theme',
                'structure' => self::structure(),
                'css'       => self::css(),
                'date'      => date('Y-m-d')
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
    }

    /** Login CSS */
    public static function css()
    {
        $asset_url = PROFILEPRESS_ROOT_URL . 'assets/images/mwp';

        return <<<CSS
/* css class for the login generated errors */
 .profilepress-login-status {
    border-radius: 6px;
    font-size: 17px;
    line-height: 1.471;
    padding: 10px 19px;
    background-color: #e74c3c;
    color: #ffffff;
    font-weight: normal;
    display: block;
    text-align: center;
    vertical-align: middle;
    margin: 10px auto;
    width: 350px;
}

#pp-soc-login-form {
    background: none repeat scroll 0 0 #fff;
    border: 1px solid #eee;
    box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.3);
    display: block;
    padding: 40px;
    top: 50px;
    margin: auto;
    width: 300px;
}
#pp-soc-login-form h3 {
    font-size: 24px;
    font-weight: 700;
    line-height: 30px;
}
#pp-soc-login-form p.onboarding {
    padding-bottom: 31px;
}
#pp-soc-login-form p {
    color: #444;
}
#pp-soc-login-form > a {
    font-family:"PT Sans", sans-serif;
    background: url("{$asset_url}/twitter.png") no-repeat scroll 4px 0 #3ca5ce;
    border-bottom: 3px solid #2191be;
    color: #fff;
    display: block;
    font-size: 16px;
    font-weight: 700;
    height: 39px;
    line-height: 42px;
    margin-bottom: 10px;
    text-indent: 60px;
    width: 300px;
    text-decoration: none;
}
#pp-soc-login-form a.facebook {
    background: url("{$asset_url}/fb.png") no-repeat scroll 4px 0 #4865a3;
    border-color: #324a7d;
}
#pp-soc-login-form a.google {
    background: url("{$asset_url}/google.png") no-repeat scroll 4px 0 #df4a32;
    border-color: #bf3e2a;
}
#pp-soc-login-form a.linkedin::before {
    font-family:"FontAwesome";
    color: #fff;
    font-size: 25px;
    content:"\f0e1";
    padding: 0 24px 5px 0;
}
#pp-soc-login-form a.linkedin {
    background: #007bb6;
    border-color: #007a99;
    text-indent: 15px;
}
#pp-soc-login-form a.github::before {
    font-family:"FontAwesome";
    color: #fff;
    font-size: 20px;
    content:"\f09b";
    padding: 0 23px 20px 0;
}
#pp-soc-login-form a.github {
    background: #444;
    border-color: #141414;
    text-indent: 16px;
}
CSS;

    }


    /** Login structure */
    public static function structure()
    {
        return <<<STRUCTURE
<div id="pp-soc-login-form">
    	<h3>Register or Log in</h3>

    <p class="onboarding">Create or log in to your account using your existing social media account.</p>	<a class="twitter" href="[twitter-login-url]">Continue with Twitter</a>
	<a class="facebook" href="[facebook-login-url]">Continue with Facebook</a>
	<a class="google" href="[google-login-url]">Continue with Google</a>
	<a class="linkedin" href="[linkedin-login-url]">Continue with LinkedIn</a>
	<a class="github" href="[github-login-url]">Continue with GitHub</a>

</div>
STRUCTURE;
    }
}