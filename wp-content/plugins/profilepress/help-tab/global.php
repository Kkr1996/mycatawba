<?php
return <<<DOC

<table class="widefat">
	<thead>
	<tr>
		<th>Shortcodes</th>
		<th>Description</th>
		<th class="row-title">Attributes</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><strong>[user-avatar]</strong></td>
		<td>Output a profile picture or avatar of a user.</td>
		<td>
			<p><strong>size</strong>: &nbsp; size of the image. it serves as the image width and height.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>alt</strong>: &nbsp; specifies an alternate text for the image.</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the element.</p>
			<p><strong>default</strong>: default image url if no profile picture is found for a user.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[user-avatar-url]</strong></td>
		<td>Output a user's profile picture or avatar URL.</td>
		<td>
			<p>No attribute.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[link-registration]</strong></td>
		<td>Output a link to the registration page.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the element.</p>
			<p><strong>label</strong>: the anchor text for the link.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[link-edit-user-profile]</strong></td>
		<td>Output a remember-me checkbox for the login form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the element.</p>
			<p><strong>label</strong>: the anchor text for the link.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[link-lost-password]</strong></td>
		<td>Output a link to the password reset page</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the element.</p>
			<p><strong>label</strong>: the anchor text for the link.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[link-login]</strong></td>
		<td>Output a link to the password reset page</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the element.</p>
			<p><strong>label</strong>: the anchor text for the link.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[link-logout]</strong></td>
		<td>Output a link to the password reset page</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the element.</p>
			<p><strong>label</strong>: the anchor text for the link.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[pp-recaptcha]</strong></td>
		<td>Display reCAPTCHA to protect the form against spam</td>
		<td>
			<p>No attribute.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[facebook-login-url]</strong></td>
		<td>Output a URL to login with Facebook.</td>
		<td>
			<p>No attribute.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[twitter-login-url]</strong></td>
		<td>Output a URL to login with Twitter.</td>
		<td>
			<p>No attribute.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[linkedin-login-url]</strong></td>
		<td>Output a URL to login with LinkedIn.</td>
		<td>
			<p>No attribute.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[google-login-url]</strong></td>
		<td>Output a URL to login with Google.</td>
		<td>
			<p>No attribute.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[github-login-url]</strong></td>
		<td>Output a URL to login with GitHub.</td>
		<td>
			<p>No attribute.</p>
		</td>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<th>Shortcodes</th>
		<th>Description</th>
		<th class="row-title">Attributes</th>
	</tr>
	</tfoot>
</table>
DOC;
