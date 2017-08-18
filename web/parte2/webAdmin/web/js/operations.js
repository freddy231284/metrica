function editDocument(file) {

    $(".contentMessage1").addClass("hidden");
    $(".contentMessage2").addClass("hidden");
    $(".contentMessage3").addClass("hidden");

    $.ajax({
        url: './documentEditHeader/' + file,
        //data : { id : file },
        type: 'GET',
        //dataType : 'json',
        success: function (data) {
            $('#headerDocument').html(data);
            $('html,body').animate({
                scrollTop: $("#headerDocument").offset().top
            }, 1000);

            if ($("#documentType").val() == 'VD') {
                $("#ubigeo").val("-");
                $("#companyName").val("-");
                $("#address").val("-");
                $("#serie").val("-");
                $("#number").val("-");

                $("#ubigeo").prop('disabled', true);
                $("#companyName").prop('disabled', true);
                $("#address").prop('disabled', true);
                $("#serie").prop('disabled', true);
                $("#number").prop('disabled', true);

            }
        },
        error: function (xhr, status) {
            alert('Existió un problema');
        },
        complete: function (xhr, status) {
            //alert('Petición realizada');
        }
    });

}

function deleteDocument(file, obj) {

    if (confirm("¿Está seguro de eliminar el comprobante?")) {

        $(".contentMessage1").addClass("hidden");
        $(".contentMessage2").addClass("hidden");
        $(".contentMessage3").addClass("hidden");

        $.ajax({
            url: './documentDelete/' + file,
            type: 'GET',
            success: function (data) {
                $(obj).closest('tr').remove();
            },
            error: function (xhr, status) {
            },
            complete: function (xhr, status) {
            }
        });
    }

}


function sendTestAll() {

    if (typeof $("#documentType").val() === "undefined") {
        alert("Tiene que seleccionar los datos de cabecera de al menos un comprobante");
        return false;
    }

    if (confirm("Solo serán modificados los datos de Emisor para los comprobantes del tipo: " + $("#documentType").val())) {
        var dataEmisor = [];
        dataEmisor.push({
            "ruc": $("#ruc").val(),
            "companyName": $("#companyName").val(),
            "ubigeo": $("#ubigeo").val(),
            "address": $("#address").val(),
            "documentType": $("#documentType").val(),
            "case": $("#caseSel").val()
        });

        $(".contentMessage1").addClass("hidden");
        $(".contentMessage2").addClass("hidden");
        $(".contentMessage3").addClass("hidden");
        $(".loading").removeClass("hidden");

        $.ajax({
            type: 'POST',
            url: './sendJsonTestAll',
            data: dataEmisor[0],
            dataType: 'json',
            success: function (data) {

                //console.log(data);
                //console.log(data[0].message);
                var dataHTML = "";
                for (var i = 0; i < data.length; i++) {
                    dataHTML += "- " + data[i].message + "<br>";
                }

                $('#message3').html(dataHTML);
                $(".contentMessage3").removeClass("hidden");

                $(".loading").addClass("hidden");
                $('html,body').animate({
                    scrollTop: $(".contentMessage3").offset().top
                }, 1000);

            },
            error: function (xhr, status) {
                alert('Existió un problema');
            },
            complete: function (xhr, status) {
                //alert('Petición realizada');
            }
        });

    }


}

function sendTest() {
    var file = $("#file").val();
    var ruc = $("#ruc").val();
    var companyName = $("#companyName").val();
    var ubigeo = $("#ubigeo").val();
    var address = $("#address").val();
    var issueDate = $("#issueDate").val();
    var serie = $("#serie").val();
    var number = $("#number").val();
    var registrationName = $("#registrationName").val();
    var company = $("#company").val();

    $(".contentMessage1").addClass("hidden");
    $(".contentMessage2").addClass("hidden");
    $(".contentMessage3").addClass("hidden");
    $(".loading").removeClass("hidden");


    var i = 1;
    $.ajax({
        url: './sendJsonTest/' + file + '/' + ruc + '/' + companyName + '/' + ubigeo + '/' + address + '/' + issueDate + '/' + serie + '/' + number + '/' + registrationName+ '/' + company,
        type: 'GET',
        success: function (data) {

            if (data.statusCode == 400) {
                i = 1;
                $('#message1').html(data.message);
                $(".contentMessage1").removeClass("hidden")
            }
            if (data.statusCode == 200) {
                i = 2;
                $('#message2').html(data.message);
                $(".contentMessage2").removeClass("hidden")
            }
            $(".loading").addClass("hidden");

            $('html,body').animate({
                scrollTop: $(".contentMessage" + i).offset().top
            }, 1000);

        },
        error: function (xhr, status) {
            alert('Existió un problema');
        },
        complete: function (xhr, status) {
            //alert('Petición realizada');
        }
    });
}

