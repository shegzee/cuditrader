<?php
defined('BASEPATH') OR exit('');
?>

<div class="row hidden-print">
    <!-- requested loans here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new loan?, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-plus pointer" style="color:#337ab7" data-target='#addNewLoanModal' data-toggle='modal'>
                        New Loan
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="reqLoanListPerPage">Show</label>
                        <select id="reqLoanListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="reqLoanListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="reqLoanListSortBy" class="control-label">Sort by</label> 
                        <select id="reqLoanListSortBy" class="form-control">
                            <option value="loan_amount-ASC">Amount (A to Z)</option>
                            <option value="loan_amount-DESC">Amount (Z to A)</option>
                            <option value="collateral_amount-ASC">Collateral Amount (A to Z)</option>
                            <option value="collateral_amount-DESC">Collateral Amount (Z to A)</option>
                            <option value="requested_on-ASC" selected>Date Requested (A to Z)</option>
                            <option value="requested_on-DESC">Date Requested (Z to A)</option>
                            <option value="user_id-ASC">User (A to Z)</option>
                            <option value="user_id-DESC">User (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="description-ASC">Description - ascending<!-- </option>
                            <option value="description-DESC">Description - descending</option> -->
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="appLoanSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="appLoanSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- loan list -->
            <div class="row">
                <div class="col-sm-12" id="reqLoan"></div>
            </div>
            <!-- loan list ends -->
        </div>
    </div>


<!-- approved loans here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new loan?, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="appLoanListPerPage">Show</label>
                        <select id="appLoanListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="appLoanListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="appLoanListSortBy" class="control-label">Sort by</label> 
                        <select id="appLoanListSortBy" class="form-control">
                            <option value="loan_amount-ASC">Amount (A to Z)</option>
                            <option value="loan_amount-DESC">Amount (Z to A)</option>
                            <option value="collateral_amount-ASC">Collateral Amount (A to Z)</option>
                            <option value="collateral_amount-DESC">Collateral Amount (Z to A)</option>
                            <option value="requested_on-ASC">Date Requested (A to Z)</option>
                            <option value="requested_on-DESC">Date Requested (Z to A)</option>
                            <option value="approved_on-ASC" selected>Date Approved (A to Z)</option>
                            <option value="approved_on-DESC">Date Approved (Z to A)</option>
                            <option value="user_id-ASC">User (A to Z)</option>
                            <option value="user_id-DESC">User (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="description-ASC">Description - ascending<!-- </option>
                            <option value="description-DESC">Description - descending</option> -->
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="appLoanSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="appLoanSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- loan list -->
            <div class="row">
                <div class="col-sm-12" id="appLoan"></div>
            </div>
            <!-- loan list ends -->
        </div>
    </div>

<!-- granted loans here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new loan?, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="graLoanListPerPage">Show</label>
                        <select id="graLoanListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="graLoanListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="graLoanListSortBy" class="control-label">Sort by</label> 
                        <select id="graLoanListSortBy" class="form-control">
                            <option value="loan_amount-ASC">Amount (A to Z)</option>
                            <option value="loan_amount-DESC">Amount (Z to A)</option>
                            <option value="collateral_amount-ASC">Collateral Amount (A to Z)</option>
                            <option value="collateral_amount-DESC">Collateral Amount (Z to A)</option>
                            <option value="requested_on-ASC">Date Requested (A to Z)</option>
                            <option value="requested_on-DESC">Date Requested (Z to A)</option>
                            <option value="approved_on-ASC" selected>Date Approved (A to Z)</option>
                            <option value="approved_on-DESC">Date Approved (Z to A)</option>
                            <option value="granted_on-ASC" selected>Date Granted (A to Z)</option>
                            <option value="granted_on-DESC">Date Granted (Z to A)</option>
                            <option value="user_id-ASC">User (A to Z)</option>
                            <option value="user_id-DESC">User (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="description-ASC">Description - ascending<!-- </option>
                            <option value="description-DESC">Description - descending</option> -->
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="graLoanSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="graLoanSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- loan list -->
            <div class="row">
                <div class="col-sm-12" id="graLoan"></div>
            </div>
            <!-- loan list ends -->
        </div>
    </div>

<!-- denied loans here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new loan?, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="denLoanListPerPage">Show</label>
                        <select id="denLoanListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="denLoanListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="denLoanListSortBy" class="control-label">Sort by</label> 
                        <select id="denLoanListSortBy" class="form-control">
                            <option value="loan_amount-ASC">Amount (A to Z)</option>
                            <option value="loan_amount-DESC">Amount (Z to A)</option>
                            <option value="collateral_amount-ASC">Collateral Amount (A to Z)</option>
                            <option value="collateral_amount-DESC">Collateral Amount (Z to A)</option>
                            <option value="requested_on-ASC">Date Requested (A to Z)</option>
                            <option value="requested_on-DESC">Date Requested (Z to A)</option>
                            <option value="approved_on-ASC" selected>Date Denied (A to Z)</option>
                            <option value="approved_on-DESC">Date Denied (Z to A)</option>
                            <option value="user_id-ASC">User (A to Z)</option>
                            <option value="user_id-DESC">User (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="description-ASC">Description - ascending<!-- </option>
                            <option value="description-DESC">Description - descending</option> -->
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="denLoanSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="denLoanSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- loan list -->
            <div class="row">
                <div class="col-sm-12" id="denLoan"></div>
            </div>
            <!-- loan list ends -->
        </div>
    </div>

<!-- cleared loans here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new loan?, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="cleLoanListPerPage">Show</label>
                        <select id="cleLoanListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="cleLoanListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="cleLoanListSortBy" class="control-label">Sort by</label> 
                        <select id="cleLoanListSortBy" class="form-control">
                            <option value="loan_amount-ASC">Amount (A to Z)</option>
                            <option value="loan_amount-DESC">Amount (Z to A)</option>
                            <option value="collateral_amount-ASC">Collateral Amount (A to Z)</option>
                            <option value="collateral_amount-DESC">Collateral Amount (Z to A)</option>
                            <option value="requested_on-ASC">Date Requested (A to Z)</option>
                            <option value="requested_on-DESC">Date Requested (Z to A)</option>
                            <option value="approved_on-ASC">Date Approved (A to Z)</option>
                            <option value="approved_on-DESC">Date Approved (Z to A)</option>
                            <option value="cleared_on-ASC" selected>Date Cleared (A to Z)</option>
                            <option value="cleared_on-DESC">Date Cleared (Z to A)</option>
                            <option value="user_id-ASC">User (A to Z)</option>
                            <option value="user_id-DESC">User (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="description-ASC">Description - ascending<!-- </option>
                            <option value="description-DESC">Description - descending</option> -->
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="cleLoanSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="cleLoanSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- loan list -->
            <div class="row">
                <div class="col-sm-12" id="cleLoan"></div>
            </div>
            <!-- loan list ends -->
        </div>
    </div>

<!-- cancelled loans here -->
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new loan?, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="canLoanListPerPage">Show</label>
                        <select id="canLoanListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="canLoanListPerPage">per page</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="canLoanListSortBy" class="control-label">Sort by</label> 
                        <select id="canLoanListSortBy" class="form-control">
                            <option value="loan_amount-ASC">Amount (A to Z)</option>
                            <option value="loan_amount-DESC">Amount (Z to A)</option>
                            <option value="collateral_amount-ASC">Collateral Amount (A to Z)</option>
                            <option value="collateral_amount-DESC">Collateral Amount (Z to A)</option>
                            <option value="requested_on-ASC">Date Requested (A to Z)</option>
                            <option value="requested_on-DESC">Date Requested (Z to A)</option>
                            <option value="approved_on-ASC" selected>Date Cancelled (A to Z)</option>
                            <option value="approved_on-DESC">Date Cancelled (Z to A)</option>
                            <option value="user_id-ASC">User (A to Z)</option>
                            <option value="user_id-DESC">User (Z to A)</option>
                            <!-- <option value="created_on-ASC">Date Created (older first)</option>
                            <option value="created_on-DESC">Date Created (recent first)</option> -->
                            <option value="description-ASC">Description - ascending<!-- </option>
                            <option value="description-DESC">Description - descending</option> -->
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="canLoanSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="canLoanSearch" placeholder="Search...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- loan list -->
            <div class="row">
                <div class="col-sm-12" id="canLoan"></div>
            </div>
            <!-- loan list ends -->
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


<!-- Modal for editing loan -->
<div class='modal fade' id='editLoanModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit Loan</h4>
                <div class="text-center">
                    <i id="fMsgEditIcon"></i>
                    <span id="fMsgEdit"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editLoanForm' name='editLoanForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='userEdit' class="control-label">User</label><br />
                            <!-- <input type="text" id='userEdit' class="form-control checkField" placeholder="User"> -->
                            <?php // form_dropdown('userEdit', $allUsersList, set_select('userEdit'), 'class="form-control"'); ?>
                            <select id="userEdit" name="userEdit" class="form-control">
                                <?php foreach ($allUsersList as $id => $text) { ?>
                                    <option id="user-<?= $id ?>" value="<?= $id ?>"><?= $text ?></option>
                                <?php } ?>
                            </select>
                            <!-- <select id="userEdit", ></select> -->
                            <span class="help-block errMsg" id="userEditErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='statusNumberEdit' class="control-label">Status</label><br />
                            <!-- <input type="text" id='statusNumberEdit' class="form-control" placeholder="Status"> -->
                            <?php // form_dropdown('statusNumberEdit', $allStatusesList, set_select('statusNumberEdit'), 'class="form-control"'); ?>
                            <select id="statusNumberEdit" name="statusNumberEdit" class="form-control">
                                <?php foreach ($allStatusesList as $id => $this_status) { ?>
                                    <option id="status-<?= $id ?>" value="<?= $id ?>"><?= $this_status ?></option>
                                <?php } ?>
                            </select>
                            <span class="help-block errMsg" id="statusNumberEditErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='loanUnitEdit' class="control-label">Loan Unit</label><br />
                            <!-- <input type="text" id='loanUnitEdit' class="form-control" placeholder="Loan Unit"> -->
                            <?php // form_dropdown('loanUnitEdit', $allLUnitsList, set_select('loanUnitEdit'), 'class="form-control"'); ?>
                            <select id="loanUnitEdit" name="loanUnitEdit" class="form-control">
                                <?php foreach ($allLUnitsList as $id => $text) { ?>
                                    <option id="loan_unit-<?= $id ?>" value="<?= $id ?>"><?= $text ?></option>
                                <?php } ?>
                            </select>
                            <span class="help-block errMsg" id="loanUnitEditErr"></span>
                        </div>

                        <div class="form-group-sm col-sm-6">
                            <label for='loanAmountEdit' class="control-label">Amount</label>
                            <input type="tel" id='loanAmountEdit' class="form-control" placeholder="Amount">
                            <span class="help-block errMsg" id="loanAmountEditErr"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='collateralUnitEdit' class="control-label">Collateral Unit</label><br />
                            <!-- <input type="text" id='collateralUnitEdit' class="form-control" placeholder="Collateral Unit"> -->
                            <?php //form_dropdown('collateralUnitEdit', $allCUnitsList, set_select('collateralUnitEdit'), 'class="form-control"'); ?>
                            <select id="collateralUnitEdit" name="collateralUnitEdit" class="form-control">
                                <?php foreach ($allCUnitsList as $id => $text) { ?>
                                    <option id="collateral_unit-<?= $id ?>" value="<?= $id ?>"><?= $text ?></option>
                                <?php } ?>
                            </select>
                            <span class="help-block errMsg" id="collateralUnitEditErr"></span>
                        </div>
                        
                        <div class="form-group-sm col-sm-6">
                            <label for='collateralAmountEdit' class="control-label">Amount</label>
                            <input type="tel" id='collateralAmountEdit' class="form-control" placeholder="Collateral Amount">
                            <span class="help-block errMsg" id="collateralAmountEditErr"></span>
                        </div>
                    </div>
                        
                        
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='durationEdit' class="control-label">Duration (months)</label>
                            <input type="tel" id='durationEdit' class="form-control" placeholder="Duration">
                            <span class="help-block errMsg" id="durationEditErr"></span>
                        </div>


                    </div>
                    
                    <input type="hidden" id="loanId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editLoanForm" class="btn btn-warning pull-left">Reset Form</button>
                <button type='button' id='editLoanSubmit' class="btn btn-primary">Update</button>
                <button type='button' id='editLoanCancel' class="btn btn-danger" data-dismiss='modal'>Close</button>
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

<script src="<?=base_url()?>public/js/loan.js"></script>