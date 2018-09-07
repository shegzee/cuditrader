<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/_parts/pages_header'); ?>

<?= isset($page_content) ? $page_content : "" ?>


<?php $this->load->view('templates/_parts/pages_footer');?>