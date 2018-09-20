'use strict';

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm bank's log in status
	
	
    //load all banks once the page is ready
    //function header: larl_(url)
    // larl_();
    // laal_();
    // ladl_();
    load_all();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of loans when fields are changed
    $("#reqLoanListSortBy, #reqLoanListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        load_all();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#reqLoan").on('click', '.lnp', function(e){
        e.preventDefault();
		
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        load_all($(this).attr('href'));

        return false;
    });
    
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new bank details .i.e. when "add bank" button is clicked
    $("#addLoanSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['nameErr', 'descriptionErr'],
        "");
        
        var name = $("#name").val();
        var description = $("#description").val();
        
        //ensure all required fields are filled
        if(!name){
            !name ? changeInnerHTML('nameErr', "required") : "";
            return;
        }
        
        //display message telling bank action is being processed
        $("#fMsgIcon").attr('class', spinnerClass);
        $("#fMsg").text(" Processing...");
        
        //make ajax request if all is well
        $.ajax({
            method: "POST",
            url: appRoot+"loans/add",
            data: {name:name, description:description}
        }).done(function(returnedData){
            $("#fMsgIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsg").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewLoanForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsg").text("");
                    $("#addNewLoanModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['nameErr', 'descriptionErr'],
                "");

                //refresh bank list table
                load_all();

            }

            else{
                //display error message returned
                $("#fMsg").css('color', 'red').html(returnedData.msg);

                //display individual error messages if applied
                $("#nameErr").text(returnedData.name);
                $("#descriptionErr").text(returnedData.description);
            }
        }).fail(function(){
            if(!navigator.onLine){
                $("#fMsg").css('color', 'red').text("Network error! Pls check your network connection");
            }
        });
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $("#editLoanCancel").click(function(e){
        e.preventDefault();

        changeInnerHTML(['userEditErr', 'statusNumberEditErr', 'loanUnitEditErr',
                'loanAmountEditErr', 'collateralUnitEditErr', 'collateralAmountEditErr','durationEditErr'], "");
    });
    
    //handles the updating of loan details
    $("#editLoanSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editLoanForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['userEditErr', 'statusNumberEditErr', 'loanUnitEditErr',
                'loanAmountEditErr', 'collateralUnitEditErr', 'collateralAmountEditErr','durationEditErr'], "");

            var userId = $("#userEdit").val();
            var statusNumber = $("#statusNumberEdit").val();
            var loanUnitId = $("#loanUnitEdit").val();
            var loanAmount = $("#loanAmountEdit").val();
            var collateralUnitId = $("#collateralUnitEdit").val();
            var collateralAmount = $("#collateralAmountEdit").val();
            var duration = $("#durationEdit").val();
            var loanId = $("#loanId").val();

            //ensure all required fields are filled
            if(!userId || !statusNumber || !loanUnitId || !loanAmount || ! collateralUnitId || !collateralAmount
                || !duration){
                !userId ? changeInnerHTML('userEditErr', "required") : "";
                !statusNumber ? changeInnerHTML('statusNumberEditErr', "required") : "";
                !loanUnitId ? changeInnerHTML('loanUnitEditErr', "required") : "";
                !loanAmount ? changeInnerHTML('loanAmountEditErr', "required") : "";
                !collateralUnitId ? changeInnerHTML('collateralUnitEditErr', "required") : "";
                !collateralAmount ? changeInnerHTML('collateralAmountEditErr', "required") : "";
                !duration ? changeInnerHTML('durationEditErr', "required") : "";
                return;
            }
            // if(!statusNumber){
            //     return;
            // }
            // if(!loanUnitId){
            //     return;
            // }
            // if(!loanAmount){
            //     return;
            // }
            // if(!collateralUnitId){
            //     return;
            // }
            // if(!collateralAmount){
            //     return;
            // }
            // if(!duration){
            //     return;
            // }

            if(!loanId){
                $("#fMsgEdit").text("An unexpected error occured while trying to update bank's details");
                return;
            }

            //display message telling loan is being processed
            $("#fMsgEditIcon").attr('class', spinnerClass);
            $("#fMsgEdit").text(" Updating details...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"loans/update",
                data: {loanId:loanId, userId:userId, statusNumber:statusNumber, 
                    loanUnitId:loanUnitId, loanAmount:loanAmount, 
                    collateralUnitId:collateralUnitId, collateralAmount:collateralAmount,
                    duration:duration}
            }).done(function(returnedData){
                $("#fMsgEditIcon").removeClass();//remove spinner

                if(returnedData.status === 1){
                    $("#fMsgEdit").css('color', 'green').text(returnedData.msg);

                    //reset the form and close the modal
                    setTimeout(function(){
                        $("#fMsgEdit").text("");
                        $("#editLoanModal").modal('hide');
                    }, 1000);

                    //reset all error msgs in case they are set
                    changeInnerHTML(['nameEditErr', 'descriptionEditErr'], "");

                    //refresh bank list table
                    load_all();

                }

                else{
                    //display error message returned
                    $("#fMsgEdit").css('color', 'red').html(returnedData.msg);

                    //display individual error messages if applied
                    $("#nameEditErr").html(returnedData.name);
                    $("#descriptionEditErr").html(returnedData.description);
                }
            }).fail(function(){
                    if(!navigator.onLine){
                        $("#fMsgEdit").css('color', 'red').html("Network error! Pls check your network connection");
                    }
                });
        }
        
        else{
            $("#fMsgEdit").html("No changes were made");
        }
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles loan search
    $("#reqLoanSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/reqLoanSearch",
                data: {v:value},
                success: function(returnedData){
                    $("#reqLoan").html(returnedData.loanTable);
                }
            });
        }
        
        else{
            load_all();
        }
    });
    
    
    /*
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    */
    
    
    //When the trash icon in front of a bank account is clicked on the bank list table (i.e. to delete the account)
    $("#reqLoan").on('click', '.deleteLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/delete",
                    method: "POST",
                    data: {_uId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData._nv === 1 ? "<a class='pointer'>Undo Delete</a>" : "<i class='fa fa-trash pointer'></i>";

                        //change the icon
                        $("#del-"+returnedData._uId).html(newHTML);

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

    /*
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    */
    
    
    //When the approve icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#reqLoan").on('click', '.approveLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/approve",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Approve</a>" : "<i class='fa fa-check pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

    //When the deny icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#reqLoan").on('click', '.denyLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/deny",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Deny</a>" : "<i class='fa fa-remove pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });
    
    
    //to launch the modal to allow for the editing of bank info
    $("#reqLoan").on('click', '.editLoan', function(){
        
        var loanId = $(this).attr('id').split("-")[1];
        
        $("#loanId").val(loanId);
        
        //get info of bank with loanId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var user_id = $(this).siblings(".user_id").html();
        var loan_unit_id = $(this).siblings(".loan_unit_id").html();
        var loan_amount = parseFloat($(this).siblings(".loan_amount").html());
        var collateral_unit_id = $(this).siblings(".collateral_unit_id").html();
        var collateral_amount = parseFloat($(this).siblings(".collateral_amount").html());
        var duration = parseInt($(this).siblings(".duration").html());
        var status_id = $(this).siblings(".status_id").html();
        
        //prefill the form fields
        $("#nameEdit").val(name);
        $("#loanUnitEdit").val(loan_unit_id);
        $("#loanAmountEdit").val(loan_amount);
        $("#collateralUnitEdit").val(collateral_unit_id);
        $("#collateralAmountEdit").val(collateral_amount);
        $("#durationEdit").val(duration);

        // prefill dropdowns
        $("#status-"+status_id).prop('selected', 'selected');
        $("#user-"+user_id).prop('selected', 'selected');
        $("#loan_unit-"+loan_unit_id).prop('selected', 'selected');
        $("#collateral_unit-"+collateral_unit_id).prop('selected', 'selected');
        
        $("#editLoanModal").modal('show');
    });
    
});

