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
		<td><strong>[profile-username]</strong></td>
		<td>Output the username of a user.</td>
		<td>
		<p>No attribute required</p>
		</td>
	</tr>
	<tr>
		<td><strong>[profile-email]</strong></td>
		<td>Output the email address of a user.</td>
		<td>
		<p>No attribute required</p>
		</td>
	</tr>
	<tr>
		<td><strong>[profile-website]</strong></td>
		<td>Output the website URL of a user.</td>
		<td>
			<p>No attribute required</p>
		</td>
	</tr>
	<tr>
		<td><strong>[profile-nickname]</strong></td>
		<td>Output the nickname of a user.</td>
		<td>
			<p>No attribute required</p>
		</td>
	</tr>
	<tr>
		<td><strong>[profile-display-name]</strong></td>
		<td>Output the display name of a user.</td>
		<td>
			<p>No attribute required</p>
		</td>
	</tr>
	<tr>
		<td><strong>[profile-first-name]</strong></td>
		<td>Output the first name of a user.</td>
		<td>
			<p>No attribute required</p>
		</td>
	</tr>
	<tr>
		<td><strong>[profile-last-name]</strong></td>
		<td>Output the last name of a user.</td>
		<td>
			<p>No attribute required</p>
		</td>
	</tr>
	<tr>
		<td><strong>[profile-bio]</strong></td>
		<td>Output the description or biography of a user.</td>
		<td>
			<p>No attribute required</p>
		</td>
	</tr>
		<tr>
		<td><strong>[profile-cpf]</strong></td>
		<td>Output the data of a custom profile field.</td>
		<td>
			<p><strong>key</strong>: &nbsp; the custom field key.</p>
		</td>
	</tr>
		<tr>
		<td><strong>[profile-file]</strong></td>
		<td>Output link to a user uploaded file.</td>
		<td>
			<p><strong>key</strong>: &nbsp; the custom field key of the file.</p>
			<p><strong>raw</strong>: &nbsp; set to true to output the URL to the file.</p>
		</td>
	</tr>
	<tr>
		<td><strong>[profile-avatar-url]</strong></td>
		<td>Output the profile picture or avatar image url of a user.</td>
		<td>
			<p>No attribute required</p>
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
