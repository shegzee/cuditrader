<?php
defined('BASEPATH') OR exit('');
?>

<?php echo isset($range) && !empty($range) ? "Showing ".$range : ""?>
<div class="panel panel-primary">
    <div class="panel-heading">BANK ACCOUNT TYPES</div>
    <?php if($allBankATs):?>
    <div class="table table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>NAME</th>
                    <th>DESCRIPTION</th>
                    <!-- <th>DATE CREATED</th> -->
                    <!-- <th>DELETE</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach($allBankATs as $get):?>
                    <tr>
                        <th><?=$sn?>.</th>
                        <td class="name"><?=$get->name?></td>
                        <!-- <td class="hidden name"><?=$get->name?></td> -->
                        <td class="description"><?=$get->description?></td>
                        <!-- <td><?=date('jS M, Y h:i:sa', $get->created_on)?></td> -->
                        <td class="text-center editAccountType" id="edit-<?=$get->id?>">
                            <i class="fa fa-pencil pointer"></i>
                        </td>
                        <!-- <td class="text-center text-danger deleteBank" id="del-<?=$get->id?>">
                            <?php if($get->deleted === "1"): ?>
                            <a class="pointer">Undo Delete</a>
                            <?php else: ?>
                            <i class="fa fa-trash pointer"></i>
                            <?php endif; ?>
                        </td> -->
                    </tr>
                    <?php $sn++;?>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php else:?>
    No Bank account types
    <?php endif; ?>
</div>
<!-- Pagination -->
<div class="row text-center">
    <?php echo isset($links) ? $links : ""?>
</div>
<!-- Pagination ends -->