// ******************************************
// APPROVED LOANS

    //reload the list of loans when fields are changed
    $("#appLoanListSortBy, #appLoanListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        load_all();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#appLoan").on('click', '.lnp', function(e){
        e.preventDefault();
        
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        load_all($(this).attr('href'));

        return false;
    });

    //handles loan search
    $("#appLoanSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/appLoanSearch",
                data: {v:value},
                success: function(returnedData){
                    $("#appLoan").html(returnedData.loanTable);
                }
            });
        }
        
        else{
            load_all();
        }
    });

    //to launch the modal to allow for the editing of bank info
    $("#appLoan").on('click', '.editLoan', function(){
        
        var loanId = $(this).attr('id').split("-")[1];
        
        $("#loanId").val(loanId);
        
        //get info of bank with loanId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var user_id = $(this).siblings(".user_id").html();
        var loan_unit_id = $(this).siblings(".loan_unit_id").html();
        var loan_amount = parseFloat($(this).siblings(".loan_amount").html());
        var collateral_unit_id = $(this).siblings(".collateral_unit_id").html();
        var collateral_amount = parseFloat($(this).siblings(".collateral_amount").html());
        var duration = parseInt($(this).siblings(".duration").html());
        var status_id = $(this).siblings(".status_id").html();
        
        //prefill the form fields
        $("#nameEdit").val(name);
        $("#loanUnitEdit").val(loan_unit_id);
        $("#loanAmountEdit").val(loan_amount);
        $("#collateralUnitEdit").val(collateral_unit_id);
        $("#collateralAmountEdit").val(collateral_amount);
        $("#durationEdit").val(duration);

        // prefill dropdowns
        $("#status-"+status_id).prop('selected', 'selected');
        $("#user-"+user_id).prop('selected', 'selected');
        $("#loan_unit-"+loan_unit_id).prop('selected', 'selected');
        $("#collateral_unit-"+collateral_unit_id).prop('selected', 'selected');
        
        $("#editLoanModal").modal('show');
    });
    

