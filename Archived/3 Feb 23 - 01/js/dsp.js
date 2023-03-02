function yesIDO(){
    $("#step_one").addClass('d-none').animate().hide('slow');
    $("#step_two").removeClass('d-none').animate().show('fast');
    $("#selfservice_stage_progressbar").css('width', '20%');
}

function noIDont(){
    alert("Kindly go to the Nearest Office or Registration center to Initiate the registration Process");
}

function Next(){
    if ($("#pid").val() == ' ' || $("#pid").val() == '') {
        $("#err1").css('color','red')
        $("#err1").html("The Portal ID Field Cannot Be Empty!")
    } else if($("#tin").val() == ' ' || $("#tin").val() == '') {
        $("#err1").css('color','red')
        $("#err1").html("The Tax Identification Number Field Cannot Be Empty!")
    }else{
       
        $("#next").text("Loading please wait...");
        $("#next").prop('disabled', true);
        var dd = $("#form1").serialize();

        $.ajax({
            type: "POST",
            url: "utilities.php",
            data:dd,
            dataType:"json",
            success: function(re){
            $("#next").text("Processed");
                console.log(re);
            if(re.response_code == 200){
                    $("#next").prop('disabled',true);
                    $("#err1").css('color','green')
                    $("#err1").html(re.response_message)
                    $("#step_two").addClass('d-none').animate().show('slow');
                    $("#step_three").removeClass('d-none').animate().show('fast');
                    $("#selfservice_stage_progressbar").css('width', '60%');

                    $("#titlename").val(JSON.stringify(re.title));
                    $("#first_name").val(JSON.stringify(re.name));
                    $("#middle_name").val(JSON.stringify(re.owner_name));
                    $("#surname").val(JSON.stringify(re.surname));
                    $("#phoneNumber").val(JSON.stringify(re.mobile));
                    $("#address").val(JSON.stringify(re.address));
                    $("#tinval").val(JSON.stringify(re.tin));
                    $("#port").val(JSON.stringify(re.port));

                  if ($("#middle_name").val() == " " || $("#middle_name").val() == "null") {
                        console.log('Middle NameField Does NOT Contain Data');
                    } else {
                        $("#middle_name").attr('readonly', true)
                    }
                    if ($("#first_name").val() == " " || $("#first_name").val() == "null") {
                        console.log('First Name Field Does NOT Contain Data');
                    } else {
                        $("#first_name").attr('readonly', true)
                    }
                    if ($("#address").val() == " " || $("#address").val() == "null") {
                        console.log('Address Field Does NOT Contain Data');
                    } else {
                        $("#address").attr('readonly', true)
                    }
                    if ($("#titlename").val() == " " || $("#titlename").val() == "null") {
                        console.log('Title Field Does NOT Contain Data');
                    } else {
                        $("#titlename").attr('readonly', true)
                    }
                    if ($("#surname").val() == " " || $("#surname").val() == "null") {
                        console.log('Surname Field Does NOT Contain Data');
                    } else {
                        $("#surname").attr('readonly', true)
                    }
                    if ($("#phoneNumber").val() == " " || $("#phoneNumber").val() == "null") {
                        console.log('Mobile Number Field Does NOT Contain Data');
                    } else {
                        $("#phoneNumber").attr('readonly', true)
                    }
                    if ($("#tinval").val() == " " || $("#tinval").val() == "null") {
                        console.log('TIN Field Does NOT Contain Data');
                    } else {
                        $("#tinval").attr('readonly', true)
                    }
                    if ($("#port").val() == " " || $("#tinval").val() == "null") {
                        console.log('TIN Field Does NOT Contain Data');
                    } else {
                        $("#port").attr('readonly', true)
                    }

                }else{
                    
                    $("#next").prop('disabled', false);
                    $("#next").text("Next");
                    $("#err1").css('color','red')
                    $("#err1").html(re.response_message)
                }
                
        }, error: function(re){
            $("#err1").css('color', 'red');
            $("#next").prop('disabled', false);
            $("#err1").html("Could not connect to server");
            $("#next").text("Next");
        }
    });
    }

    function containsAnyLetters(str) {
        return /[a-zA-Z]/.test(str);
      }
      function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
      }
    
      
}

