<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<p class="header-description">Welcome to your account settings.</p>

      <div class="profile-details first-section">
        <h3>Profile Info</h3>

        <div class="table-wrapper">
          <table>
            <tbody>
              <tr data-email-id="5698536">
                <td class="email-row">
                  <span class="emails-email"><?= $user->email; ?></span>
                  <!-- if there's only one email address, we shouldn't show default -->
                  <span class="status status-primary">Primary</span>
                  <?php if ($user->active): ?>
                    <span class="status">Verified</span>
                  <?php else: ?>
                    <span class="status status-error">Unverified</span>
                  <?php endif; ?>
                  <span class="actions">
                    <?php if (!$user->active): ?>
                      <span class="status action resend">Resend verification</span>
                    <?php endif; ?>
                    <!-- <span class="status action edit"><a href="<?= base_url('user/change_email') ?>">Change email</a></span> -->
                  </span>
                  <span class="status"><a href="<?= base_url('auth/change_password') ?>">Change Password</a></span>
                </td>
                  <!-- <td class="date">Joined on: <?= date('jS M, Y', $user->created_on) ; ?></td> -->
              </tr>
            </tbody>
          </table>
        </div>
<?= isset($_SESSION['message']) ? "<p>".$_SESSION['message']."</p>" : FALSE ?>

        <!-- <form autocomplete="on" id="profile-form"> -->
        <?= form_open('user/edit_profile') ?>
          <div class="input-group">
        <?php
        	echo form_error('full_name');
        	echo form_label('Full Name', 'full_name');
        	echo form_input('full_name', set_value('full_name', $user->full_name));
        ?>
<!--             <label>Full Name</label>
            <input id="full_name" type="text" value="<?= $user->full_name; ?>"> -->
          </div>

          <div class="input-group">
        <?php
        	echo form_error('phone');
        	echo form_label('Phone Number', 'phone');
        	echo form_input('phone', set_value('phone', $user->phone));
        ?>
          </div>

          <div class="input-group">
        <?php
        	echo form_error('address');
        	echo form_label('Address', 'address');
        	echo form_input('address', set_value('address', $user->address));
        ?>
          </div>

          <button class="button">Save</button>
        </form>
      </div>