//When the deny icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#appLoan").on('click', '.denyLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/deny",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Deny</a>" : "<i class='fa fa-remove pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

    //When the revert icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#appLoan").on('click', '.revertLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/revert",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Revert</a>" : "<i class='fa fa-check pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

//When the clear icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#appLoan").on('click', '.clearLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/clear",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Revert</a>" : "<i class='fa fa-check pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });


// ******************************************
// DENIED LOANS

    //reload the list of loans when fields are changed
    $("#denLoanListSortBy, #denLoanListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        load_all();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#denLoan").on('click', '.lnp', function(e){
        e.preventDefault();
        
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        load_all($(this).attr('href'));

        return false;
    });

    //handles loan search
    $("#denLoanSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/denLoanSearch",
                data: {v:value},
                success: function(returnedData){
                    $("#denLoan").html(returnedData.loanTable);
                }
            });
        }
        
        else{
            load_all();
        }
    });

    //to launch the modal to allow for the editing of bank info
    $("#denLoan").on('click', '.editLoan', function(){
        
        var loanId = $(this).attr('id').split("-")[1];
        
        $("#loanId").val(loanId);
        
        //get info of bank with loanId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var user_id = $(this).siblings(".user_id").html();
        var loan_unit_id = $(this).siblings(".loan_unit_id").html();
        var loan_amount = parseFloat($(this).siblings(".loan_amount").html());
        var collateral_unit_id = $(this).siblings(".collateral_unit_id").html();
        var collateral_amount = parseFloat($(this).siblings(".collateral_amount").html());
        var duration = parseInt($(this).siblings(".duration").html());
        var status_id = $(this).siblings(".status_id").html();
        
        //prefill the form fields
        $("#nameEdit").val(name);
        $("#loanUnitEdit").val(loan_unit_id);
        $("#loanAmountEdit").val(loan_amount);
        $("#collateralUnitEdit").val(collateral_unit_id);
        $("#collateralAmountEdit").val(collateral_amount);
        $("#durationEdit").val(duration);

        // prefill dropdowns
        $("#status-"+status_id).prop('selected', 'selected');
        $("#user-"+user_id).prop('selected', 'selected');
        $("#loan_unit-"+loan_unit_id).prop('selected', 'selected');
        $("#collateral_unit-"+collateral_unit_id).prop('selected', 'selected');
        
        $("#editLoanModal").modal('show');
    });

//When the approve icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#denLoan").on('click', '.approveLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/approve",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Approve</a>" : "<i class='fa fa-check pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

    //When the approve icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#denLoan").on('click', '.revertLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/revert",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Revert</a>" : "<i class='fa fa-check pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

// ******************************************
// CLEARED LOANS

    //reload the list of loans when fields are changed
    $("#cleLoanListSortBy, #cleLoanListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        load_all();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#cleLoan").on('click', '.lnp', function(e){
        e.preventDefault();
        
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        load_all($(this).attr('href'));

        return false;
    });

    //handles loan search
    $("#cleLoanSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/cleLoanSearch",
                data: {v:value},
                success: function(returnedData){
                    $("#cleLoan").html(returnedData.loanTable);
                }
            });
        }
        
        else{
            load_all();
        }
    });

    //to launch the modal to allow for the editing of bank info
    $("#cleLoan").on('click', '.editLoan', function(){
        
        var loanId = $(this).attr('id').split("-")[1];
        
        $("#loanId").val(loanId);
        
        //get info of bank with loanId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var user_id = $(this).siblings(".user_id").html();
        var loan_unit_id = $(this).siblings(".loan_unit_id").html();
        var loan_amount = parseFloat($(this).siblings(".loan_amount").html());
        var collateral_unit_id = $(this).siblings(".collateral_unit_id").html();
        var collateral_amount = parseFloat($(this).siblings(".collateral_amount").html());
        var duration = parseInt($(this).siblings(".duration").html());
        var status_id = $(this).siblings(".status_id").html();
        
        //prefill the form fields
        $("#nameEdit").val(name);
        $("#loanUnitEdit").val(loan_unit_id);
        $("#loanAmountEdit").val(loan_amount);
        $("#collateralUnitEdit").val(collateral_unit_id);
        $("#collateralAmountEdit").val(collateral_amount);
        $("#durationEdit").val(duration);

        // prefill dropdowns
        $("#status-"+status_id).prop('selected', 'selected');
        $("#user-"+user_id).prop('selected', 'selected');
        $("#loan_unit-"+loan_unit_id).prop('selected', 'selected');
        $("#collateral_unit-"+collateral_unit_id).prop('selected', 'selected');
        
        $("#editLoanModal").modal('show');
    });

