<?php
/**
 * Theme name: Smiley2
 * Builder type: Front end profile
 */
namespace front_end_profile\smiley2_theme;

/**
 * Class Smiley2_Profile
 * @package front_end_profile\smiley2_theme
 */
class Smiley2_Profile
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            USER_PROFILE_TABLE,
            array(
                'title'     => 'Smiley Profile Theme 2',
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
.ppprofile2 {
    max-width: 550px;
    width: 100%;
    margin: 0 auto;
}

.ppprofile2 ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.ppprofile2 .ppavatar {
    max-width: 150px;
    width: 100%;
    border-radius: 100px;
    border: 8px solid #fff;
    position: relative;
    margin-bottom: -42px;
    z-index: 9;
    top: 20px;
    left: 20px;
}

.ppprofile2 .ppcover {
    background: url({$asset_url}/bg2.png);
    max-width: 550px;
    width: 100%;
    height: 125px;
    display: block;
    position: absolute;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}

.ppprofile2 .ppbtmbox {
    max-width: 550px;
    width: 100%;
    position: relative;
}

.ppbtmbox ul li::before {
    content: none;
}

.ppprofile2 .pplside {
    background: #fff;
    max-width: 100px;
    width: 100%;
    float: left;
    height: 320px;
    padding: 15px;
    box-sizing: border-box;
    padding-top: 75px;
    border-bottom-left-radius: 4px;
    border-left: 1px solid #f5f5f5;
    border-bottom: 1px solid #f5f5f5;
}

.ppprofile2 .pplside ul li {
    -moz-hyphens: none;
    word-wrap: normal;
    text-transform: uppercase;
    font-weight: normal;
    color: rgba(0, 0, 0, .4);
    font-size: 14px;
}

.ppprofile2 .pplside ul li span {
    text-transform: uppercase;
    font-weight: bold;
    color: rgba(0, 0, 0, .4);
    font-size: 28px;
}

.ppprofile2 .pprside {
    background: #f5f5f5;
    max-width: 440px;
    width: 100%;
    float: right;
    height: 320px;
    padding: 15px;
    box-sizing: border-box;
    padding-top: 75px;
    position: relative;
    border-bottom-right-radius: 4px;
}

.ppprofile2 .pprside ul.ppusrdata li i {
    font-size: 18px;
    background: #f5f5f5;
    border: 4px solid #f5f5f5;
    position: absolute;
    margin-left: -45px;
    margin-top: -5px;
    max-width: 25px;
    width: 100%;
    height: 35px;
    color: rgba(0, 0, 0, .5);
}

.ppprofile2 .pprside ul.ppusrdata li {
    background: rgba(0, 0, 0, .03);
    padding: 5px;
    font-size: 13px;
    margin-bottom: 6px;
    border-radius: 4px;
    color: #1f1f1f;
    padding-left: 40px;
    font-weight: 500;
    height: 30px;
}

.ppprofile2 .pprside ul.ppusrdata li a {
  border-bottom: 0 none;
  text-decoration: none;
}

.ppprofile2 .pprside ul.ppsocial {
    margin-top: -35px;
    right: 15px;
    position: absolute;
    top: 50px;
    opacity: 0.1;
    display: inline-flex;
}

.ppprofile2 .pprside ul.ppsocial li {
    padding-left: 5px;
}

.ppprofile2 .rside ul.ppsocial li {
    display: inline;
}

.ppprofile2 .pprside .ppbio {
    background: rgba(255, 255, 255, .45);
    padding: 10px;
    font-weight: 500;
    font-size: 13px;
    line-height: 1.4;
    position: absolute;
    bottom: 0;
    left: 0;
    max-width: 430px;
    width: 100%;
    color: #1f1f1f;
    border-bottom-right-radius: 4px;
}

.ppprofile2 .ppcover .ppuserinfo {
    text-align: right;
    margin: 50px;
}

.ppprofile2 .ppcover .ppuserinfo .ppname {
    font-size: 26px;
    font-weight: bold;
    color: #fff;
    text-shadow: 0 1px 1px rgba(0, 0, 0, .3);
}

.ppprofile2 .ppcover .ppuserinfo .pptitle {
    font-size: 13px;
    text-transform: uppercase;
    color: #1f1f1f;
    text-shadow: 0 1px 1px rgba(255, 255, 255, .5);
    position: relative;
    left: -10px;
}
CSS;

    }

    public static function structure()
    {
        $asset_url = PROFILEPRESS_ROOT_URL . 'assets/images/smiley';

        return <<<STRUCTURE
<div class="ppprofile2">
			<div class="ppcover">
				<div class="ppuserinfo">
					<span class="ppname">
						[profile-first-name] [profile-last-name]
					</span>
					<br />
					<span class="pptitle">
						[profile-cpf key="country"]
					</span>
				</div>
			</div>
			<div class="ppboxa">
				<img src="[profile-avatar-url]" class="ppavatar" />
			</div>
			<div class="ppbtmbox">
				<div class="pplside">
					<ul>
						<li>
							<span class="ppnum">[post-count]</span><br />
							articles
						</li>
						<br /><br />
						<li>
							<span class="ppnum">[comment-count]</span><br />
							comments
						</li>
					</ul>
				</div>
				<div class="pprside">
					<ul class="ppsocial">

						<li><a href="[profile-cpf key=facebook]"><img src="{$asset_url}/facebook.png" /></a></li>
						<li><a href="[profile-cpf key=twitter]"><img src="{$asset_url}/twitter.png" /></a></li>
						<li><a href="[profile-cpf key=linkedin]"><img src="{$asset_url}/linkedin.png" /></a></li>

					</ul>
					<ul class="ppusrdata">
						<li>
							<i class="fa fa-plane"></i>
							[profile-cpf key="country"]
						</li>
						<li>
							<i class="fa fa-envelope-o"></i>
							[profile-email]
						</li>
						<li>
							<i class="fa fa-transgender-alt"></i>
							[profile-cpf key="gender"]
						</li>
						<li>
							<i class="fa fa-globe"></i>
							<a href="[profile-website]">[profile-website]</a>
						</li>
					</ul>
					<br />
					<div class="ppbio">
						[profile-bio]
					</div>
				</div>
			</div>
		</div>
STRUCTURE;
    }
}