$("#proceed").click(function(){
    $("#proceed").text("Loading please wait...");
    $("#proceed").prop('disabled', true);
    
    var dd = $("#form2").serialize();

    $.ajax({
        type: "POST",
        url: "utilities.php",
        data:dd,
        dataType:"json",
        success: function(re){
        $("#proceed").text("Processed");
            console.log(re);
        if(re.response_code == 200){
                $("#proceed").prop('disabled',true);
                    $("#err2").css('color','green')
                    $("#err2").html(re.response_message)
                    $("#step_three").addClass('d-none').animate().show('slow');
                    $("#step_four").removeClass('d-none').animate().show('fast');
                    $("#selfservice_stage_progressbar").css('width', '90%');
                    $("#tinforP").val(re.port);
            }else{
                $("#proceed").text("Confirm & Proceed");
                $("#proceed").prop('disabled', false);
                $("#err2").css('color','red')
                $("#err2").html(re.response_message)
            }
            
    }, error: function(re){
        $("#err2").css('color', 'red');
        $("#proceed").prop('disabled', false);
        $("#err2").html("Could not connect to server");
        $("#proceed").text("Confirm & Proceed");
    }
});
});

$("#Pay").click(function(){
    $("#Pay").text("Loading please wait...");
    $("#Pay").prop('disabled', true);
    var dd = $("#form3").serialize();

    $.ajax({
        type: "POST",
        url: "utilities.php",
        data:dd,
        dataType:"json",
        success: function(re){
            $("#Pay").text("Processed");
            console.log(re);
            var redirect = re.redirect_url;

        if(re.response_code == 200 && re.redirect_url == null){
                $("#Pay").prop('disabled', false);
                $("#Pay").text("Make Payment");
                $("#err3").css('color','red');
                $("#err3").html("Could not load payment interface. Please try again");

        }else if(re.response_code == 200 && re.redirect_url !== null){
            
            $("#Pay").text("Processed");
            $("#Pay").prop('disabled',true);
                PrintPage(redirect);
            }else{
                
                $("#Pay").prop('disabled', false);
                $("#Pay").text("Make Payment");
                $("#err3").css('color','red')
                $("#err3").html(re.response_message)
            }
            
    }, error: function(re){
        $("#err3").css('color', 'red');
        $("#Pay").prop('disabled', false);
        $("#err3").html("Could not connect to server");
        $("#Pay").text("Make Payment");
    }
});
});

function PrintPage(redirect) {
   
    window.open(redirect, '_blank');
    
}
    

function Nextr(){
    if ($("#pid").val() == ' ' || $("#pid").val() == '') {
        $("#err1").css('color','red')
        $("#err1").html("The Portal ID Field Cannot Be Empty!")
    }else{
        $("#nextr").text("Loading please wait...");
        $("#nextr").prop('disabled', true);
        var dd = $("#form1").serialize();
        $.ajax({
            type: "POST",
            url: "utilities.php",
            data:dd,
            dataType:"json",
            success: function(re){
            $("#nextr").text("Processed");
            if(re.response_code == 200)
                {
                    $("#nextr").prop('disabled',true);
                    $("#err1").css('color','green')
                    $("#err1").html(re.response_message)
                    $("#step_oner").addClass('d-none').animate().show('slow');
                    $("#step_twor").removeClass('d-none').animate().show('fast');
                    $("#selfservice_stage_progressbar").css('width', '30%');

                    
                    $("#titlename").val(JSON.stringify(re.title));
                    $("#first_name").val(JSON.stringify(re.name));
                    $("#middle_name").val(JSON.stringify(re.owner_name));
                    $("#surname").val(JSON.stringify(re.surname));
                    $("#phoneNumber").val(JSON.stringify(re.mobile));
                    $("#address").val(JSON.stringify(re.address));
                    $("#tinval").val(JSON.stringify(re.tin));
                    $("#port").val(JSON.stringify(re.port));
                    $("#exp").val(JSON.stringify(re.exp));

                  if ($("#middle_name").val() == " " || $("#middle_name").val() == "null") {
                        console.log('Middle NameField Does NOT Contain Data');
                    } else {
                        $("#middle_name").attr('readonly', true)
                    }
                    if ($("#first_name").val() == " " || $("#first_name").val() == "null") {
                        console.log('First Name Field Does NOT Contain Data');
                    } else {
                        $("#first_name").attr('readonly', true)
                    }
                    if ($("#address").val() == " " || $("#address").val() == "null") {
                        console.log('Address Field Does NOT Contain Data');
                    } else {
                        $("#address").attr('readonly', true)
                    }
                    if ($("#titlename").val() == " " || $("#titlename").val() == "null") {
                        console.log('Title Field Does NOT Contain Data');
                    } else {
                        $("#titlename").attr('readonly', true)
                    }
                    if ($("#surname").val() == " " || $("#surname").val() == "null") {
                        console.log('Surname Field Does NOT Contain Data');
                    } else {
                        $("#surname").attr('readonly', true)
                    }
                    if ($("#phoneNumber").val() == " " || $("#phoneNumber").val() == "null") {
                        console.log('Mobile Number Field Does NOT Contain Data');
                    } else {
                        $("#phoneNumber").attr('readonly', true)
                    }
                    if ($("#tinval").val() == " " || $("#tinval").val() == "null") {
                        console.log('TIN Field Does NOT Contain Data');
                    } else {
                        $("#tinval").attr('readonly', true)
                    }
                    if ($("#port").val() == " " || $("#tinval").val() == "null") {
                        console.log('TIN Field Does NOT Contain Data');
                    } else {
                        $("#port").attr('readonly', true)
                    }
                    if ($("#exp").val() == " " || $("#exp").val() == "null") {
                        console.log('Expiry date Field Empty');
                    } else {
                        $("#exp").attr('readonly', true)
                    }

                }else{
                    $("#nextr").prop('disabled', false);
                    $("#nextr").text("Next");
                    $("#err1").css('color','red')
                    $("#err1").html(re.response_message)
                }
                
        }, error: function(re){
            $("#err1").css('color', 'red');
            $("#nextr").prop('disabled', false);
            $("#err1").html("Could not connect to server");
            $("#nextr").text("Next");
        }
    });
    }

    function containsAnyLetters(str) {
        return /[a-zA-Z]/.test(str);
      }
      function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
      }
    
      
}

