<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-08-28 20:39:11 --> Query error: Champ 'mobile' inconnu dans field list - Invalid query: SELECT `id`, `first_name`, `last_name`, `email`, `mobile`, `addr`, `created_on`, `last_login`, `account_status`, `deleted`
FROM `users`
WHERE `id` != '1'
ORDER BY `first_name` ASC
