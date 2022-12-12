function yesIDO(){
    $("#step_one").addClass('d-none').animate().hide('slow');
    $("#step_two").removeClass('d-none').animate().show('fast');
    $("#selfservice_stage_progressbar").css('width', '20%');
}

function noIDont(){
    $("#step_one").addClass('d-none').animate().hide('slow');
    $("#step_off1").removeClass('d-none').animate().show('fast');
    $("#selfservice_stage_progressbar").css('width', '20%');
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
                    $("#ref").val(JSON.stringify(re.ref).replace(/"/g, ""));
                    $("#plate").val(JSON.stringify(re.plate).replace(/"/g, ""));
                    $("#vehmake").val(JSON.stringify(re.vehmake).replace(/"/g, ""));
                    $("#vehtype").val(JSON.stringify(re.vehtype).replace(/"/g, ""));
                    $("#catego").val(JSON.stringify(re.categ).replace(/"/g, ""));
                    $("#address").val(JSON.stringify(re.address).replace(/"/g, ""));
                    $("#tinval").val(JSON.stringify(re.tin).replace(/"/g, ""));
                    $("#chasis").val(JSON.stringify(re.chasis).replace(/"/g, ""))
                    $("#count").val(JSON.stringify(re.count).replace(/"/g, ""));
                    $("#price").val(JSON.stringify(re.price).replace(/"/g, ""));

                  if ($("#plate").val() == " " || $("#plate").val() == "null") {
                        console.log('Plate Number Field Does NOT Contain Data');
                    } else {
                        $("#plate").attr('readonly', true)
                    }
                    if ($("#vehmake").val() == " " || $("#vehmake").val() == "null") {
                        console.log('Vehicle Make Field Does NOT Contain Data');
                    } else {
                        $("#vehmake").attr('readonly', true)
                    }
                    if ($("#address").val() == " " || $("#address").val() == "null") {
                        console.log('Address Field Does NOT Contain Data');
                    } else {
                        $("#address").attr('readonly', true)
                    }
                    if ($("#vehtype").val() == " " || $("#vehtype").val() == "null") {
                        console.log('Vehicle Type Field Does NOT Contain Data');
                    } else {
                        $("#vehtype").attr('readonly', true)
                    }
                    if ($("#categ").val() == " " || $("#categ").val() == "null") {
                        console.log('Category Field Does NOT Contain Data');
                    } else {
                        $("#categ").attr('readonly', true)
                    }

                    if ($("#count").val() == " " || $("#count").val() == "null") {
                        console.log('Number Of Offences Field Does NOT Contain Data');
                    } else {
                        $("#count").attr('readonly', true)
                    }
                    if ($("#price").val() == " " || $("#price").val() == "null") {
                        console.log('Total Price Field Does NOT Contain Data');
                    } else {
                        $("#price").attr('readonly', true)
                    }

                    if ($("#tinval").val() == " " || $("#tinval").val() == "null") {
                        console.log('TIN Field Does NOT Contain Data');
                    } else {
                        $("#tinval").attr('readonly', true)
                    }
                    if ($("#chasis").val() == " " || $("#chasis").val() == "null") {
                        console.log('Chasis Number Field Does NOT Contain Data');
                    } else {
                        $("#chasis").attr('readonly', true)
                    }
                    if ($("#ref").val() == " " || $("#ref").val() == "null") {
                        console.log('Reference Number Field Does NOT Contain Data');
                    } else {
                        $("#ref").attr('readonly', true)
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

$("#proceed1").click(function(){
    $("#proceed1").text("Loading......");
    var dd = $("#form2").serialize();
    $.post("utilities.php",dd,function(re)
    {
        console.log(re);
        var id = re.reference_code;
        if(re.response_code == 200)
            {
                $("#proceed1").prop('disabled',true);
                $("#err2").css('color','green');
                $("#err2").html(re.response_message);
                $("#selfservice_stage_progressbar").css('width', '90%');
                setTimeout(() => {
                    window.open('./slip/offence_payment_slip.php?id='+id, '_blank');
                }, 1000);
            }
        else
            {
                $("#proceed1").text("Confirm $ Proceed");
                $("#err2").css('color','red');
                $("#err2").html(re.response_message);
            }
            
    },'json')
});

$("#offPay").click(function(){
    $("#offPay").text("Loading......");
    var dd = $("#form12").serialize();
    $.post("utilities.php",dd,function(re)
    {
        console.log(re);
        var id = re.reference_code;
        if(re.response_code == 200)
            {
                $("#offPay").prop('disabled',true);
                $("#err12").css('color','green');
                $("#err12").html(re.response_message);
                $("#selfservice_stage_progressbar").css('width', '90%');
                setTimeout(() => {
                    window.open('./slip/offence_payment_slip.php?id='+id, '_blank');
                }, 1000);
            }
        else
            {
                $("#offPay").text("Confirm $ Proceed");
                $("#err12").css('color','red');
                $("#err12").html(re.response_message);
            }
            
    },'json')
});

$("#off1").click(function(){
    if ($("#track").val() == ' ' || $("#track").val() == '') {
       alert("Please Select Atleast One Offence ");
    }else{
       $("#off1").text("Loading......");
        var dd = $("#form11").serialize();
        $.post("utilities.php",dd,function(re)
        {
            console.log(re);
            if(re.response_code == 200)
                {
                    var make = JSON.stringify(re.make).replace(/"/g, "");
                    var taxPayer = JSON.stringify(re.taxPayer).replace(/"/g, "");
                    var model = JSON.stringify(re.model).replace(/"/g, "");
                    var chasis = JSON.stringify(re.chasis).replace(/"/g, "");
                    var plate = JSON.stringify(re.plate).replace(/"/g, "");
                    var tin = JSON.stringify(re.tin).replace(/"/g, "");
                    var ids = JSON.stringify(re.ids).replace(/"/g, "");
                    var phone = JSON.stringify(re.phone).replace(/"/g, "");
                    var address = JSON.stringify(re.address).replace(/"/g, "");

                    $("#step_off1").addClass('d-none').animate().hide('slow');
                    $("#step_off2").removeClass('d-none').animate().show('fast');
                    $("#selfservice_stage_progressbar").css('width', '70%');
                    $("#amount").val(JSON.stringify(re.total+'.00').replace(/"/g, ""));
                    $("#name").val(taxPayer);
                    $("#vehmakeD").val(make);
                    $("#vehtypeD").val(model);
                    $("#chasisD").val(chasis);
                    $("#ids").val(ids);
                    $("#plateNN").val(plate);
                    $("#tinff").val(tin);
                    $("#phoneD").val(phone);
                    $("#addressD").val(address);

                   
                    if ($("#name").val() == " " || $("#name").val() == "null") {
                        console.log('Name Field Does NOT Contain Data');
                    } else {
                        $("#name").attr('readonly', true)
                    }
                    if ($("#vehmakeD").val() == " " || $("#vehmakeD").val() == "null") {
                        console.log('Vehicle Make Field Does NOT Contain Data');
                    } else {
                        $("#vehmakeD").attr('readonly', true)
                    }
                    if ($("#vehtypeD").val() == " " || $("#vehtypeD").val() == "null") {
                        console.log('Vehicle Type Does NOT Contain Data');
                    } else {
                        $("#vehtypeD").attr('readonly', true)
                    }
                    if ($("#chasisD").val() == " " || $("#chasisD").val() == "null") {
                        console.log('Chasis Number Field Does NOT Contain Data');
                    } else {
                        $("#chasisD").attr('readonly', true)
                    }
                    if ($("#plateNN").val() == " " || $("#plateNN").val() == "null") {
                        console.log('Plate Number Field Does NOT Contain Data');
                    } else {
                        $("#plateNN").attr('readonly', true)
                    }
                    if ($("#phoneD").val() == " " || $("#phoneD").val() == "null") {
                        console.log('Phone Number Field Does NOT Contain Data');
                    } else {
                        $("#phoneD").attr('readonly', true)
                    }
                    if ($("#addressD").val() == " " || $("#addressD").val() == "null") {
                        console.log('Address Field Does NOT Contain Data');
                    } else {
                        $("#addressD").attr('readonly', true)
                    }
                
                }
            else
                {
                    $("#off1").text("Next");
                    $("#errOff").css('color','red')
                    $("#errOff").html(re.response_message)
                }
                
        },'json')  
    }
   
});





    
