<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="login_hero" class="minimal"></div>
      <section class="content content-centered content-auth">
            <h2>Change Password</h2>

<div class="<?= !isset($_SESSION['message']) ?'form-message':'' ?> error">
      <i class="fa fa-exclamation-circle"></i><span class="form-message-label"><?= isset($_SESSION['message']) ? "<p><span style='color:black'>".$_SESSION['message']."</span></p>" : "" ?></span>
</div>

<?php echo form_open("auth/change_password", 'class="login-form"');?>

      <p>
            <?php echo form_error($old_password['name'], '<div class="error">', '</div>');?>
            <?php echo form_input($old_password, set_value(''), "placeholder='Old Password'");?>
      </p>

      <p>
            <?php echo form_error($new_password['name'], '<div class="error">', '</div>');?>
            <?php echo form_input($new_password, set_value(''), "placeholder='New Password'");?>
      </p>

      <p>
            <?php echo form_error($new_password_confirm['name'], '<div class="error">', '</div>');?>
            <?php echo form_input($new_password_confirm, set_value(''), "placeholder='Confirm New Password'");?>
      </p>

      <?php echo form_input($user_id);?>
      <p><?php echo form_submit('submit', "Submit", "class='button button-primary'");?></p>

<?php echo form_close();?>
