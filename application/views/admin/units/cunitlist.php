<?php
defined('BASEPATH') OR exit('');
?>

<?php echo isset($range) && !empty($range) ? "Showing ".$range : ""?>
<div class="panel panel-primary">
    <div class="panel-heading">COLLATERAL UNITS</div>
    <?php if($allCUnits):?>
    <div class="table table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>NAME</th>
                    <th>LOGO</th>
                    <th>API URL</th>
                    <th>MARKUP</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allCUnits as $get):?>
                    <tr>
                        <th><?=$sn?>.</th>
                        <td class="name"><?=$get->name?></td>
                        <!-- <td class="hidden name"><?=$get->name?></td> -->
                        <td class="logo"><?=$get->logo?></td>
                        <td class="api_url"><?=$get->api_url?></td>
                        <td class="markup"><?=$get->markup?></td>
                        <!-- <td><?=date('jS M, Y h:i:sa', $get->created_on)?></td> -->
                        <td class="text-center editCUnit" id="edit-<?=$get->id?>">
                            <i class="fa fa-pencil pointer"></i>
                        </td>
                        <td class="text-center text-danger deleteCUnit" id="del-<?=$get->id?>">
                            <i class="fa fa-trash pointer"></i>
                        </td>
                    </tr>
                    <?php $sn++;?>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php else:?>
    No Collateral Units added
    <?php endif; ?>
</div>
<!-- Pagination -->
<div class="row text-center">
    <?php echo isset($links) ? $links : ""?>
</div>
<!-- Pagination ends -->