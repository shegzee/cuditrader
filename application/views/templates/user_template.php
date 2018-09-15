<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/_parts/profile_pages_header'); ?>
<div class="wrapper" id="profile_hero">
    <nav class="side-nav">
      <a href="<?= base_url('user/profile'); ?>" class="<?= isset($page_title) && $page_title=='Profile' ? 'active' : '' ?>">Profile</a>
      <!--<hr>-->

      <a href="<?= base_url('user/bank'); ?>"  class="<?= isset($page_title) && stristr($page_title, 'Bank') ? 'active' : '' ?>">Bank Accounts</a>
      <a href="<?= base_url('user/collaterals'); ?>" class="<?= isset($page_title) && $page_title=='Collaterals' ? 'active' : '' ?>">Collaterals</a>
      <a href="<?= base_url('user/loans'); ?>" class="<?= isset($page_title) && stristr($page_title, 'Loans') ? 'active' : '' ?>">Loans</a>
    </nav>
    <section class="content profile">
      <h2>Hello, <?= $user->full_name; ?>.</h2>
	<?= isset($page_content) ? $page_content : "" ?>
	</section>
</div>

<?php $this->load->view('templates/_parts/pages_footer');?>