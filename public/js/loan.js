'use strict';

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm bank's log in status
	
	
    //load all banks once the page is ready
    //function header: larl_(url)
    larl_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of bank when fields are changed
    $("#reqLoanListSortBy, #reqLoanListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        larl_();
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

        larl_($(this).attr('href'));

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
                larl_();

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
    $("#editLoanSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editLoanForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['nameEditErr', 'descriptionEditErr'], "");

            var name = $("#nameEdit").val();
            var description = $("#descriptionEdit").val();
            var loanId = $("#loanId").val();

            //ensure all required fields are filled
            if(!name){
                !name ? changeInnerHTML('nameEditErr', "required") : "";

                return;
            }

            if(!loanId){
                $("#fMsgEdit").text("An unexpected error occured while trying to update bank's details");
                return;
            }

            //display message telling bank action is being processed
            $("#fMsgEditIcon").attr('class', spinnerClass);
            $("#fMsgEdit").text(" Updating details...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"loans/update",
                data: {loanId:loanId, name:name, description:description}
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
                    larl_();

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
            larl_();
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
    
    
    //When the trash icon in front of a bank account is clicked on the bank list table (i.e. to delete the account)
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
                        larl_();

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
        var user = $(this).siblings(".user").html();
        var amount = $(this).siblings(".amount").html();
        var duration = $(this).siblings(".duration").html();
        var status = $(this).siblings(".status").html();
        
        //prefill the form fields
        $("#nameEdit").val(name);
        $("#descriptionEdit").val(description);
        
        $("#editLoanModal").modal('show');
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