function yesIDO(){
    $("#step_one").addClass('d-none').animate().hide('slow');
    $("#step_two").removeClass('d-none').animate().show('fast');
    $("#selfservice_stage_progressbar").css('width', '20%');
}

function noIDont(){
    alert("Please Contact An Admin To Generate A Portal ID Before You Continue With This Process!");
}

function Next(){
    if ($("#pid").val() == ' ' || $("#pid").val() == '') {
        $("#err1").css('color','red')
        $("#err1").html("The Portal ID Field Cannot Be Empty!")
    } else if($("#tin").val() == ' ' || $("#tin").val() == '') {
        $("#err1").css('color','red')
        $("#err1").html("The Tax Identification Number Field Cannot Be Empty!")
    }else{
        $("#next").text("Loading......");
        var dd = $("#form1").serialize();
        $.post("utilities.php",dd,function(re)
        {
            console.log(re);
            if(re.response_code == 200)
                {
                    $("#next").prop('disabled',true);
                    $("#err1").css('color','green')
                    $("#err1").html(re.response_message)
                    $("#step_two").addClass('d-none').animate().show('slow');
                    $("#step_three").removeClass('d-none').animate().show('fast');
                    $("#selfservice_stage_progressbar").css('width', '60%');
                    // console.log(re);
                    $("#name").val(JSON.stringify(re.name).replace(/"/g, ""));
                    $("#owner_name").val(JSON.stringify(re.owner_name).replace(/"/g, ""));
                    $("#email").val(JSON.stringify(re.email).replace(/"/g, ""));
                    $("#phoneNumber").val(JSON.stringify(re.mobile).replace(/"/g, ""));
                    $("#address").val(JSON.stringify(re.address).replace(/"/g, ""));
                    $("#tinval").val(JSON.stringify(re.tin).replace(/"/g, ""));

                  if ($("#owner_name").val() == " " || $("#owner_name").val() == "null") {
                        console.log('Owner Name Field Does NOT Contain Data');
                    } else {
                        $("#owner_name").attr('readonly', true)
                    }
                    if ($("#name").val() == " " || $("#name").val() == "null") {
                        console.log('Name Field Does NOT Contain Data');
                    } else {
                        $("#name").attr('readonly', true)
                    }
                    if ($("#address").val() == " " || $("#address").val() == "null") {
                        console.log('Address Field Does NOT Contain Data');
                    } else {
                        $("#address").attr('readonly', true)
                    }
                    if ($("#email").val() == " " || $("#email").val() == "null") {
                        console.log('Email Field Does NOT Contain Data');
                    } else {
                        $("#email").attr('readonly', true)
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

                }
            else
                {
                    $("#next").text("Next");
                    $("#err1").css('color','red')
                    $("#err1").html(re.response_message)
                }
                
        },'json')
    }
      
}

$("#proceed").click(function(){
    $("#proceed").text("Loading......");
    var dd = $("#form2").serialize();
    $.post("utilities.php",dd,function(re)
    {
        console.log(re);
        if(re.response_code == 200)
            {
                $("#proceed").prop('disabled',true);
                $("#err2").css('color','green')
                $("#err2").html(re.response_message)
                $("#step_three").addClass('d-none').animate().show('slow');
                $("#step_four").removeClass('d-none').animate().show('fast');
                $("#selfservice_stage_progressbar").css('width', '90%');
                $("#tinforP").val(re.tin);
            }
        else
            {
                $("#proceed").text("Confirm $ Proceed");
                $("#err2").css('color','red')
                $("#err2").html(re.response_message)
            }
            
    },'json')
});

$("#Pay").click(function(){
    $("#Pay").text("Pls Wait ......");
    var dd = $("#form3").serialize();
    $.post("utilities.php",dd,function(re)
    {
        console.log(re.tin);
        if(re.response_code == 200)
            {
                $("#Pay").prop('disabled',true);
                $("#err3").css('color','green')
                $("#err3").html(re.response_message)
                $("#step_four").addClass('d-none').animate().show('fast');
                $("#selfservice_stage_progressbar").css('width', '100%');
            }
        else
            {
                $("#Pay").text("Make Payment");
                $("#err3").css('color','red')
                $("#err3").html(re.response_message)
            }
            
    },'json')
});
    
