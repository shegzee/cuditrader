<?php
defined('BASEPATH') OR exit('');
?>

<div class="row hidden-print">
    <!-- setting here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new setting, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-plus pointer" style="color:#337ab7" data-target='#addNewSettingModal' data-toggle='modal'>
                        New Setting
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="settingsListPerPage">Show</label>
                        <select id="settingsListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="settingsListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="settingsListSortBy" class="control-label">Sort by</label> 
                        <select id="settingsListSortBy" class="form-control">
                            <option value="setting-ASC" selected>Setting (A to Z)</option>
                            <option value="setting-DESC">Setting (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="value-ASC">Value - ascending</option>
                            <option value="value-DESC">Value - descending</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="settingsSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="settingsSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- setting list -->
            <div class="row">
                <div class="col-sm-12" id="allSettings"></div>
            </div>
            <!-- setting list ends -->
        </div>
    </div>

    <!-- tenor here -->
     <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new tenor, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-plus pointer" style="color:#337ab7" data-target='#addNewTenorModal' data-toggle='modal'>
                        New Tenor
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="tenorListPerPage">Show</label>
                        <select id="tenorListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="tenorListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="tenorListSortBy" class="control-label">Sort by</label> 
                        <select id="tenorListSortBy" class="form-control">
                            <option value="tenor-ASC" selected>Tenor (A to Z)</option>
                            <option value="tenor-DESC">Tenor (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="display-ASC">Display - ascending</option>
                            <option value="display-DESC">Display - descending</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="tenorSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="tenorSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- cUnit list -->
            <div class="row">
                <div class="col-sm-12" id="allTenors"></div>
            </div>
            <!-- cUnit list ends -->
        </div>
    </div>
</div>


<!-- Modal to add new setting -->
<div class='modal fade' id='addNewSettingModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Add New Setting</h4>
                <div class="text-center">
                    <i id="fMsgIcon"></i><span id="fMsg"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewSettingForm' name='addNewSettingForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='setting' class="control-label">Setting</label>
                            <input type="text" id='setting' class="form-control checkField" placeholder="Setting">
                            <span class="help-block errMsg" id="settingErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='value' class="control-label">Value</label>
                            <input type="text" id='value' class="form-control" placeholder="Value">
                            <span class="help-block errMsg" id="valueErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewSettingForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='addSettingSubmit' class="btn btn-primary">Add Setting</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end of modal to add new setting -->


<!-- Modal for editing setting details -->
<div class='modal fade' id='editSettingModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit Setting</h4>
                <div class="text-center">
                    <i id="fMsgEditIcon"></i>
                    <span id="fMsgEdit"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editSettingForm' name='editSettingForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='settingEdit' class="control-label">Setting</label>
                            <input type="text" id='settingEdit' class="form-control checkField" placeholder="Setting">
                            <span class="help-block errMsg" id="settingEditErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='valueEdit' class="control-label">Value</label>
                            <input type="text" id='valueEdit' class="form-control" placeholder="Value">
                            <span class="help-block errMsg" id="valueEditErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="settingId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editSettingForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='editSettingSubmit' class="btn btn-primary">Update</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit setting --->

<!-- modals for Tenor -->
<!-- Modal to add new Tenor -->
<div class='modal fade' id='addNewTenorModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Add New Tenor</h4>
                <div class="text-center">
                    <i id="fMsgTenorIcon"></i><span id="fMsgTenor"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewTenorForm' name='addNewTenorForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='tenor' class="control-label">Tenor</label>
                            <input type="text" id='tenor' class="form-control checkField" placeholder="Tenor (months)">
                            <span class="help-block errMsg" id="tenorErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='display' class="control-label">Display</label>
                            <input type="text" id='display' class="form-control" placeholder="Tenor Display Text">
                            <span class="help-block errMsg" id="displayErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewTenorForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='addTenorSubmit' class="btn btn-primary">Add Tenor</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end of modal to add new Tenor -->


<!-- Modal for editing Tenor details -->
<div class='modal fade' id='editTenorModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit Tenor</h4>
                <div class="text-center">
                    <i id="fMsgEditTenorIcon"></i>
                    <span id="fMsgEditTenor"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editTenorForm' name='editTenorForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='tenorEdit' class="control-label">Tenor</label>
                            <input type="text" id='tenorEdit' class="form-control checkField" placeholder="tenor">
                            <span class="help-block errMsg" id="tenorEditErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='displayEdit' class="control-label">Display</label>
                            <input type="text" id='displayEdit' class="form-control" placeholder="display">
                            <span class="help-block errMsg" id="displayEditErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="tenorId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editTenorForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='editTenorSubmit' class="btn btn-primary">Update</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit tenor --->

<script src="<?=base_url()?>public/js/settings.js"></script>