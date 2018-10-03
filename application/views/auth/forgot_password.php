<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="login_hero" class="minimal"></div>
	<section class="content content-centered content-auth">
		<!-- <h2>Hello! Please log in.</h2>
		<p class="subheading">We're happy you're here.</p> -->

		<h2>Forgot Password</h2>
		<p class="subheading">Please enter your email address so we can send you an email to reset your password.</p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/forgot_password", 'autocomplete="on" id="login-form" class="login-form"');?>

      	<?php
      	echo form_error('email', '<div class="error">', '</div>');
      	echo form_input('email', set_value('email'), "placeholder='Email'");
      	
      	echo form_submit('submit', 'Submit', "class='button button-primary'");
      	?>

<?php echo form_close();?>

<div class="below" id="footer_hero">
			<a href="<?=base_url('auth/login') ?>" class="email-link">Login</a>
			<a id="registerlink" href="<?=base_url('auth/register') ?>">Sign up</a>

		</div>
	</section>
	</div>

	<section class="bottom-message">
		<p>Having trouble?
			<a href="#">See our Help Center</a>
		</p>
	</section>