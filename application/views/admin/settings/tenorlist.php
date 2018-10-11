<?php
defined('BASEPATH') OR exit('');
?>

<?php echo isset($range) && !empty($range) ? "Showing ".$range : ""?>
<div class="panel panel-primary">
    <div class="panel-heading">TENORS</div>
    <?php if($allTenors):?>
    <div class="table table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>TENOR (months)</th>
                    <th>DISPLAY</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allTenors as $get):?>
                    <tr>
                        <th><?=$sn?>.</th>
                        <td class="tenor"><?=$get->tenor?></td>
                        <!-- <td class="hidden name"><?=$get->name?></td> -->
                        <td class="display"><?=$get->display?></td>
                        <!-- <td><?=date('jS M, Y h:i:sa', $get->created_on)?></td> -->
                        <td class="text-center editTenor" id="edit-<?=$get->id?>">
                            <i class="fa fa-pencil pointer"></i>
                        </td>
                        <td class="text-center text-danger deleteTenor" id="del-<?=$get->id?>">
                            <i class="fa fa-trash pointer"></i>
                        </td>
                    </tr>
                    <?php $sn++;?>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php else:?>
    No Tenors added
    <?php endif; ?>
</div>
<!-- Pagination -->
<div class="row text-center">
    <?php echo isset($links) ? $links : ""?>
</div>
<!-- Pagination ends -->