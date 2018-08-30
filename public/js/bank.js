'use strict';

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm bank's log in status
	
	
    //load all banks once the page is ready
    //function header: laba_(url)
    laba_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of bank when fields are changed
    $("#bankListSortBy, #bankListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        laba_();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#allBank").on('click', '.lnp', function(e){
        e.preventDefault();
		
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        laba_($(this).attr('href'));

        return false;
    });
    
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new bank details .i.e. when "add bank" button is clicked
    $("#addBankSubmit").click(function(e){
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
            url: appRoot+"banks/add",
            data: {name:name, description:description}
        }).done(function(returnedData){
            $("#fMsgIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsg").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewBankForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsg").text("");
                    $("#addNewBankModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['nameErr', 'descriptionErr'],
                "");

                //refresh bank list table
                laba_();

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
    
    
    //handles the updating of bank details
    $("#editBankSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editBankForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['nameEditErr', 'descriptionEditErr'], "");

            var name = $("#nameEdit").val();
            var description = $("#descriptionEdit").val();
            var bankId = $("#bankId").val();

            //ensure all required fields are filled
            if(!name){
                !name ? changeInnerHTML('nameEditErr', "required") : "";

                return;
            }

            if(!bankId){
                $("#fMsgEdit").text("An unexpected error occured while trying to update bank's details");
                return;
            }

            //display message telling bank action is being processed
            $("#fMsgEditIcon").attr('class', spinnerClass);
            $("#fMsgEdit").text(" Updating details...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"banks/update",
                data: {bankId:bankId, name:name, description:description}
            }).done(function(returnedData){
                $("#fMsgEditIcon").removeClass();//remove spinner

                if(returnedData.status === 1){
                    $("#fMsgEdit").css('color', 'green').text(returnedData.msg);

                    //reset the form and close the modal
                    setTimeout(function(){
                        $("#fMsgEdit").text("");
                        $("#editBankModal").modal('hide');
                    }, 1000);

                    //reset all error msgs in case they are set
                    changeInnerHTML(['nameEditErr', 'descriptionEditErr'], "");

                    //refresh bank list table
                    laba_();

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
    
    
    //handles bank search
    $("#bankSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/banksearch",
                data: {v:value},
                success: function(returnedData){
                    $("#allBank").html(returnedData.bankTable);
                }
            });
        }
        
        else{
            laba_();
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
    $("#allBank").on('click', '.deleteBank', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var bankId = ElemId.split("-")[1];//get the bankId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(bankId){
                $.ajax({
                    url: appRoot+"banks/delete",
                    method: "POST",
                    data: {_uId:bankId}
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
    
    
    //to launch the modal to allow for the editing of bank info
    $("#allBank").on('click', '.editBank', function(){
        
        var bankId = $(this).attr('id').split("-")[1];
        
        $("#bankId").val(bankId);
        
        //get info of bank with bankId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var name = $(this).siblings(".name").html();
        var description = $(this).siblings(".description").html();
        
        //prefill the form fields
        $("#nameEdit").val(name);
        $("#descriptionEdit").val(description);
        
        $("#editBankModal").modal('show');
    });
    
    /***************** replicate for account types ************************************* */

    laat_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of bank when fields are changed
    $("#accountTypeListSortBy, #accountTypeListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        laat_();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#allAccountType").on('click', '.lnp', function(e){
        e.preventDefault();
        
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        laat_($(this).attr('href'));

        return false;
    });
    
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new bank details .i.e. when "add bank" button is clicked
    $("#addAccountTypeSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['nameATErr', 'descriptionATErr'],
        "");
        
        var name = $("#nameAT").val();
        var description = $("#descriptionAT").val();
        
        //ensure all required fields are filled
        if(!name){
            !name ? changeInnerHTML('nameATErr', "required") : "";
            return;
        }
        
        //display message telling bank action is being processed
        $("#fMsgATIcon").attr('class', spinnerClass);
        $("#fMsgAT").text(" Processing...");
        
        //make ajax request if all is well
        $.ajax({
            method: "POST",
            url: appRoot+"banks/addAT",
            data: {name:name, description:description}
        }).done(function(returnedData){
            $("#fMsgATIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsgAT").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewAccountTypeForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsgAT").text("");
                    $("#addNewAccountTypeModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['nameATErr', 'descriptionErr'],
                "");

                //refresh bank list table
                laat_();

            }

            else{
                //display error message returned
                $("#fMsgAT").css('color', 'red').html(returnedData.msg);

                //display individual error messages if applied
                $("#nameATErr").text(returnedData.name);
                $("#descriptionATErr").text(returnedData.description);
            }
        }).fail(function(){
            if(!navigator.onLine){
                $("#fMsgAT").css('color', 'red').text("Network error! Pls check your network connection");
            }
        });
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the updating of bank details
    $("#editAccountTypeSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editAccountTypeForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['nameATEditErr', 'descriptionATEditErr'], "");

            var name = $("#nameATEdit").val();
            var description = $("#descriptionATEdit").val();
            var accountTypeId = $("#accountTypeId").val();

            //ensure all required fields are filled
            if(!name){
                !name ? changeInnerHTML('nameATEditErr', "required") : "";

                return;
            }

            if(!accountTypeId){
                $("#fMsgEditAT").text("An unexpected error occured while trying to update account type");
                return;
            }

            //display message telling bank action is being processed
            $("#fMsgEditATIcon").attr('class', spinnerClass);
            $("#fMsgEditAT").text(" Updating details...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"banks/updateAT",
                data: {accountTypeId:accountTypeId, name:name, description:description}
            }).done(function(returnedData){
                $("#fMsgEditATIcon").removeClass();//remove spinner

                if(returnedData.status === 1){
                    $("#fMsgEditAT").css('color', 'green').text(returnedData.msg);

                    //reset the form and close the modal
                    setTimeout(function(){
                        $("#fMsgEditAT").text("");
                        $("#editBankATModal").modal('hide');
                    }, 1000);

                    //reset all error msgs in case they are set
                    changeInnerHTML(['nameATEditErr', 'descriptionATEditErr'], "");

                    //refresh bank list table
                    laat_();

                }

                else{
                    //display error message returned
                    $("#fMsgEditAT").css('color', 'red').html(returnedData.msg);

                    //display individual error messages if applied
                    $("#nameATEditErr").html(returnedData.name);
                    $("#descriptionATEditErr").html(returnedData.description);
                }
            }).fail(function(){
                    if(!navigator.onLine){
                        $("#fMsgEditAT").css('color', 'red').html("Network error! Pls check your network connection");
                    }
                });
        }
        
        else{
            $("#fMsgEditAT").html("No changes were made");
        }
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles bank search
    $("#accountTypeSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/accountTypesearch",
                data: {v:value},
                success: function(returnedData){
                    $("#allAccountType").html(returnedData.bankTable);
                }
            });
        }
        
        else{
            laat_();
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
    $("#allAccountType").on('click', '.deleteAccountType', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var bankId = ElemId.split("-")[1];//get the bankId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(bankId){
                $.ajax({
                    url: appRoot+"banks/delete",
                    method: "POST",
                    data: {_uId:accountTypeId}
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
    
    
    //to launch the modal to allow for the editing of bank info
    $("#allAccountType").on('click', '.editAccountType', function(){
        
        var accountTypeId = $(this).attr('id').split("-")[1];
        
        $("#accountTypeId").val(accountTypeId);
        
        //get info of bank with bankId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var name = $(this).siblings(".name").html();
        var description = $(this).siblings(".description").html();
        
        //prefill the form fields
        $("#nameATEdit").val(name);
        $("#descriptionATEdit").val(description);
        
        $("#editAccountTypeModal").modal('show');
    });
});



/*
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
*/

/**
 * laba_ = "Load all banks"
 * @returns {undefined}
 */
function laba_(url){
    var orderBy = $("#bankListSortBy").val().split("-")[0];
    var orderFormat = $("#bankListSortBy").val().split("-")[1];
    var limit = $("#bankListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"banks/laba_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
			
            $("#allBank").html(returnedData.bankTable);
        });
}

/* *************************************************************************************** */

/**
 * laat_ = "Load all banks"
 * @returns {undefined}
 */
function laat_(url){
    var orderBy = $("#accountTypeListSortBy").val().split("-")[0];
    var orderFormat = $("#accountTypeListSortBy").val().split("-")[1];
    var limit = $("#accountTypeListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"banks/laat_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
            
            $("#allAccountType").html(returnedData.bankATTable);
        });
}