//When the approve icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#cleLoan").on('click', '.approveLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/approve",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Approve</a>" : "<i class='fa fa-check pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

    //When the approve icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#cleLoan").on('click', '.revertLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/revert",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Revert</a>" : "<i class='fa fa-check pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

    // CANCELLED LOAN
    //When the approve icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#canLoan").on('click', '.approveLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/approve",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Approve</a>" : "<i class='fa fa-check pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });

    //When the deny icon in front of a loan is clicked on the bank list table (i.e. to delete the account)
    $("#canLoan").on('click', '.denyLoan', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var loanId = ElemId.split("-")[1];//get the loanId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(loanId){
                $.ajax({
                    url: appRoot+"loans/deny",
                    method: "POST",
                    data: {_lId:loanId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData.status === 1 ? "<a class='pointer'>Undo Deny</a>" : "<i class='fa fa-remove pointer'></i>";

                        //change the icon
                        $("#"+ElemId).html(newHTML);
                        load_all();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });
    
    
    //to launch the modal to allow for the editing of bank info
    $("#canLoan").on('click', '.editLoan', function(){
        
        var loanId = $(this).attr('id').split("-")[1];
        
        $("#loanId").val(loanId);
        
        //get info of bank with loanId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var user_id = $(this).siblings(".user_id").html();
        var loan_unit_id = $(this).siblings(".loan_unit_id").html();
        var loan_amount = parseFloat($(this).siblings(".loan_amount").html());
        var collateral_unit_id = $(this).siblings(".collateral_unit_id").html();
        var collateral_amount = parseFloat($(this).siblings(".collateral_amount").html());
        var duration = parseInt($(this).siblings(".duration").html());
        var status_id = $(this).siblings(".status_id").html();
        
        //prefill the form fields
        $("#nameEdit").val(name);
        $("#loanUnitEdit").val(loan_unit_id);
        $("#loanAmountEdit").val(loan_amount);
        $("#collateralUnitEdit").val(collateral_unit_id);
        $("#collateralAmountEdit").val(collateral_amount);
        $("#durationEdit").val(duration);

        // prefill dropdowns
        $("#status-"+status_id).prop('selected', 'selected');
        $("#user-"+user_id).prop('selected', 'selected');
        $("#loan_unit-"+loan_unit_id).prop('selected', 'selected');
        $("#collateral_unit-"+collateral_unit_id).prop('selected', 'selected');
        
        $("#editLoanModal").modal('show');
    });

/*
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
*/

/**
 * larl_ = "Load all requested loans"
 * @returns {undefined}
 */
function larl_(url){
    var orderBy = $("#reqLoanListSortBy").val().split("-")[0];
    var orderFormat = $("#reqLoanListSortBy").val().split("-")[1];
    var limit = $("#reqLoanListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"loans/larl_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
            
            $("#reqLoan").html(returnedData.loansList);
        });
}

/**
 * laal_ = "Load all approved loans"
 * @returns {undefined}
 */
function laal_(url){
    var orderBy = $("#appLoanListSortBy").val().split("-")[0];
    var orderFormat = $("#appLoanListSortBy").val().split("-")[1];
    var limit = $("#appLoanListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"loans/laal_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
            
            $("#appLoan").html(returnedData.loansList);
        });
}

/**
 * ladl_ = "Load all denied loans"
 * @returns {undefined}
 */
function ladl_(url){
    var orderBy = $("#denLoanListSortBy").val().split("-")[0];
    var orderFormat = $("#denLoanListSortBy").val().split("-")[1];
    var limit = $("#denLoanListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"loans/ladl_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
            
            $("#denLoan").html(returnedData.loansList);
        });
}

/**
 * lacl_ = "Load all cleared loans"
 * @returns {undefined}
 */
function lacl_(url){
    var orderBy = $("#cleLoanListSortBy").val().split("-")[0];
    var orderFormat = $("#cleLoanListSortBy").val().split("-")[1];
    var limit = $("#cleLoanListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"loans/lacl_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
            
            $("#cleLoan").html(returnedData.loansList);
        });
}

/**
 * laca_ = "Load all cancelled loans"
 * @returns {undefined}
 */
function laca_(url){
    var orderBy = $("#canLoanListSortBy").val().split("-")[0];
    var orderFormat = $("#canLoanListSortBy").val().split("-")[1];
    var limit = $("#canLoanListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"loans/laca_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
			
            $("#canLoan").html(returnedData.loansList);
        });
}

function load_all(url) {
    larl_();
    laal_();
    ladl_();
    lacl_();
    laca_();
}