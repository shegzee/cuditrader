'use strict';

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm user's log in status
	
	
    //load all users once the page is ready
    //function header: laus_(url)
    laus_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of user when fields are changed
    $("#userListSortBy, #userListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        laus_();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#allUser").on('click', '.lnp', function(e){
        e.preventDefault();
		
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        laus_($(this).attr('href'));

        return false;
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //Check to ensure the password and retype password fields are the same
    $("#passwordDup").on('keyup change focusout focus focusin', function(){
        var orig = $("#passwordOrig").val();
        var dup = $("#passwordDup").val();
        
        if(dup !== orig){
            //show error
            $("#passwordDupErr").addClass('fa');
            $("#passwordDupErr").addClass('fa-times');
            $("#passwordDupErr").removeClass('fa-check');
            $("#passwordDupErr").css('color', 'red');
            $("#passwordDupErr").html("");
        }
        
        else{
            //show success
            $("#passwordDupErr").addClass('fa');
            $("#passwordDupErr").addClass('fa-check');
            $("#passwordDupErr").removeClass('fa-times');
            $("#passwordDupErr").css('color', 'green');
            $("#passwordDupErr").html("");
        }
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new user details .i.e. when "add user" button is clicked
    $("#addUserSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['firstNameErr', 'lastNameErr', 'emailErr', 'roleErr', 'mobileErr', 'mobile2Err', 'passwordOrigErr', 'passwordDupErr'],
        "");
        
        var firstName = $("#firstName").val();
        var lastName = $("#lastName").val();
        var email = $("#email").val();
        var mobile = $("#mobile").val();
        var addr = $("#addr").val();
        var passwordOrig = $("#passwordOrig").val();
        var passwordDup = $("#passwordDup").val();
        
        //ensure all required fields are filled
        if(!firstName || !lastName || !email || !mobile || !passwordOrig || !passwordDup){
            !firstName ? changeInnerHTML('firstNameErr', "required") : "";
            !lastName ? changeInnerHTML('lastNameErr', "required") : "";
            !email ? changeInnerHTML('emailErr', "required") : "";
            !mobile ? changeInnerHTML('mobileErr', "required") : "";
            !passwordOrig ? changeInnerHTML('passwordOrigErr', "required") : "";
            !passwordDup ? changeInnerHTML('passwordDupErr', 'required') : "";
            
            return;
        }
        
        //display message telling user action is being processed
        $("#fMsgIcon").attr('class', spinnerClass);
        $("#fMsg").text(" Processing...");
        
        //make ajax request if all is well
        $.ajax({
            method: "POST",
            url: appRoot+"users/add",
            data: {firstName:firstName, lastName:lastName, email:email, mobile:mobile, addr:addr,
                passwordOrig:passwordOrig, passwordDup:passwordDup}
        }).done(function(returnedData){
            $("#fMsgIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsg").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewUserForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsg").text("");
                    $("#addNewUserModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['firstNameErr', 'lastNameErr', 'emailErr', 'roleErr', 'mobileErr', 'mobile2Err', 'passwordOrigErr', 'passwordDupErr'],
                "");

                //refresh user list table
                laus_();

            }

            else{
                //display error message returned
                $("#fMsg").css('color', 'red').html(returnedData.msg);

                //display individual error messages if applied
                $("#firstNameErr").text(returnedData.firstName);
                $("#lastNameErr").text(returnedData.lastName);
                $("#emailErr").text(returnedData.email);
                $("#roleErr").text(returnedData.role);
                $("#mobileErr").text(returnedData.mobile);
                $("#mobile2Err").text(returnedData.mobile2);
                $("#passwordOrigErr").text(returnedData.passwordOrig);
                $("#passwordDupErr").text(returnedData.passwordDup);
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
    
    
    //handles the updating of user details
    $("#editUserSubmit").click(function(e){
        e.preventDefault();
        
        if(formChanges("editUserForm")){
            //reset all error msgs in case they are set
            changeInnerHTML(['firstNameEditErr', 'lastNameEditErr', 'emailEditErr', 'phoneEditErr', 'addressEditErr'], "");

            var firstName = $("#firstNameEdit").val();
            var lastName = $("#lastNameEdit").val();
            var email = $("#emailEdit").val();
            var phone = $("#phoneEdit").val();
            var address = $("#addressEdit").val();
            // var role = $("#roleEdit").val();
            var userId = $("#userId").val();

            //ensure all required fields are filled
            if(!firstName || !lastName || !email || !phone){
                !firstName ? changeInnerHTML('firstNameEditErr', "required") : "";
                !lastName ? changeInnerHTML('lastNameEditErr', "required") : "";
                !email ? changeInnerHTML('emailEditErr', "required") : "";
                !phone ? changeInnerHTML('phoneEditErr', "required") : "";

                return;
            }

            if(!userId){
                $("#fMsgEdit").text("An unexpected error occured while trying to update user's details");
                return;
            }

            //display message telling user action is being processed
            $("#fMsgEditIcon").attr('class', spinnerClass);
            $("#fMsgEdit").text(" Updating details...");

            //make ajax request if all is well
            $.ajax({
                method: "POST",
                url: appRoot+"users/update",
                data: {firstName:firstName, lastName:lastName, email:email, phone:phone, address:address, userId:userId}
            }).done(function(returnedData){
                $("#fMsgEditIcon").removeClass();//remove spinner

                if(returnedData.status === 1){
                    $("#fMsgEdit").css('color', 'green').text(returnedData.msg);

                    //reset the form and close the modal
                    setTimeout(function(){
                        $("#fMsgEdit").text("");
                        $("#editUserModal").modal('hide');
                    }, 1000);

                    //reset all error msgs in case they are set
                    changeInnerHTML(['firstNameEditErr', 'lastNameEditErr', 'emailEditErr', 'roleEditErr', 'phoneEditErr', 'addressEditErr'], "");

                    //refresh user list table
                    laus_();

                }

                else{
                    //display error message returned
                    $("#fMsgEdit").css('color', 'red').html(returnedData.msg);

                    //display individual error messages if applied
                    $("#firstNameEditErr").html(returnedData.firstName);
                    $("#lastNameEditErr").html(returnedData.lastName);
                    $("#emailEditErr").html(returnedData.email);
                    $("#mobileEditErr").html(returnedData.mobile);
                    $("#mobile2EditErr").html(returnedData.mobile2);
                    $("#roleEditErr").html(returnedData.role);
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
    
    
    //handles user search
    $("#userSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/usersearch",
                data: {v:value},
                success: function(returnedData){
                    $("#allUser").html(returnedData.userTable);
                }
            });
        }
        
        else{
            laus_();
        }
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //When the toggle on/off button is clicked to change the account status of an user (i.e. suspend or lift suspension)
    $("#allUser").on('click', '.suspendUser', function(){
        var ElemId = $(this).attr('id');
        
        var userId = ElemId.split("-")[1];//get the userId
        
        //show spinner
        $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");
        
        if(userId){
            $.ajax({
                url: appRoot+"users/suspend",
                method: "POST",
                data: {_uId:userId}
            }).done(function(returnedData){
                if(returnedData.status === 1){
                    //change the icon to "on" if it's "off" before the change and vice-versa
                    var newIconClass = returnedData._ns === 1 ? "fa fa-toggle-on pointer" : "fa fa-toggle-off pointer";
                    
                    //change the icon
                    $("#sus-"+returnedData._uId).html("<i class='"+ newIconClass +"'></i>");
                    
                }
                
                else{
                    console.log('err');
                }
            });
        }
    });
    
    
    /*
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    */
    
    
    //When the trash icon in front of a user account is clicked on the user list table (i.e. to delete the account)
    $("#allUser").on('click', '.deleteUser', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var userId = ElemId.split("-")[1];//get the userId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(userId){
                $.ajax({
                    url: appRoot+"users/delete",
                    method: "POST",
                    data: {_uId:userId}
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
    
    
    //to launch the modal to allow for the editing of user info
    $("#allUser").on('click', '.editUser', function(){
        
        var userId = $(this).attr('id').split("-")[1];
        
        $("#userId").val(userId);
        
        //get info of user with userId and prefill the form with it
        //alert($(this).siblings(".userEmail").children('a').html());
        var firstName = $(this).siblings(".firstName").html();
        var lastName = $(this).siblings(".lastName").html();
        var email = $(this).siblings(".userEmail").children('a').html();
        var phone = $(this).siblings(".userPhone").html();
        var address = $(this).siblings(".userAddress").html();
        
        //prefill the form fields
        $("#firstNameEdit").val(firstName);
        $("#lastNameEdit").val(lastName);
        $("#emailEdit").val(email);
        $("#phoneEdit").val(phone);
        $("#addressEdit").val(address);
        
        $("#editUserModal").modal('show');
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
 * laus_ = "Load all users"
 * @returns {undefined}
 */
function laus_(url){
    var orderBy = $("#userListSortBy").val().split("-")[0];
    var orderFormat = $("#userListSortBy").val().split("-")[1];
    var limit = $("#userListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"users/laus_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
			
            $("#allUser").html(returnedData.userTable);
        });
}