function sendTestNewCase() {

    $(".contentMessage1").addClass("hidden");
    $(".contentMessage2").addClass("hidden");
    $(".contentMessage3").addClass("hidden");
    $(".loading").removeClass("hidden");

    var dataString = $('#form_account').serialize();
    var i = 1;
    $.ajax({
        type: "POST",
        url: "./testNewCase",
        data: dataString,
        dataType: 'json',
        success: function (data) {
            console.log(data)
            if (data.statusCode == 400) {
                i = 1;
                $('#message1').html(data.message);
                $(".contentMessage1").removeClass("hidden")
            }
            if (data.statusCode == 200) {
                i = 2;
                $('#message2').html(data.message);
                $(".contentMessage2").removeClass("hidden")
            }
            $(".loading").addClass("hidden");

            $('html,body').animate({
                scrollTop: $(".contentMessage" + i).offset().top
            }, 1000);

        },
        error: function (xhr, status) {
        },
        complete: function (xhr, status) {
            //console.log(xhr);
        }
    });
}

function sendTestVoided() {

    $(".contentMessage1").addClass("hidden");
    $(".contentMessage2").addClass("hidden");
    $(".contentMessage3").addClass("hidden");
    $(".loading").removeClass("hidden");

    var dataString = $('#form_voided').serialize();
    //console.log(dataString)
    var i = 1;
    $.ajax({
        type: "POST",
        url: "./testVoided",
        data: dataString,
        dataType: 'json',
        success: function (data) {

            if (data.statusCode == 400) {
                i = 1;
                $('#message1').html(data.message);
                $(".contentMessage1").removeClass("hidden")
            }
            if (data.statusCode == 200) {
                i = 2;
                $('#message2').html(data.message);
                $(".contentMessage2").removeClass("hidden")
            }
            $(".loading").addClass("hidden");

            $('html,body').animate({
                scrollTop: $(".contentMessage" + i).offset().top
            }, 1000);

        },
        error: function (xhr, status) {
        },
        complete: function (xhr, status) {
            //console.log(xhr);
        }
    });
}



$(document).on("click", ".panel-heading", function () {
    $(this).siblings('div').slideToggle("slow");
});

