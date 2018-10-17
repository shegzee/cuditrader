$(document).ready(function(){

  $("#loan_amount, #collateral_unit_id").change(function(){
        // displayFlashMsg("Please wait...", spinnerClass, "", "");
        // load_all();
        $("#collateral_amount").prop('disabled', true);
        compute_collateral_amount();
        $("#collateral_amount").prop('disabled', false);
  });
  
  $("#collateral_amount, #loan_unit_id").change(function(){
        // displayFlashMsg("Please wait...", spinnerClass, "", "");
        // load_all();
        $("#loan_amount").prop('disabled', true);
        compute_loan_amount();
        $("#loan_amount").prop('disabled', false);
  });
  
    function compute_collateral_amount(url){
        var loan_unit_id = $("#loan_unit_id").val();
        var loan_amount = $("#loan_amount").val();
        var collateral_unit_id = $("#collateral_unit_id").val();
        if(!loan_unit_id || !loan_amount || !collateral_unit_id){
            return;
        }
        $.ajax({
            type:'get',
            url: url ? url : baseRoot+"loan/compute_collateral_amount/",
            data: {loan_unit_id:loan_unit_id, loan_amount:loan_amount, collateral_unit_id:collateral_unit_id},
         }).done(function(returnedData){
                // hideFlashMsg();
          
                $("#collateral_amount").val(returnedData.collateral_amount);
            });
    }

    function client_compute_collateral_amount(url) {
        var loan_unit_id = $("#loan_unit_id").val();
        var loan_amount = $("#loan_amount").val();
        var collateral_unit_id = $("#collateral_unit_id").val();

        var collateral_unit_price = 1;
        var collateral_unit_api_url = "";
        var loan_unit_exchange_rate = "";
        var markup = "";

        if(!loan_unit_id || !loan_amount || !collateral_unit_id){
            return;
        }

        var data = fetch_collateral_computation_data(url, loan_unit_id, loan_amount, collateral_unit_id);
        markup = data.markup;
        collateral_unit_api_url = data['collateral_unit_api_url'];
        loan_unit_exchange_rate = data['loan_unit_exchange_rate'];

        $.getJSON({
            type:'get',
            url: data['collateral_unit_api_url'] + "?format=json&jsoncallback=?",
        }).done(function(returnedData){
                // hideFlashMsg();
                collateral_unit_price = returnedData.price_usd;
        });
        var collateral_dollar_price = (loan_amount / loan_unit_exchange_rate) * ((100 - -data.markup) / 100); // worth of collateral to be obtained
        var collateral_amount = collateral_dollar_price / collateral_unit_price; // amount of collateral
          
        $("#collateral_amount").val(collateral_amount);
    }

    function fetch_collateral_computation_data(url, loan_unit_id, loan_amount, collateral_unit_id) {
        var data = new Array();
        $.ajax({
            type:'get',
            url: url ? url : baseRoot+"loan/collateral_computation_data",
            data: {loan_unit_id:loan_unit_id, loan_amount:loan_amount, collateral_unit_id:collateral_unit_id},
         }).done(function(returnedData){
                // hideFlashMsg();
                data['loan_unit_exchange_rate'] = returnedData.loan_unit_exchange_rate;
                data['collateral_unit_api_url'] = returnedData.collateral_unit_api_url;
                data['markup'] = returnedData.markup;
        });
         return data;
    }

    function compute_loan_amount(url){
        var loan_unit_id = $("#loan_unit_id").val();
        var collateral_unit_id = $("#collateral_unit_id").val();
        var collateral_amount = $("#collateral_amount").val();
        
        $.ajax({
            type:'get',
            url: url ? url : baseRoot+"loan/compute_loan_amount/",
            data: {loan_unit_id:loan_unit_id, collateral_amount:collateral_amount, collateral_unit_id:collateral_unit_id},
         }).done(function(returnedData){
                // hideFlashMsg();
          
                $("#loan_amount").val(returnedData.loan_amount);
            });
    }
});
