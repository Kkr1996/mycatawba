<?php
$asset = ASSETS_URL;

return <<<HTML
<table class="widefat">
	<thead>
	<tr>
		<th class="row-title">Description</th>
		<th>Shortcodes</th>
		<th>Attributes</th>
	</tr>
	</thead>
	<tbody>
	<tr>

		<td><p>Output a Facebook social login button.</p>
		<p><img src="{$asset}/images/zocial/fb-button.png" /> </p></td>
		<td class="row-title">
			<strong>[social-button type="facebook" size="13"]<br> Sign in with Facebook<br> [/social-button] </strong>
		</td>
		<td>
			<p><strong>size</strong>: &nbsp; font size of the social button text</p>
		</td>
	</tr>
	<tr>

		<td><p>Output a Twitter social login button</p>
		<p><img src="{$asset}/images/zocial/twit-button.png" /> </p></td>
		<td class="row-title">
			<strong>[social-button type="twitter" size="13"]<br> Sign in with Twitter<br> [/social-button] </strong>
		</td>
		<td>
			<p><strong>size</strong>: &nbsp; font size of the social button text</p>
		</td>
	</tr>
	<tr>

		<td><p>Output a Google social login button</p>
		<p><img src="{$asset}/images/zocial/goog-button.png" /> </p>
		</td>
		<td class="row-title">
			<strong>[social-button type="google" size="13"]<br> Sign in with Google<br> [/social-button] </strong>
		</td>
		<td>
			<p><strong>size</strong>: &nbsp; font size of the social button text</p>
		</td>
	</tr>
	<tr>

		<td><p>Output a LinkedIn social login button</p>
		<p><img src="{$asset}/images/zocial/link-button.png" /> </p></td>
		<td class="row-title">
			<strong>[social-button type="linkedin" size="13"]<br> Sign in with LinkedIn<br> [/social-button] </strong>
		</td>
		<td>
			<p><strong>size</strong>: &nbsp; font size of the social button text</p>
		</td>
	</tr>
	<tr>

		<td><p>Output a Github social login button</p>
		<p><img src="{$asset}/images/zocial/gith-button.png" /> </p></td>
		<td class="row-title">
			<strong>[social-button type="github" size="13"]<br> Sign in with Github<br> [/social-button] </strong>
		</td>
		<td>
			<p><strong>size</strong>: &nbsp; font size of the social button text</p>
		</td>
	</tr>
	<tr>

		<td><p>Output a VK.com social login button</p>
		<p><img src="{$asset}/images/zocial/vk-button.png" /> </p></td>
		<td class="row-title">
			<strong>[social-button type="vk" size="13"]<br> Sign in with VK<br> [/social-button] </strong>
		</td>
		<td>
			<p><strong>size</strong>: &nbsp; font size of the social button text</p>
		</td>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<th class="row-title">Description</th>
		<th>Shortcodes</th>
		<th>Attributes</th>
	</tr>
	</tfoot>
</table>


HTML;

