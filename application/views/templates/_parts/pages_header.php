<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<!-- saved from url=(0034)cuditrader.com/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Cudi Trader provides financial products for long-term cryptocurrency owners.">
    <title><?= isset($page_title) ? $page_title." | " : "" ?>Cudi Trader</title>

    <link rel="stylesheet" href="<?=base_url()?>public/css/podium.css">
    <link rel="stylesheet" href="<?=base_url()?>public/css/fok3hxk.css">

<link rel="apple-touch-icon" sizes="180x180" href="<?=base_url()?>public/images/favicons/apple-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?=base_url()?>public/images/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>public/images/favicons/favicon-16x16.png">
<link rel="manifest" href="<?=base_url()?>public/images/favicons/manifest.json">
<link rel="mask-icon" href="<?=base_url()?>public/images/favicons/safari-pinned-tab.svg" color="#387EEC">
<link rel="shortcut icon" href="<?=base_url()?>public/images/favicons/favicon.ico">
<meta name="msapplication-config" content="<?=base_url()?>public/images/favicons/browserconfig.xml">
<meta name="theme-color" content="#ffffff">

  </head>
  <body>
    <nav class="navbar fixed-top bg-light">
  <div class="container">
    <a data-category="home" data-action="goto" data-label="from-navbar" class="track-click navbar-brand unchained-banner" href="<?= base_url() ?>" title="Home - Cudi Trader">
      <img src="<?=base_url()?>public/images/test_banner.png" alt="Cudi Trader banner logo" title="Cudi trader">
    </a>
    <ul class="nav">
      <li class="nav-item d-none d-lg-inline-block">
        <a href="<?= base_url('loans') ?>" class="nav-link big-link track-click" data-category="loans" data-action="goto" data-label="from-navbar-expanded">Loans</a>
      </li>
      <li class="nav-item d-none d-lg-inline-block">
        <a href="<?= base_url('how_it_works') ?>" class="nav-link big-link track-click" data-category="how-it-works" data-action="goto" data-label="from-navbar-expanded">How It Works</a>
      </li>
      <li class="nav-item d-none d-xl-inline-block">
        <a href="<?= base_url('about') ?>" class="nav-link big-link track-click" data-category="about-us" data-action="goto" data-label="from-navbar-expanded">About Us</a>
      </li>
    </ul>
    <form class="form-inline">
      <?php if (isset($current_user)) { ?>
        <a href="<?= base_url('user/logout') ?>" class="btn btn-primary mr-1 track-click" data-category="sign-in" data-action="goto" data-label="from-navbar">Sign Out</a>
      <a href="<?= base_url('loan/') ?>" class="btn btn-outline-primary track-click" data-category="invite" data-action="goto" data-label="from-navbar">Loans</a>
      <a href="<?= base_url('user/profile') ?>"><img src="<?= $user->profile->picture_url; ?>" height="40" width="40" style="border-radius: 50%" alt="<?=$user->username; ?>" title="Profile" /></a>
      <?php } else { ?>
      <a href="<?= base_url('user/login') ?>" class="btn btn-primary mr-1 track-click" data-category="sign-in" data-action="goto" data-label="from-navbar">Sign In</a>
      <a href="<?= base_url('user/register') ?>" class="btn btn-outline-primary track-click" data-category="invite" data-action="goto" data-label="from-navbar">Sign Up</a>
      <?php } ?>

      
    </form>
    <button data-category="navbar" data-action="toggle" class="navbar-toggler d-xl-none track-click" type="button" data-toggle="collapse" data-target="#mainNavContent" aria-controls="mainNavContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
  </div>
  <div class="container justify-content-end" id="subNavItems">
    <div id="mainNavContent" class="collapse">
      <ul class="navbar-nav">
        <li class="nav-item d-lg-none">
          <a href="<?=base_url('loans')?>" class="nav-link big-link track-click" data-category="loans" data-action="goto" data-label="from-navbar-collapsed">Loans</a>
        </li>
        <li class="nav-item d-lg-none">
          <a href="<?=base_url('how_it_works')?>" class="nav-link big-link track-click" data-category="how-it-works" data-action="goto" data-label="from-navbar-collapsed">How It Works</a>
        </li>
        <li class="nav-item d-xl-none">
          <a href="<?=base_url('about')?>" class="nav-link big-link track-click" data-category="about-us" data-action="goto" data-label="from-navbar-collapsed">About Us</a>
        </li>
        <li class="nav-item">
          <a href="/#" class="nav-link big-link track-click" data-category="blog" data-action="goto" data-label="from-navbar-collapsed" target="_blank">Blog</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<p>
<?= isset($_SESSION['auth_message']) ? $_SESSION['auth_message'] : FALSE ?>
</p>