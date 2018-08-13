<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
	<head>
		<title>Cudi Trader<?= isset($page_title) ? " - ".$page_title : "" ?></title></head>
	<body>
		<?php // if (user->loggedin) // probably do this check in controller and set $data['taskbar'] to appropriate partial view taskbar ?>

		<?= isset($user_bar) ? $user_bar : "" ?><!-- set in controller: if user is logged in, send a bar containing user stuff, else send a bar with "login", etc. templated already _sha_ -->
		<nav>
			<!-- then, put the navigation... -->
			<div>
				<ul>
					<li class="<?= $page_title == 'Home' ? 'active' : '' ?>">
						<a href="<?= base_url() ?>">
							Home
						</a>
					</li>
					<li class="<?= $page_title == 'About' ? 'active' : '' ?>">
						<a href="<?= base_url('about') ?>">
							About
						</a>
					</li>
					<li style="float: right;">
						<?= isset($current_user) ? $current_user->username." - ".anchor('user/logout', 'Logout') : anchor('user/login', 'Login'); ?>
					</li>
				</ul>
			</div>
		</nav>

		<!-- sidebar? -->
		<!-- <div>main page content -->