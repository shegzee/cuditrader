<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/_parts/main_header'); ?>

<?= isset($page_content) ? $page_content : "" ?>


<?php $this->load->view('templates/_parts/main_footer');?>