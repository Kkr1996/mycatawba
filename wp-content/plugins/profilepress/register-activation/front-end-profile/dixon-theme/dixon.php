<?php
/**
 * Theme name: Dixon
 * Builder type: Front end profile
 */
namespace front_end_profile\dixon_theme;

/**
 * Class Dixon_Profile
 * @package front_end_profile\dixon_theme
 */
class Dixon_Profile
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            USER_PROFILE_TABLE,
            array(
                'title'     => 'Dixon Profile Theme',
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

    public static function css()
    {
        $asset_url = PROFILEPRESS_ROOT_URL . 'assets/images/dixon-fe-profile';

        return <<<CSS
/* CSS Stylesheet here */

@import url(https://fonts.googleapis.com/css?family=Raleway);
 @import url(https://fonts.googleapis.com/css?family=Lato);
#dixon-wraper {
    width: 100%;
	 background:#ececec;
}

.dixon-section-name {
    float:left;
    color:#222222;
}
.dixon-section-address {
    margin-left:30%;
    color: #6b6969;
}
.dixon-container {
    margin:auto;
    width:100%;
	padding-bottom: 10px;
}
.dixon-section-name > p, .dixon-section-address > p {
    padding-bottom:20px;
	margin:0;
}

.dixon-section-name > a, .dixon-section-address > a {
    text-decoration:none;
}

.dixon-container h3 {
    margin:30px 0;
    font-family:Lato, sans-serif;
    color:#196783;
    font-size:28px;
}
.dixon-header {
    background:url({$asset_url}/bg.jpg);
    width:100%;
    height:60%;
    border-bottom:3px solid #a7bdc8;
	padding-bottom: 5px;
}
.dixon-aside {
    margin-top:30px;
}
.dixon-section {
    background:#fff;
    padding:40px 20px 10px;
    border-top:4px solid red;
	margin: 0 10px;
}
.dixon-img {
    padding-top:40px;
    position:relative;
    float:left;
	width: 50%;
}
.dixon-icon > ul > li img {
    box-shadow: none;
}

.dixon-text {
    text-align:center;
    font-size:30px;
    color:#fff;
    padding-top:50px;
    line-height:40px;
    font-family:Raleway, Sans-serif;
    text-transform:uppercase;
}


.dixon-icon ul li::before {
    content: none;
}

.dixon-icon li {
    display:inline-block;
    text-align:center;
}
.dixon-icon {
    text-align:center;
    margin-top:15px;
}
.dixon-text span {
    border-bottom:1px solid #52edc7;
    padding-bottom:4px;
}
.dixon-aside img {
    width:190px;
    margin-top:-30px;
}
.dixon-center-text {
    color:#6b6969;
}
.dixon-section-address span a {
    color:#24758e;
}
.dixon-light {
    margin-left:10px;
}
.dixon-img-left {
    margin-left:5px;
}
.dixon-aside-text {
    border-bottom: 1px solid #949798;
    border-top: 1px solid #949798;
    padding:25px 0;
    margin-top: 50px;
}

.dixon-avatar > img {
    border: 3px solid #ffffff;
    border-radius: 50%;
    height: 50%;
    margin: 10px;
    padding: 4px;
    width: 70%;
    float: left;
}

CSS;

    }

    /** Structure */
    public static function structure()
    {
        $asset_url = PROFILEPRESS_ROOT_URL . 'assets/images/dixon-fe-profile';

        return <<<STRUCTURE
<div id="dixon-wraper">
    <div class="dixon-header">
        <div class="dixon-container">
            <div class="dixon-img">
			<div class="dixon-avatar">
                <img src="[profile-avatar-url]" />
				</div>
            </div>
            <div class="dixon-text"> <span>[profile-username] </span>
                <br />Profile</div>
            <div class="dixon-icon">
                <ul>
                    <li> <a href="[profile-cpf key=facebook]"><img src="{$asset_url}/facebook.png" /> </a>
                    </li>
                    <li> <a href="[profile-cpf key=twitter]"><img src="{$asset_url}/twitter.png" /> </a>
                    </li>
                    <li> <a href="[profile-cpf key=linkedin]"><img src="{$asset_url}/linkedn.png"/> </a>
                    </li>
                </ul>
                </div>
        </div>
    </div>
    <div class="dixon-container">
        <div style="text-align: center;">
            <h3>  PROFILE DETAILS </h3>
        </div>
        <div class="dixon-section">
            <div class="dixon-section-name">
                <p>First name</p>
            </div>
            <div class="dixon-section-address">
                <p>[profile-first-name]</p>
            </div>
            <div class="dixon-section-name">
                <p>Last name</p>
            </div>
            <div class="dixon-section-address">
                <p>[profile-last-name]</p>
            </div>
            <div class="dixon-section-name">
                <p>Nickname</p>
            </div>
            <div class="dixon-section-address">
                <p>[profile-nickname]</p>
            </div>
            <div class="dixon-section-name">
                <p>Gender</p>
            </div>
            <div class="dixon-section-address">
                <p>Male</p>
            </div>
            <div class="dixon-section-name">
                <p>Website</p>
            </div>
            <div class="dixon-section-address">
                <p> <span> <a href="#"> [profile-website] </a></span>
                </p>
            </div>
            <div class="dixon-section-name">
                <p>Bio</p>
            </div>
            <div class="dixon-section-address">
                <p>[profile-bio]</p>
            </div>
        </div>
    </div>
</div>
STRUCTURE;
    }
}


