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
		<td><strong>[reg-username]</strong></td>
		<td>Output a username field of the registration form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field</p>
			<p><strong>class</strong>: &nbsp; space-separated list of CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-password]</strong></td>
		<td>Output a registration form's password field</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-confirm-password]</strong></td>
		<td>Output a registration form's password confirmation field</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
		</td>
	</tr>
		<tr>
		<td><strong>[reg-password-meter]</strong></td>
		<td>Output password strength meter.</td>
		<td>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>enforce</strong>: &nbsp; enforce strong password rule for registering users.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-email]</strong></td>
		<td>Output a password field of the registration form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-website]</strong></td>
		<td>Output a website field of the registration form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-nickname]</strong></td>
		<td>Output a nickname field of the registration form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-display-name]</strong></td>
		<td>Output the display-name field of the registration form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-first-name]</strong></td>
		<td>Output the first-name field of the registration form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-last-name]</strong></td>
		<td>Output a last-name field of the registration form</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[reg-bio]</strong></td>
		<td>Output the user description or biography textarea field of the registration form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present in the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
		<tr>
		<td><strong>[reg-avatar]</strong></td>
		<td>Output the avatar or profile picture upload field of the registration form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
		<tr>
		<td><strong>[reg-cpf]</strong></td>
		<td>Output a registration form field for a <a href="?page=pp-cpf">custom profile field</a>.</td>
		<td>
			<p><strong>key</strong>: &nbsp; The key for the custom profile field</p>
			<p><strong>type</strong>: &nbsp; The type of custom profile field</p>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>placeholder</strong>: &nbsp; short hint that describes the expected value of the field</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the initial value present if the field is a text input field.</p>
			<p><strong>required</strong>: mark this field as required.</p>
		</td>
	</tr>
		<tr>
		<td><strong>[reg-submit]</strong></td>
		<td>Output the submit button of the registration form.</td>
		<td>
			<p><strong>title</strong>: &nbsp; advisory information about the field.</p>
			<p><strong>class</strong>: &nbsp; space-separated list of the CSS classes</p>
			<p><strong>id</strong>: &nbsp; unique identifier to identify the field.</p>
			<p><strong>value</strong>: the submit button text.</p>
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
