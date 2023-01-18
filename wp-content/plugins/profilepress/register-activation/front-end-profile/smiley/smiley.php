<?php
/**
 * Theme name: Smiley
 * Builder type: Front end profile
 */
namespace front_end_profile\smiley_theme;

/**
 * Class Smiley_Profile
 * @package front_end_profile\smiley_theme
 */
class Smiley_Profile
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            USER_PROFILE_TABLE,
            array(
                'title'     => 'Smiley Profile Theme',
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
        $asset_url = PROFILEPRESS_ROOT_URL . 'assets/images/smiley';

        return <<<CSS
.ppprofile1 .ppboxa {
    background: #fff;
    max-width: 550px;
    width: 100%;
    height: 100px;
    margin: 0 auto;
    text-align: center;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}

.ppprofile1 .ppavatar {
    display: inline;
    border-radius: 120px;
    border: 6px solid #fff;
    margin-top: 05px;
    background: #f1f1f1;
    max-width: 175px;
    width: 100%;
}

.ppprofile1 .ppboxb {
    background: url({$asset_url}/bg1.png);
    max-width: 580px;
    width: 100%;
    margin: 0 auto;
    color: #fff;
    border-radius: 4px;
    padding-bottom: 4px;
}

.ppprofile1 .ppuserdata {
    text-align: center;
    padding-top: 110px;
    text-shadow: 0 1px 1px rgba(0, 0, 0, .25);
}

.ppprofile1 .pp-jca {
    text-align: center;
    padding: 20px 5px;
}

.ppprofile1 span.ppname {
    font-size: 36px;
}

.pp-jca span.jcname {
    font-size: 20px;
}

.ppprofile1 span.pptitle {
    text-transform: uppercase;
    font-size: 12px;
    color: rgba(255, 255, 255, .8);
}

.ppprofile1 .ppprofdata {
    padding: 30px;
    background: rgba(0, 0, 0, .1);
    margin-top: 50px;
    margin: 30px;
    font-size: 15px;
    border-radius: 4px;
    text-shadow: 0 1px 1px rgba(0, 0, 0, .25);
}

.ppprofdata .pprof-val {
    float: right;
    width: 80%;
}

.ppprofile1 .ppprofdata ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.ppprofile1 .ppprofdata ul li {
    padding-bottom: 10px;
}

.ppprofile1 .ppprofdata ul li a {
    color: #F1F1F1;
    text-decoration: none;
}

.ppprofile1 .ppprofdata ul li a {
    color: #F1F1F1;
    text-decoration: none;
    border-bottom: 0 none;
}

.ppprofile1 .ppuserdata ul li img {
    display: inline;
}

.ppprofile1 .ppsocial {
    margin: 0;
    padding: 0;
    text-align: left;
    position: absolute;
    margin-top: -90px;
    margin-left: 20px;
    opacity: 0.2;
}

.ppprofile1 .ppsocial li {
    list-style: none;
    display: inline;
}
CSS;

    }

    public static function structure()
    {
        $asset_url = PROFILEPRESS_ROOT_URL . 'assets/images/smiley';

        return <<<STRUCTURE
<div class="ppprofile1">
	<div class="ppboxa">
		<img src="[profile-avatar-url]" class="ppavatar"/>
	</div>
	<div class="ppboxb">
		<div class="ppuserdata">
			<ul class="ppsocial">
				<li>
					<a href="[profile-cpf key=facebook]"><img src="{$asset_url}/facebook.png"/></a>
				</li>
				<li>
					<a href="[profile-cpf key=twitter]"><img src="{$asset_url}/twitter.png"/></a>
				</li>
				<li>
					<a href="[profile-cpf key=linkedin]"><img src="{$asset_url}/linkedin.png"/></a>
				</li>
			</ul>
			<span class="ppname">[profile-first-name] [profile-last-name]</span><br/>
			<span class="pptitle">[profile-cpf key="country"]</span>
		</div>
		<div class="ppprofdata">
			<ul>
				<li><strong>Email:</strong> <span class="pprof-val">[profile-email]</span></li>
				<li><strong>Gender:</strong> <span class="pprof-val">[profile-cpf key="gender"]</span></li>
				<li>
					<strong>Website:</strong> <span class="pprof-val"><a href="[profile-website]">[profile-website]</a></span>
				</li>
				<li><strong>Country:</strong> <span class="pprof-val">[profile-cpf key="country"]</span></li>
				<li>
					<strong>Bio:</strong> <span class="pprof-val">[profile-bio]</span>
				</li>
			</ul>
		</div>
		<div class="pp-jca">
			<span class="jcname">My Articles</span> [jcarousel-author-posts count="20"]
		</div>
	</div>
</div>
STRUCTURE;
    }
}


