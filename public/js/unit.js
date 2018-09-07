'use strict';

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm bank's log in status
	
	
    //load all banks once the page is ready
    //function header: lalu_(url)
    lalu_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of bank when fields are changed
    $("#lUnitListSortBy, #lUnitListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        lalu_();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#allLUnit").on('click', '.lnp', function(e){
        e.preventDefault();
		
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        lalu_($(this).attr('href'));

        return false;
    });
    
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new loan unit .i.e. when "add loan unit" button is clicked
    $("#addLUnitSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['nameErr', 'logoErr'],
        "");
        
        var name = $("#name").val();
        var logo = $("#logo").val();
        
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
            url: appRoot+"units/addLUnit",
            data: {name:name, logo:logo}
        }).done(function(returnedData){
            $("#fMsgIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsg").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewLUnitForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsg").text("");
                    $("#addNewLUnitModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['nameErr', 'logoErr'],
                "");

                //refresh bank list table
                lalu_();

            }

            else{
                //display error message returned
                $("#fMsg").css('color', 'red').html(returnedData.msg);

                //display individual error messages if applied
                $("#nameErr").text(returnedData.name);
                $("#logoErr").text(returnedData.logo);
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
    $("#editLUnitSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editLUnitForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['nameEditErr', 'logoEditErr'], "");

            var name = $("#nameEdit").val();
            var logo = $("#logoEdit").val();
            var lUnitId = $("#lUnitId").val();

            //ensure all required fields are filled
            if(!name){
                !name ? changeInnerHTML('nameEditErr', "required") : "";

                return;
            }

            if(!lUnitId){
                $("#fMsgEdit").text("An unexpected error occured while trying to update collateral details");
                return;
            }

            //display message telling bank action is being processed
            $("#fMsgEditIcon").attr('class', spinnerClass);
            $("#fMsgEdit").text(" Updating unit...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"units/updateLUnit",
                data: {lUnitId:lUnitId, name:name, logo:logo}
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
                    changeInnerHTML(['nameEditErr', 'logoEditErr'], "");

                    //refresh bank list table
                    lalu_();

                }

                else{
                    //display error message returned
                    $("#fMsgEdit").css('color', 'red').html(returnedData.msg);

                    //display individual error messages if applied
                    $("#nameEditErr").html(returnedData.name);
                    $("#logoEditErr").html(returnedData.logo);
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
    $("#lUnitSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/lunitsearch",
                data: {v:value},
                success: function(returnedData){
                    $("#allLUnit").html(returnedData.lUnitTable);
                }
            });
        }
        
        else{
            lalu_();
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
    $("#allLUnit").on('click', '.deleteLUnit', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var lUnitId = ElemId.split("-")[1];//get the bankId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(lUnitId){
                $.ajax({
                    url: appRoot+"units/deleteLUnit",
                    method: "POST",
                    data: {_luId:lUnitId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        lalu_();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });
    
    
    //to launch the modal to allow for the editing of bank info
    $("#allLUnit").on('click', '.editLUnit', function(){
        
        var lUnitId = $(this).attr('id').split("-")[1];
        
        $("#lUnitId").val(lUnitId);
        
        //get info of bank with bankId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var name = $(this).siblings(".name").html();
        var logo = $(this).siblings(".logo").html();
        
        //prefill the form fields
        $("#nameEdit").val(name);
        $("#logoEdit").val(logo);
        
        $("#editLUnitModal").modal('show');
    });
    
    /***************** replicate for collateral units ************************************* */

    lacu_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of bank when fields are changed
    $("#cUnitListSortBy, #cUnitListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        lacu_();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#allCUnit").on('click', '.lnp', function(e){
        e.preventDefault();
        
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        lacu_($(this).attr('href'));

        return false;
    });
    
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new collateral unit .i.e. when "add collateral unit" button is clicked
    $("#addCUnitSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['nameCUErr', 'logoCUErr'],
        "");
        
        var name = $("#nameCU").val();
        var logo = $("#logoCU").val();
        
        //ensure all required fields are filled
        if(!name){
            !name ? changeInnerHTML('nameCUErr', "required") : "";
            return;
        }
        
        //display message telling bank action is being processed
        $("#fMsgCUIcon").attr('class', spinnerClass);
        $("#fMsgCU").text(" Processing...");
        
        //make ajax request if all is well
        $.ajax({
            method: "POST",
            url: appRoot+"units/addCUnit",
            data: {name:name, logo:logo}
        }).done(function(returnedData){
            $("#fMsgCUIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsgCU").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewCUnitForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsgCU").text("");
                    $("#addNewCUnitModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['nameCUErr', 'logoCUErr'],
                "");

                //refresh bank list table
                lacu_();

            }

            else{
                //display error message returned
                $("#fMsgCU").css('color', 'red').html(returnedData.msg);

                //display individual error messages if applied
                $("#nameCUErr").text(returnedData.name);
                $("#logoCUErr").text(returnedData.logo);
            }
        }).fail(function(){
            if(!navigator.onLine){
                $("#fMsgCU").css('color', 'red').text("Network error! Pls check your network connection");
            }
        });
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the updating of bank details
    $("#editCUnitSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editCUnitForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['nameCUEditErr', 'logoCUEditErr'], "");

            var name = $("#nameCUEdit").val();
            var logo = $("#logoCUEdit").val();
            var cUnitId = $("#cUnitId").val();

            //ensure all required fields are filled
            if(!name){
                !name ? changeInnerHTML('nameCUEditErr', "required") : "";

                return;
            }

            if(!cUnitId){
                $("#fMsgEditCU").text("An unexpected error occured while trying to update account type");
                return;
            }

            //display message telling bank action is being processed
            $("#fMsgEditCUIcon").attr('class', spinnerClass);
            $("#fMsgEditCU").text(" Updating details...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"units/updateCUnit",
                data: {cUnitId:cUnitId, name:name, logo:logo}
            }).done(function(returnedData){
                $("#fMsgEditCUIcon").removeClass();//remove spinner

                if(returnedData.status === 1){
                    $("#fMsgEditCU").css('color', 'green').text(returnedData.msg);

                    //reset the form and close the modal
                    setTimeout(function(){
                        $("#fMsgEditCU").text("");
                        $("#editCUnitModal").modal('hide');
                    }, 1000);

                    //reset all error msgs in case they are set
                    changeInnerHTML(['nameCUEditErr', 'logoCUEditErr'], "");

                    //refresh bank list table
                    lacu_();

                }

                else{
                    //display error message returned
                    $("#fMsgEditAT").css('color', 'red').html(returnedData.msg);

                    //display individual error messages if applied
                    $("#nameATEditErr").html(returnedData.name);
                    $("#logoATEditErr").html(returnedData.logo);
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
    $("#cUnitSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/cUnitsearch",
                data: {v:value},
                success: function(returnedData){
                    $("#allCUnit").html(returnedData.cUnitTable);
                }
            });
        }
        
        else{
            lacu_();
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
    $("#allCUnit").on('click', '.deleteCUnit', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var cUnitId = ElemId.split("-")[1];//get the bankId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(cUnitId){
                $.ajax({
                    url: appRoot+"units/deleteCUnit",
                    method: "POST",
                    data: {_cuId:cUnitId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        lacu_();

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });
    
    
    //to launch the modal to allow for the editing of bank info
    $("#allCUnit").on('click', '.editCUnit', function(){
        
        var cUnitId = $(this).attr('id').split("-")[1];
        
        $("#cUnitId").val(cUnitId);
        
        //get info of bank with bankId and prefill the form with it
        //alert($(this).siblings(".bankEmail").children('a').html());
        var name = $(this).siblings(".name").html();
        var logo = $(this).siblings(".logo").html();
        
        //prefill the form fields
        $("#nameCUEdit").val(name);
        $("#logoCUEdit").val(logo);
        
        $("#editCUnitModal").modal('show');
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
 * lalu_ = "Load all loan units"
 * @returns {undefined}
 */
function lalu_(url){
    var orderBy = $("#lUnitListSortBy").val().split("-")[0];
    var orderFormat = $("#lUnitListSortBy").val().split("-")[1];
    var limit = $("#cUnitListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"units/lalu_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
			
            $("#allLUnit").html(returnedData.lUnitTable);
        });
}

/* *************************************************************************************** */

/**
 * lacu_ = "Load all collateral units"
 * @returns {undefined}
 */
function lacu_(url){
    var orderBy = $("#cUnitListSortBy").val().split("-")[0];
    var orderFormat = $("#cUnitListSortBy").val().split("-")[1];
    var limit = $("#cUnitListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"units/lacu_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
            
            $("#allCUnit").html(returnedData.cUnitTable);
        });
}