<?php
defined('BASEPATH') OR exit('');
?>

<div class="row hidden-print">
    <!-- loan unit here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new loan unit, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-plus pointer" style="color:#337ab7" data-target='#addNewLUnitModal' data-toggle='modal'>
                        New Loan Unit
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="lunitListPerPage">Show</label>
                        <select id="lunitListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="lunitListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="lUnitListSortBy" class="control-label">Sort by</label> 
                        <select id="lUnitListSortBy" class="form-control">
                            <option value="name-ASC" selected>Name (A to Z)</option>
                            <option value="name-DESC">Name (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="logo-ASC">Logo - ascending</option>
                            <option value="logo-DESC">Logo - descending</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="lunitSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="lunitSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- loan unit list -->
            <div class="row">
                <div class="col-sm-12" id="allLUnit"></div>
            </div>
            <!-- loan unit list ends -->
        </div>
    </div>

    <!-- collateral units here -->
     <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new collateral unit, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-plus pointer" style="color:#337ab7" data-target='#addNewCUnitModal' data-toggle='modal'>
                        New Collateral Unit
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="cUnitListPerPage">Show</label>
                        <select id="cUnitListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="cUnitListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="cUnitListSortBy" class="control-label">Sort by</label> 
                        <select id="cUnitListSortBy" class="form-control">
                            <option value="name-ASC" selected>Name (A to Z)</option>
                            <option value="name-DESC">Name (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="logo-ASC">Logo - ascending</option>
                            <option value="logo-DESC">Logo - descending</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="cunitSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="cunitSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- cUnit list -->
            <div class="row">
                <div class="col-sm-12" id="allCUnit"></div>
            </div>
            <!-- cUnit list ends -->
        </div>
    </div>
</div>


<!-- Modal to add new loan unit -->
<div class='modal fade' id='addNewLUnitModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Add New Loan Unit</h4>
                <div class="text-center">
                    <i id="fMsgIcon"></i><span id="fMsg"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewLUnitForm' name='addNewLUnitForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='name' class="control-label">Name</label>
                            <input type="text" id='name' class="form-control checkField" placeholder="Name">
                            <span class="help-block errMsg" id="nameErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='logo' class="control-label">Logo</label>
                            <input type="tel" id='logo' class="form-control" placeholder="Logo">
                            <span class="help-block errMsg" id="logoErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewLUnitForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='addLUnitSubmit' class="btn btn-primary">Add Loan Unit</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end of modal to add new loan unit -->


<!-- Modal for editing loan unit details -->
<div class='modal fade' id='editLUnitModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit Loan Unit Info</h4>
                <div class="text-center">
                    <i id="fMsgEditIcon"></i>
                    <span id="fMsgEdit"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editLUnitForm' name='editLUnitForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='nameEdit' class="control-label">Name</label>
                            <input type="text" id='nameEdit' class="form-control checkField" placeholder="Name">
                            <span class="help-block errMsg" id="nameEditErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='logoEdit' class="control-label">Logo</label>
                            <input type="tel" id='logoEdit' class="form-control" placeholder="Logo">
                            <span class="help-block errMsg" id="logoEditErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="lUnitId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editLUnitForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='editLUnitSubmit' class="btn btn-primary">Update</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit collateral unit --->

<!-- modals for collateral unit -->
<!-- Modal to add new collateral unit -->
<div class='modal fade' id='addNewCUnitModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Add New Collateral Unit</h4>
                <div class="text-center">
                    <i id="fMsgCUIcon"></i><span id="fMsgCU"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewCUnitForm' name='addNewCUnitForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='nameCU' class="control-label">Name</label>
                            <input type="text" id='nameCU' class="form-control checkField" placeholder="Name">
                            <span class="help-block errMsg" id="nameCUErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='logoCU' class="control-label">Logo</label>
                            <input type="tel" id='logoCU' class="form-control" placeholder="Currency  Logo">
                            <span class="help-block errMsg" id="logoCUErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewCUnitForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='addCUnitSubmit' class="btn btn-primary">Add Collateral Unit</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end of modal to add new collateral unit -->


<!-- Modal for editing collateral unit details -->
<div class='modal fade' id='editCUnitModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit Collateral Unit</h4>
                <div class="text-center">
                    <i id="fMsgEditCUIcon"></i>
                    <span id="fMsgEditCU"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editCUnitForm' name='editCUnitForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='nameCUEdit' class="control-label">Name</label>
                            <input type="text" id='nameCUEdit' class="form-control checkField" placeholder="Name">
                            <span class="help-block errMsg" id="nameCUEditErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='logoCUEdit' class="control-label">Logo</label>
                            <input type="tel" id='logoCUEdit' class="form-control" placeholder="Logo">
                            <span class="help-block errMsg" id="logoCUEditErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="cUnitId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editCUnitForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='editCUnitSubmit' class="btn btn-primary">Update</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit collateral unit --->

<script src="<?=base_url()?>public/js/unit.js"></script>