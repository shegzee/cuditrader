<?php
defined('BASEPATH') OR exit('');
?>

<?php echo isset($range) && !empty($range) ? "Showing ".$range : ""?>
<div class="panel panel-primary">
    <div class="panel-heading">SITE SETTINGS</div>
    <?php if($allSettings):?>
    <div class="table table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>SETTING</th>
                    <th>VALUE</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allSettings as $get):?>
                    <tr>
                        <th><?=$sn?>.</th>
                        <td class="name"><?=$get->setting?></td>
                        <!-- <td class="hidden name"><?=$get->setting?></td> -->
                        <td class="logo"><?=$get->value?></td>
                        <!-- <td><?=date('jS M, Y h:i:sa', $get->created_on)?></td> -->
                        <td class="text-center editSetting" id="edit-<?=$get->id?>">
                            <i class="fa fa-pencil pointer"></i>
                        </td>
                        <td class="text-center text-danger deleteSetting" id="del-<?=$get->id?>">
                            <i class="fa fa-trash pointer"></i>
                        </td>
                    </tr>
                    <?php $sn++;?>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php else:?>
    No Settings added
    <?php endif; ?>
</div>
<!-- Pagination -->
<div class="row text-center">
    <?php echo isset($links) ? $links : ""?>
</div>
<!-- Pagination ends -->