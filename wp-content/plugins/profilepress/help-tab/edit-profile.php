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
		<td><strong>[edit-profile-username]</strong></td>
		<td>Output the username field of the edit profile form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field</p>
			<p><strong>class</strong>: &nbsp; space-separated list of CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-password]</strong></td>
		<td>Output the password field of the edit profile form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-email]</strong></td>
		<td>Output the email field of the edit profile form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-website]</strong></td>
		<td>Output the website field of the edit profile form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-nickname]</strong></td>
		<td>Output the nickname field of the edit profile form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-display-name]</strong></td>
		<td>Output the display name field of the edit profile form.</td>
		<td>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-first-name]</strong></td>
		<td>Output the first-name field of the edit profile form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-last-name]</strong></td>
		<td>Output a last-name field of the edit profile form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-bio]</strong></td>
		<td>Output the user description or bio textarea field of the  edit profile form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
		<tr>
		<td><strong>[remove-user-avatar]</strong></td>
		<td>Output a button when clicked, will delete the user's profile picture.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>label</strong>: &nbsp; defines the text on the button.</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[edit-profile-avatar]</strong></td>
		<td>Output the user description or bio textarea field of the  edit profile form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
		<tr>
		<td><strong>[edit-profile-cpf]</strong></td>
		<td>Output a custom profile field for the edit profile form.</td>
		<td>
			<p><strong>key</strong>: &nbsp; the key for the custom profile field</p>
			<p><strong>type</strong>: &nbsp; the type of custom profile field</p>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
		<tr>
		<td><strong>[edit-profile-submit]</strong></td>
		<td>Output the submit button of the edit profile form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the submit button text.</p>
			<p><strong>required</strong>: mark this field as required.</p>
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
