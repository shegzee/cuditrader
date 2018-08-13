<?php
defined('BASEPATH') OR exit('');
?>

<div class="row hidden-print">
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new user, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-user-plus pointer" style="color:#337ab7" data-target='#addNewuserModal' data-toggle='modal'>
                        New User
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="userListPerPage">Show</label>
                        <select id="userListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="userListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="userListSortBy" class="control-label">Sort by</label> 
                        <select id="userListSortBy" class="form-control">
                            <option value="first_name-ASC" selected>Name (A to Z)</option>
                            <option value="first_name-DESC">Name (Z to A)</option>
                            <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option>
                            <option value="email-ASC">E-mail - ascending</option>
                            <option value="email-DESC">E-mail - descending</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="userSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="userSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- user list -->
            <div class="row">
                <div class="col-sm-12" id="allUser"></div>
            </div>
            <!-- user list ends -->
        </div>
    </div>
</div>


<!-- Modal to add new user -->
<div class='modal fade' id='addNewuserModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Add New User</h4>
                <div class="text-center">
                    <i id="fMsgIcon"></i><span id="fMsg"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewuserForm' name='addNewuserForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='firstName' class="control-label">First Name</label>
                            <input type="text" id='firstName' class="form-control checkField" placeholder="First Name">
                            <span class="help-block errMsg" id="firstNameErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='lastName' class="control-label">Last Name</label>
                            <input type="text" id='lastName' class="form-control checkField" placeholder="Last Name">
                            <span class="help-block errMsg" id="lastNameErr"></span>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='email' class="control-label">Email</label>
                            <input type="email" id='email' class="form-control checkField" placeholder="Email">
                            <span class="help-block errMsg" id="emailErr"></span>
                        </div>
                        <!-- <div class="form-group-sm col-sm-6">
                            <label for='role' class="control-label">Role</label>
                            <select class="form-control checkField" id='role'>
                                <option value=''>Role</option>
                                <option value='Super'>Super</option>
                                <option value='Basic'>Basic</option>
                            </select>
                            <span class="help-block errMsg" id="roleErr"></span>
                        </div> -->
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='mobile' class="control-label">Phone Number</label>
                            <input type="tel" id='mobile' class="form-control checkField" placeholder="Phone Number">
                            <span class="help-block errMsg" id="mobileErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='mobile2' class="control-label">Address</label>
                            <input type="tel" id='mobile2' class="form-control" placeholder="Home Address">
                            <span class="help-block errMsg" id="lastNameErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for="passwordOrig" class="control-label">Password:</label>
                            <input type="password" class="form-control checkField" id="passwordOrig" placeholder="Password">
                            <span class="help-block errMsg" id="passwordOrigErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for="passwordDup" class="control-label">Retype Password:</label>
                            <input type="password" class="form-control checkField" id="passwordDup" placeholder="Retype Password">
                            <span class="help-block errMsg" id="passwordDupErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewuserForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='adduserSubmit' class="btn btn-primary">Add User</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end of modal to add new user -->


<!-- Modal for editing user details -->
<div class='modal fade' id='edituserModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit user Info</h4>
                <div class="text-center">
                    <i id="fMsgEditIcon"></i>
                    <span id="fMsgEdit"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='edituserForm' name='edituserForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='firstNameEdit' class="control-label">First Name</label>
                            <input type="text" id='firstNameEdit' class="form-control checkField" placeholder="First Name">
                            <span class="help-block errMsg" id="firstNameEditErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='lastNameEdit' class="control-label">Last Name</label>
                            <input type="text" id='lastNameEdit' class="form-control checkField" placeholder="Last Name">
                            <span class="help-block errMsg" id="lastNameEditErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='emailEdit' class="control-label">Email</label>
                            <input type="email" id='emailEdit' class="form-control checkField" placeholder="Email">
                            <span class="help-block errMsg" id="emailEditErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='roleEdit' class="control-label">Role</label>
                            <select class="form-control checkField" id='roleEdit'>
                                <option value=''>Role</option>
                                <option value='Super'>Super</option>
                                <option value='Basic'>Basic</option>
                            </select>
                            <span class="help-block errMsg" id="roleEditErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='mobileEdit' class="control-label">Phone Number</label>
                            <input type="tel" id='mobileEdit' class="form-control checkField" placeholder="Phone Number">
                            <span class="help-block errMsg" id="mobileEditErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='mobile2Edit' class="control-label">Other Number</label>
                            <input type="tel" id='mobile2Edit' class="form-control" placeholder="Other Number (optional)">
                            <span class="help-block errMsg" id="mobile2EditErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="userId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="edituserForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='edituserSubmit' class="btn btn-primary">Update</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit user details --->
<script src="<?=base_url()?>public/js/user.js"></script>