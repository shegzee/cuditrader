'use strict';

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm bank's log in status
	
	
    //load all banks once the page is ready
    //function header: lase_(url)
    lase_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of bank when fields are changed
    $("#settingListSortBy, #settingListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        lase_();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#allSettings").on('click', '.lnp', function(e){
        e.preventDefault();
		
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        lase_($(this).attr('href'));

        return false;
    });
    
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new loan unit .i.e. when "add loan unit" button is clicked
    $("#addSettingSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['settingErr', 'valueErr'],
        "");
        
        var setting = $("#setting").val();
        var value = $("#value").val();
        
        //ensure all required fields are filled
        if(!setting){
            !setting ? changeInnerHTML('settingErr', "required") : "";
            return;
        }
        if(!value){
            !value ? changeInnerHTML('valueErr', "required") : "";
            return;
        }
        
        //display message telling action is being processed
        $("#fMsgIcon").attr('class', spinnerClass);
        $("#fMsg").text(" Processing...");
        
        //make ajax request if all is well
        $.ajax({
            method: "POST",
            url: appRoot+"settings/addSetting",
            data: {setting:setting, value:value}
        }).done(function(returnedData){
            $("#fMsgIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsg").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewSettingForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsg").text("");
                    $("#addNewSettingModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['settingErr', 'valueErr'],
                "");

                //refresh bank list table
                lase_();

            }

            else{
                //display error message returned
                $("#fMsg").css('color', 'red').html(returnedData.msg);

                //display individual error messages if applied
                $("#settingErr").text(returnedData.name);
                $("#valueErr").text(returnedData.logo);
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
    $("#editSettingSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editSettingForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['settingEditErr', 'valueEditErr'], "");

            var setting = $("#settingEdit").val();
            var value = $("#valueEdit").val();
            var tenorId = $("#tenorId").val();

            //ensure all required fields are filled
            if(!setting){
                !setting ? changeInnerHTML('settingEditErr', "required") : "";

                return;
            }
            if(!value){
                !value ? changeInnerHTML('valueEditErr', "required") : "";

                return;
            }

            if(!tenorId){
                $("#fMsgEdit").text("An unexpected error occured while trying to update collateral details");
                return;
            }

            //display message telling bank action is being processed
            $("#fMsgEditIcon").attr('class', spinnerClass);
            $("#fMsgEdit").text(" Updating unit...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"settings/updateSetting",
                data: {tenorId:tenorId, setting:setting, value:value}
            }).done(function(returnedData){
                $("#fMsgEditIcon").removeClass();//remove spinner

                if(returnedData.status === 1){
                    $("#fMsgEdit").css('color', 'green').text(returnedData.msg);

                    //reset the form and close the modal
                    setTimeout(function(){
                        $("#fMsgEdit").text("");
                        $("#editLUnitModal").modal('hide');
                    }, 1000);

                    //reset all error msgs in case they are set
                    changeInnerHTML(['settingEditErr', 'valueEditErr'], "");

                    //refresh bank list table
                    lase_();

                }

                else{
                    //display error message returned
                    $("#fMsgEdit").css('color', 'red').html(returnedData.msg);

                    //display individual error messages if applied
                    $("#settingEditErr").html(returnedData.setting);
                    $("#valueEditErr").html(returnedData.value);
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
    $("#settingsSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/settingssearch",
                data: {v:value},
                success: function(returnedData){
                    $("#allSettings").html(returnedData.settingsTable);
                }
            });
        }
        
        else{
            lase_();
        }
    });
    
    
    /*
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    */
    
    
    //When the trash icon in front of a loan unit is clicked on the loan units list table (i.e. to delete the unit)
    $("#allSettings").on('click', '.deleteSetting', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var settingId = ElemId.split("-")[1];//get the bankId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(settingId){
                $.ajax({
                    url: appRoot+"settings/deleteSetting",
                    method: "POST",
                    data: {_sId:settingId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        lase_();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });
    
    
    //to launch the modal to allow for the editing of bank info
    $("#allSettings").on('click', '.editSetting', function(){
        
        var settingId = $(this).attr('id').split("-")[1];
        
        $("#settingId").val(settingId);
        
        //get info of bank with bankId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var setting = $(this).siblings(".setting").html();
        var value = $(this).siblings(".value").html();
        
        //prefill the form fields
        $("#settingEdit").val(setting);
        $("#valueEdit").val(value);
        
        $("#editSettingModal").modal('show');
    });
    
    /***************** replicate from tenors ************************************* */

    late_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of bank when fields are changed
    $("#tenorListSortBy, #tenorListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        late_();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#allTenors").on('click', '.lnp', function(e){
        e.preventDefault();
        
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        late_($(this).attr('href'));

        return false;
    });
    
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new collateral unit .i.e. when "add collateral unit" button is clicked
    $("#addTenorSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['tenorErr', 'displayErr'],
        "");
        
        var tenor = $("#tenor").val();
        var display = $("#display").val();
        
        //ensure all required fields are filled
        if(!tenor){
            !tenor ? changeInnerHTML('tenorErr', "required") : "";
            return;
        }
        if(!display){
            !display ? changeInnerHTML('displayErr', "required") : "";
            return;
        }
        
        //display message telling bank action is being processed
        $("#fMsgTenorIcon").attr('class', spinnerClass);
        $("#fMsgTenor").text(" Processing...");
        
        //make ajax request if all is well
        $.ajax({
            method: "POST",
            url: appRoot+"settings/addTenor",
            data: {tenor:tenor, display:display}
        }).done(function(returnedData){
            $("#fMsgTenorIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsgTenor").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewTenorForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsgTenor").text("");
                    $("#addNewTenorModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['tenorErr', 'displayErr'],
                "");

                //refresh bank list table
                late_();

            }

            else{
                //display error message returned
                $("#fMsgTenor").css('color', 'red').html(returnedData.msg);

                //display individual error messages if applied
                $("#tenorErr").text(returnedData.name);
                $("#displayErr").text(returnedData.logo);
            }
        }).fail(function(){
            if(!navigator.onLine){
                $("#fMsgTenor").css('color', 'red').text("Network error! Pls check your network connection");
            }
        });
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the updating of bank details
    $("#editTenorSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editTenorForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['tenorEditErr', 'displayEditErr'], "");

            var tenor = $("#tenorEdit").val();
            var display = $("#displayEdit").val();
            var tenorId = $("#tenorId").val();

            //ensure all required fields are filled
            if(!tenor){
                !tenor ? changeInnerHTML('tenorEditErr', "required") : "";

                return;
            }

            if(!display){
                !display ? changeInnerHTML('displayEditErr', "required") : "";

                return;
            }

            if(!tenorId){
                $("#fMsgEditTenor").text("An unexpected error occured while trying to update account type");
                return;
            }

            //display message telling bank action is being processed
            $("#fMsgEditTenorIcon").attr('class', spinnerClass);
            $("#fMsgEditTenor").text(" Updating details...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"settings/updateTenor",
                data: {tenorId:tenorId, tenor:tenor, display:display}
            }).done(function(returnedData){
                $("#fMsgEditTenorIcon").removeClass();//remove spinner

                if(returnedData.status === 1){
                    $("#fMsgEditTenor").css('color', 'green').text(returnedData.msg);

                    //reset the form and close the modal
                    setTimeout(function(){
                        $("#fMsgEditTenor").text("");
                        $("#editTenorModal").modal('hide');
                    }, 1000);

                    //reset all error msgs in case they are set
                    changeInnerHTML(['tenorEditErr', 'displayEditErr'], "");

                    //refresh bank list table
                    late_();

                }

                else{
                    //display error message returned
                    $("#fMsgEditTenor").css('color', 'red').html(returnedData.msg);

                    //display individual error messages if applied
                    $("#tenorEditErr").html(returnedData.name);
                    $("#displayEditErr").html(returnedData.logo);
                }
            }).fail(function(){
                    if(!navigator.onLine){
                        $("#fMsgEditTenor").css('color', 'red').html("Network error! Pls check your network connection");
                    }
                });
        }
        
        else{
            $("#fMsgEditTenor").html("No changes were made");
        }
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles tenor search
    $("#tenorSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/tenorsearch",
                data: {v:value},
                success: function(returnedData){
                    $("#allTenors").html(returnedData.cUnitTable);
                }
            });
        }
        
        else{
            late_();
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
    $("#allTenors").on('click', '.deleteTenor', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var tenorId = ElemId.split("-")[1];//get the bankId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(tenorId){
                $.ajax({
                    url: appRoot+"settings/deleteTenor",
                    method: "POST",
                    data: {_tId:tenorId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        late_();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });
    
    
    //to launch the modal to allow for the editing of bank info
    $("#allTenors").on('click', '.editTenor', function(){
        
        var tenorId = $(this).attr('id').split("-")[1];
        
        $("#tenorId").val(tenorId);
        
        //get info of bank with bankId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var tenor = $(this).siblings(".tenor").html();
        var display = $(this).siblings(".display").html();
        
        //prefill the form fields
        $("#tenorEdit").val(tenor);
        $("#displayEdit").val(display);
        
        $("#editTenorModal").modal('show');
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
 * lase_ = "Load all loan units"
 * @returns {undefined}
 */
function lase_(url){
    var orderBy = $("#settingsListSortBy").val().split("-")[0];
    var orderFormat = $("#settingsListSortBy").val().split("-")[1];
    var limit = $("#settingsListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"settings/lase_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
			
            $("#allSettings").html(returnedData.settingsTable);
        });
}

/* *************************************************************************************** */

/**
 * late_ = "Load all collateral units"
 * @returns {undefined}
 */
function late_(url){
    var orderBy = $("#tenorListSortBy").val().split("-")[0];
    var orderFormat = $("#tenorListSortBy").val().split("-")[1];
    var limit = $("#tenorListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"settings/late_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
            
            $("#allTenors").html(returnedData.tenorTable);
        });
}