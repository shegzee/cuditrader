<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="login_hero" class="minimal"></div>
<section class="content content-centered content-auth">
	<h2>Create your Cudi Trader Account</h2>
	<p class="subheading">We're happy you're here.</p>

	<?php /*
	<form autocomplete="on" id="register-form" class="login-form">
	*/?>
	<?php
	echo form_open(current_url(), 'autocomplete="on" id="register-form" class="login-form"');
	?>
		<div class="form-message error">
			<i class="fa fa-exclamation-circle"></i><span class="form-message-label"><?= isset($_SESSION['auth_message']) ? "<p><span style='color:black'>".$_SESSION['auth_message']."</span></p>" : "" ?></span>
		</div>

			<?php
	echo form_error('first_name', '<div class="error">', '</div>');
	echo form_input('first_name', set_value('first_name'), "placeholder='First Name'");
	echo form_error('last_name', '<div class="error">', '</div>');
	echo form_input('last_name', set_value('last_name'), "placeholder='Last Name'");
	echo form_error('email', '<div class="error">', '</div>');
	echo form_input('email', set_value('email'), "placeholder='Email'");
	echo form_error('phone', '<div class="error">', '</div>');
	echo form_input('phone', set_value('phone'), "placeholder='Phone number'");
	echo form_error('address', '<div class="error">', '</div>');
	echo form_input('address', set_value('address'), "placeholder='Address'");
	echo form_error('password', '<div class="error">', '</div>');
	echo form_password('password', set_value('password'), "placeholder='Password'");
	echo form_error('confirm_password', '<div class="error">', '</div>');
	echo form_password('confirm_password', '', "placeholder='Confirm Password'");
	echo form_submit("register", "Create", "class='button button-primary'");
			?>
		<?php /*
		<input type="text" id="name" placeholder="Name">
		<input type="text" id="email" placeholder="Email">
		<input type="text" id="phone" placeholder="Phone Number">
		<input type="text" id="address" placeholder="Address">
		<input type="password" id="password" placeholder="Password">

		<button class="button button-primary"><span class="button-label">Create</span></button>
		*/
		?>
	</form>

	<div class="below" id="footer_hero">
		<a href="<?=base_url('auth/forgot_password') ?>">Lost your password?</a><!--
		--><span class="divider">・</span><!--
		--><!-- Already have an account?  --><a id="loginlink" href="<?=base_url('auth/login') ?>">Login</a>
	</div>
</section>


<section class="bottom-message">
	<p>Having trouble? <a href="#">See our Help Center</a></p>
</section>
</div>

<section class="bottom-message">
	<p>Having trouble? <a href="#">See our Help Center</a></p>
</section>