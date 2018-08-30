<?php
defined('BASEPATH') OR exit('');
?>

<div class="row hidden-print">
    <!-- banks here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new bank, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-bank-plus pointer" style="color:#337ab7" data-target='#addNewBankModal' data-toggle='modal'>
                        New Bank
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="bankListPerPage">Show</label>
                        <select id="bankListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="bankListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="bankListSortBy" class="control-label">Sort by</label> 
                        <select id="bankListSortBy" class="form-control">
                            <option value="name-ASC" selected>Name (A to Z)</option>
                            <option value="name-DESC">Name (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="description-ASC">Description - ascending</option>
                            <option value="description-DESC">Description - descending</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="bankSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="bankSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- bank list -->
            <div class="row">
                <div class="col-sm-12" id="allBank"></div>
            </div>
            <!-- bank list ends -->
        </div>
    </div>

    <!-- bank account types here -->
     <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new accountType, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-bank-plus pointer" style="color:#337ab7" data-target='#addNewAccountTypeModal' data-toggle='modal'>
                        New Account type
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="accountTypeListPerPage">Show</label>
                        <select id="accountTypeListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="accountTypeListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="accountTypeListSortBy" class="control-label">Sort by</label> 
                        <select id="accountTypeListSortBy" class="form-control">
                            <option value="name-ASC" selected>Name (A to Z)</option>
                            <option value="name-DESC">Name (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="description-ASC">Description - ascending</option>
                            <option value="description-DESC">Description - descending</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="accountTypeSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="accountTypeSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- accountType list -->
            <div class="row">
                <div class="col-sm-12" id="allAccountType"></div>
            </div>
            <!-- accountType list ends -->
        </div>
    </div>
</div>


<!-- Modal to add new bank -->
<div class='modal fade' id='addNewBankModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Add New Bank</h4>
                <div class="text-center">
                    <i id="fMsgIcon"></i><span id="fMsg"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewBankForm' name='addNewBankForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='name' class="control-label">Name</label>
                            <input type="text" id='name' class="form-control checkField" placeholder="Name">
                            <span class="help-block errMsg" id="nameErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='mobile2' class="control-label">Description</label>
                            <input type="tel" id='description' class="form-control" placeholder="Bank Description">
                            <span class="help-block errMsg" id="descriptionErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewBankForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='addBankSubmit' class="btn btn-primary">Add Bank</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end of modal to add new bank -->


<!-- Modal for editing bank details -->
<div class='modal fade' id='editBankModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit bank Info</h4>
                <div class="text-center">
                    <i id="fMsgEditIcon"></i>
                    <span id="fMsgEdit"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editBankForm' name='editBankForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='nameEdit' class="control-label">Name</label>
                            <input type="text" id='nameEdit' class="form-control checkField" placeholder="Name">
                            <span class="help-block errMsg" id="nameEditErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='descriptionEdit' class="control-label">Description</label>
                            <input type="tel" id='descriptionEdit' class="form-control" placeholder="Description">
                            <span class="help-block errMsg" id="descriptionEditErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="bankId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editBankForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='editBankSubmit' class="btn btn-primary">Update</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit bank details --->

<!-- modals for account types -->
<!-- Modal to add new account type -->
<div class='modal fade' id='addNewAccountTypeModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Add New Account Type</h4>
                <div class="text-center">
                    <i id="fMsgATIcon"></i><span id="fMsgAT"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewAccountTypeForm' name='addNewAccountTypeForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='nameAT' class="control-label">Name</label>
                            <input type="text" id='nameAT' class="form-control checkField" placeholder="Name">
                            <span class="help-block errMsg" id="nameATErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='descriptionAT' class="control-label">Description</label>
                            <input type="tel" id='descriptionAT' class="form-control" placeholder="Account Type Description">
                            <span class="help-block errMsg" id="descriptionATErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewAccountTypeForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='addAccountTypeSubmit' class="btn btn-primary">Add Account Type</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end of modal to add new account type -->


<!-- Modal for editing account type details -->
<div class='modal fade' id='editAccountTypeModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit Account Type</h4>
                <div class="text-center">
                    <i id="fMsgEditATIcon"></i>
                    <span id="fMsgEditAT"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editAccountTypeForm' name='editAccountTypeForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='nameATEdit' class="control-label">Name</label>
                            <input type="text" id='nameATEdit' class="form-control checkField" placeholder="Name">
                            <span class="help-block errMsg" id="nameATEditErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='descriptionATEdit' class="control-label">Description</label>
                            <input type="tel" id='descriptionATEdit' class="form-control" placeholder="Description">
                            <span class="help-block errMsg" id="descriptionATEditErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="accountTypeId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editAccountTypeForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='editAccountTypeSubmit' class="btn btn-primary">Update</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit account type --->

<script src="<?=base_url()?>public/js/bank.js"></script>