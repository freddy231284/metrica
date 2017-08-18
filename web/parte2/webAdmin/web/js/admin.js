jQuery(function () {
    $(document).ready(function () {
        // $('.js-datepicker').datepicker({
        //     format: 'yyyy-mm-dd'
        // });
    });
});

/**
 * Generate Document report such a Summary
 * @param url Url of the reception API
 * @param dataJson json to send to API
 */
function generateDocumentReport(url, dataJson) {
    var $btn = $(this).button('loading');
    $(this).addClass('disabled');
    $(this).prop('disabled', true);

    $.ajax({
        type: "POST",
        url: url,
        data: dataJson,
        dataType: 'json',
        success: function (data) {
            console.log('success... 1');
            console.log(data);
            // $("#report_generate_msg").find('div.alert').removeClass().addClass('alert alert-success');
            // $("#report_generate_msg").find('div.errorMessage').html(data.message);
        },
        error: function (xhr, status) {
            console.log(xhr);
            console.log(status);
            // var response = jQuery.parseJSON(xhr.responseText);
            // $("#report_generate_msg").find('div.alert').removeClass().addClass('alert alert-danger');
            // $("#report_generate_msg").find('div.errorMessage').html(response.message);
        },
        complete: function (xhr, status) {
            console.log('complete... 3');
            console.log(status);
            $btn.button('reset');
            $(this).removeClass('disabled');
            $(this).prop('disabled', false);
        }
    });
}