$("#proceedr").click(function(){
    $("#proceedr").text("Loading please wait...");
    $("#proceedr").prop('disabled', true);
    var dd = $("#form2").serialize();
    $.ajax({
        type: "POST",
        url: "utilities.php",
        data:dd,
        dataType:"json",
        success: function(re){
        $("#proceedr").text("Processed");
            console.log(re);
        if(re.response_code == 200)
            {
                $("#proceedr").prop('disabled',true);
                    $("#err2").css('color','green')
                    $("#err2").html(re.response_message)
                    $("#step_twor").addClass('d-none').animate().show('slow');
                    $("#step_threer").removeClass('d-none').animate().show('fast');
                    $("#selfservice_stage_progressbar").css('width', '80%');
                    $("#tinforP").val(re.tinforP);
                    $("#renew").val(re.status);
            }else{
                $("#proceedr").text("Confirm & Proceed");
                $("#proceedr").prop('disabled', false);
                $("#err2r").css('color','red')
                $("#err2").html(re.response_message)
            }
            
    }, error: function(re){
        $("#err2").css('color', 'red');
        $("#proceedr").prop('disabled', false);
        $("#err2").html("Could not connect to server");
        $("#proceedr").text("Confirm & Proceed");
    }
});
});

$("#Payr").click(function(){
    $("#Payr").text("Loading please wait...");
    $("#Payr").prop('disabled', true);

    var dd = $("#form3").serialize();
   $.ajax({
        type: "POST",
        url: "utilities.php",
        data:dd,
        dataType:"json",
        success: function(re){
            $("#Payr").text("Processed");
        var redirect = re.redirect_url;
        if(re.response_code == 200 && re.redirect_url == null){
            $("#Payr").prop('disabled', false);
            $("#Payr").text("Make Payment");
            $("#err3").css('color','red');
            $("#err3").html("Could not load payment interface. Please try again");

    }else if(re.response_code == 200 && re.redirect_url !== null){
        
        $("#Payr").text("Processed");
        $("#Payr").prop('disabled',true);
            PrintPage(redirect);
        }else{
            
            $("#Payr").prop('disabled', false);
            $("#Payr").text("Make Payment");
            $("#err3").css('color','red')
            $("#err3").html(re.response_message)
        }
    }, error: function(re){
        $("#err3").css('color', 'red');
        $("#Payr").prop('disabled', false);
        $("#err3").html("Could not connect to server");
        $("#Payr").text("Make Payment");
    }
    });
});

function PrintPage(redirect) {
   
    window.open(redirect, '_blank');
    
}
    
