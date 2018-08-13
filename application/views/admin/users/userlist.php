<?php
defined('BASEPATH') OR exit('');
?>

<?php echo isset($range) && !empty($range) ? "Showing ".$range : ""?>
<div class="panel panel-primary">
    <div class="panel-heading">USER ACCOUNTS</div>
    <?php if($allUsers):?>
    <div class="table table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>NAME</th>
                    <th>E-MAIL</th>
                    <th>MOBILE</th>
                    <th>HOME ADDRESS</th>
                    <th>DATE CREATED</th>
                    <th>LAST LOG IN</th>
                    <th>EDIT</th>
                    <th>ACCOUNT STATUS</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allUsers as $get):?>
                    <tr>
                        <th><?=$sn?>.</th>
                        <td class="userName"><?=$get->first_name ." ". $get->last_name?></td>
                        <td class="hidden firstName"><?=$get->first_name?></td>
                        <td class="hidden lastName"><?=$get->last_name?></td>
                        <td class="userEmail"><?=mailto($get->email)?></td>
                        <td class="userMobile"><?=$get->mobile?></td>
                        <td class="userAddress"><?=$get->addr?></td>
                        <td><?=date('jS M, Y h:i:sa', strtotime($get->created_on))?></td>
                        <td>
                            <?=$get->last_login === "0000-00-00 00:00:00" ? "---" : date('jS M, Y h:i:sa', strtotime($get->last_login))?>
                        </td>
                        <td class="text-center edituser" id="edit-<?=$get->id?>">
                            <i class="fa fa-pencil pointer"></i>
                        </td>
                        <td class="text-center suspenduser text-success" id="sus-<?=$get->id?>">
                            <?php if($get->account_status === "1"): ?>
                            <i class="fa fa-toggle-on pointer"></i>
                            <?php else: ?>
                            <i class="fa fa-toggle-off pointer"></i>
                            <?php endif; ?>
                        </td>
                        <td class="text-center text-danger deleteuser" id="del-<?=$get->id?>">
                            <?php if($get->deleted === "1"): ?>
                            <a class="pointer">Undo Delete</a>
                            <?php else: ?>
                            <i class="fa fa-trash pointer"></i>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $sn++;?>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php else:?>
    No User Accounts
    <?php endif; ?>
</div>
<!-- Pagination -->
<div class="row text-center">
    <?php echo isset($links) ? $links : ""?>
</div>
<!-- Pagination ends -->