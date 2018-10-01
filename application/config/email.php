<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.zoho.com';
$config['smtp_user'] = 'info@cuditrader.com';
$config['smtp_pass'] = 'P@55word321';
$config['smtp_port'] = 465;
$config['smtp_crypto'] = 'SSL';
$config['mailtype'] = 'html';
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['bcc_batch_size'] = 2000;