$(function () {


    $(document).on("click", "#agregar", function () {
        $("#tabla thead tr:eq(1)").clone().removeClass('hidden').appendTo("#tabla tbody");
        var trs = $("#tabla tbody tr").length;
        $("#tabla tr:last td")[0].innerHTML = trs;
    });
    $(document).on("click", "#agregar2", function () {
        $("#tabla2 thead tr:eq(1)").clone().removeClass('hidden').appendTo("#tabla2 tbody");
        var trs = $("#tabla2 tbody tr").length;
        $("#tabla2 tr:last td")[0].innerHTML = trs;
    });

    $(document).on("click", "#agregar3", function () {
        $("#tabla3 thead tr:eq(1)").clone().removeClass('hidden').appendTo("#tabla3 tbody");
        var trs = $("#tabla3 tbody tr").length;
        $("#tabla3 tr:last td")[0].innerHTML = trs;
    });
    $(document).on("click", "#agregar4", function () {
        $("#tabla4 thead tr:eq(1)").clone().removeClass('hidden').appendTo("#tabla4 tbody");
        var trs = $("#tabla4 tbody tr").length;
        $("#tabla4 tr:last td")[0].innerHTML = trs;
    });
    $(document).on("click", "#agregar5", function () {
        $("#tabla5 thead tr:eq(1)").clone().removeClass('hidden').appendTo("#tabla5 tbody");
        var trs = $("#tabla5 tbody tr").length;
        $("#tabla5 tr:last td")[0].innerHTML = trs;
    });

    $(document).on("click", "#eliminar", function () {
        var parent = $(this).parents().parents().get(0);
        var i = $(this).attr("data-content");
        //console.log(i);
        $(parent).remove();
        $("#tabla" + i + " tbody tr").each(function (index) {
            $(this).children("td")[0].innerHTML = index + 1;
        })
    });

    var objGlobal = "";
    $(document).on("click", "#details", function () {
        $('#myModal').modal('show');
        var index = $(this).parents().parents().children("td")[0].innerHTML - 1;
        objGlobal = index;
        $("#Migv_percent").val($($("input[name='igv_percent[]']")[index]).val());
        $("#Migv_total").val($($("input[name='igv_total[]']")[index]).val());
        $("#Migv_afec").val($($("input[name='igv_afec[]']")[index]).val());
        $("#Misc_percent").val($($("input[name='isc_percent[]']")[index]).val());
        $("#Misc_total").val($($("input[name='isc_total[]']")[index]).val());
        $("#Misc_afec").val($($("input[name='isc_afec[]']")[index]).val());
        $("#Mother_percent").val($($("input[name='other_percent[]']")[index]).val());
        $("#Mother_total").val($($("input[name='other_total[]']")[index]).val());

    });

    $(document).on("click", "#aceptar", function () {
        $($("input[name='igv_percent[]']")[objGlobal]).val($("#Migv_percent").val());
        $($("input[name='igv_total[]']")[objGlobal]).val($("#Migv_total").val());
        $($("input[name='igv_afec[]']")[objGlobal]).val($("#Migv_afec").val());
        $($("input[name='isc_percent[]']")[objGlobal]).val($("#Misc_percent").val());
        $($("input[name='isc_total[]']")[objGlobal]).val($("#Misc_total").val());
        $($("input[name='isc_afec[]']")[objGlobal]).val($("#Misc_afec").val());
        $($("input[name='other_percent[]']")[objGlobal]).val($("#Mother_percent").val());
        $($("input[name='other_total[]']")[objGlobal]).val($("#Mother_total").val());

    });

    $(document).on("click", "#save_case", function () {

        $(".contentMessage1").addClass("hidden");
        $(".contentMessage2").addClass("hidden");
        $(".contentMessage3").addClass("hidden");


        if ($("#descriptionCase").val() == '') {
            alert("Ingrese un nombre para el caso");
            $("#descriptionCase").focus();
            return false;
        }
        if ($("#listCase").val() == '00') {
            alert("Seleccione un caso");
            $("#listCase").focus();
            return false;
        }
        $(".loading").removeClass("hidden");
        var dataString = $('#form_account').serialize();
        $.ajax({
            type: "POST",
            url: "./saveCase",
            data: dataString,
            dataType: 'json',
            success: function (data) {

            },
            error: function (xhr, status) {
            },
            complete: function (xhr, status) {
                $(".loading").addClass("hidden");
                $('#msg1').html("Nuevo caso guardado correctamente");

                setTimeout(function(){
                    location.reload();
                }, 1000);

            /*
                $(":text").each(function () {
                    $($(this)).val('');
                });
             */

            }
        });
    });

    $('#listDoc').on('change', function () {
        $.ajax({
            type: "GET",
            url: "./loadFields/" + this.value,
            success: function (data) {
                $('#headerDocument').html(data);

                for (var i = 1; i <= 7; i++) {
                    $(".body" + i).slideToggle("slow");
                }
            }
        });
    });

    $('.updateCaptcha').on('click', function () {

        $.ajax({
            type: "GET",
            url: "./updateCaptcha",
            success: function (data) {
                $('#imgCaptcha').html(data);


            }
        });
    })

    if($("div").hasClass("form_date")){
        $('.form_date').datetimepicker({
            language: 'es',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
    }

    $('.lk1').on('click', function () {
        var n=$(this).attr("data-content");

        $("#link1").addClass("hidden");
        $("#link2").addClass("hidden");
        $("#link3").addClass("hidden");
        $("#link4").addClass("hidden");

        if(n==1) {
            $("#link1").removeClass("hidden");
        }
        if(n==2) {
            $("#link2").removeClass("hidden");
        }
        if(n==3) {
            $("#link3").removeClass("hidden");
        }
        if(n==4) {
            $("#link4").removeClass("hidden");
        }

    })

    $(document).on("click", "#catalog", function () {
        var n=$(this).attr("data-content");
        $("#catalog1").addClass("hidden");
        $("#catalog2").addClass("hidden");
        $("#catalog3").addClass("hidden");
        $("#catalog4").addClass("hidden");
        $("#catalog5").addClass("hidden");
        $("#catalog6").addClass("hidden");
        //console.log(n);
        $("#catalog"+n).removeClass("hidden");
        $('#myModal').modal('show');
    });

    $(document).on("click", ".btnRetro", function () {
        location.reload();
    });

});

function findDocument(){

    //if($("#supllier").val()=='00'){alert("Seleccione un Emisor");return false};
    if($("#listDoc").val()=='00'){alert("Seleccione un documento");return false};
    if($("#serial").val()==''){alert("ingrese serie del documento");return false};
    if($("#number").val()==''){alert("Ingrese número del documento");return false};
    if($("#amount").val()==''){alert("Ingrese un monto total");return false};
    if($("#date").val()==''){alert("Ingrese una fecha");return false};
    if($("#textCaptcha").val()==''){alert("Digite el código de la imágen");return false};

    var dataString = $('#form_client').serialize();

    $(".contentMessage1").removeClass("hidden");

    $.ajax({
        type: "POST",
        url: "./findDocument",
        data: dataString,
        dataType: 'json',
        success: function (data) {
        },
        error: function (xhr, status) {
        },
        complete: function (xhr, status) {
           //console.log(xhr.responseText);

            if(xhr.responseText=='0'){
                $(".alert").removeClass("hidden")
                $("#lblmsg").html("Código captcha incorrecto");
                $('.updateCaptcha').click();
                return false;
            }else{
                if(xhr.responseText=='1'){
                    $(".alert").removeClass("hidden")
                    $("#lblmsg").html("No existen documentos coincidentes");
                    $('.updateCaptcha').click();
                    return false;
                }else{
                    $("#form_client").addClass("hidden");
                    $(".docsCustomer").html(xhr.responseText);
                    return false;
                }
            }
        }
    });
}

function clearFields(){

    //$("#listDoc").val('00');
    $("#serial").val('');
    $("#number").val('');
    $("#amount").val('');
    $("#date").val('');

}
